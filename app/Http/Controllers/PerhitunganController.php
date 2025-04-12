<?php

namespace App\Http\Controllers;

use App\Models\Hasil;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PerhitunganController extends Controller
{
    public function index(): View
    {
        $siswas = Siswa::with('penilaians')->get();
        $kriterias = Kriteria::all();

        $calculationData = [
            'has_errors' => false,
            'error_messages' => [],
        ];

        if ($siswas->isEmpty()) {
            $calculationData['has_errors'] = true;
            $calculationData['error_messages'][] = 'Tidak ada data siswa untuk diproses.';
        }

        if ($kriterias->isEmpty()) {
            $calculationData['has_errors'] = true;
            $calculationData['error_messages'][] = 'Tidak ada data kriteria untuk diproses.';
        }

        if ($calculationData['has_errors']) {
            return view('perhitungan.index', compact('calculationData'));
        }

        // Ambil semua periode unik dari data penilaian
        $allPenilaians = Penilaian::all();
        $periodeList = $allPenilaians->pluck('periode')->unique()->sort()->values();
        $jumlahPeriode = $periodeList->count();

        $calculationData['periode_penilaian'] = $periodeList;

        // Normalisasi Bobot
        $totalBobot = $kriterias->sum('bobot');
        $bobotNormal = [];
        foreach ($kriterias as $kriteria) {
            $bobotNormal[$kriteria->id] = $kriteria->bobot / $totalBobot;
        }

        $calculationData['bobot_kriteria'] = $kriterias->mapWithKeys(function ($item) use ($bobotNormal) {
            return [$item->id => ['nama' => $item->nama, 'bobot_awal' => $item->bobot, 'bobot_normal' => $bobotNormal[$item->id]]];
        })->all();

        // Tahap 1: Agregasi Nilai
        $dataAgregasi = [];
        foreach ($siswas as $siswa) {
            $dataAgregasi[$siswa->nisn] = [];
            $penilaianSiswa = $siswa->penilaians->keyBy(function ($item) {
                return $item->kriteria_id . '_' . $item->periode;
            });

            foreach ($kriterias as $kriteria) {
                $totalNilaiPeriode = 0;
                $nilaiDitemukan = false;

                foreach ($periodeList as $periode) {
                    $key = $kriteria->id . '_' . $periode;
                    if (isset($penilaianSiswa[$key])) {
                        $totalNilaiPeriode += $penilaianSiswa[$key]->nilai;
                        $nilaiDitemukan = true;
                    }
                }

                $dataAgregasi[$siswa->nisn][$kriteria->id] = $nilaiDitemukan ? $totalNilaiPeriode : null;
            }
        }
        $calculationData['data_agregasi'] = $dataAgregasi;

        // Tahap 2: Min/Max
        $minMaxValues = [];
        foreach ($kriterias as $kriteria) {
            $allValues = collect($dataAgregasi)->pluck($kriteria->id)->filter(fn($v) => $v !== null);

            $minMaxValues[$kriteria->id] = $allValues->isNotEmpty()
                ? ['min' => $allValues->min(), 'max' => $allValues->max()]
                : ['min' => null, 'max' => null];
        }
        $calculationData['min_max_values'] = $minMaxValues;

        // Tahap 3: Normalisasi
        $dataNormalisasi = [];
        foreach ($siswas as $siswa) {
            $dataNormalisasi[$siswa->nisn] = [];

            foreach ($kriterias as $kriteria) {
                $nilai = $dataAgregasi[$siswa->nisn][$kriteria->id] ?? null;
                $min = $minMaxValues[$kriteria->id]['min'];
                $max = $minMaxValues[$kriteria->id]['max'];

                $utility = 0;

                if ($nilai !== null && $min !== null && $max !== null) {
                    if ($max - $min != 0) {
                        $utility = $kriteria->jenis === 'benefit'
                            ? ($nilai - $min) / ($max - $min)
                            : ($max - $nilai) / ($max - $min);
                    } else {
                        $utility = 1;
                    }
                }

                $dataNormalisasi[$siswa->nisn][$kriteria->id] = $utility;
            }
        }
        $calculationData['data_normalisasi'] = $dataNormalisasi;

        // Tahap 4: Terbobot
        $dataTerbobot = [];
        foreach ($siswas as $siswa) {
            $dataTerbobot[$siswa->nisn] = [];
            foreach ($kriterias as $kriteria) {
                $utility = $dataNormalisasi[$siswa->nisn][$kriteria->id] ?? 0;
                $bobot = $bobotNormal[$kriteria->id] ?? 0;
                $dataTerbobot[$siswa->nisn][$kriteria->id] = $utility * $bobot;
            }
        }
        $calculationData['data_terbobot'] = $dataTerbobot;

        // Tahap 5: Nilai Akhir
        $hasilAkhirPerSiswa = [];
        foreach ($siswas as $siswa) {
            $hasilAkhirPerSiswa[$siswa->nisn] = array_sum($dataTerbobot[$siswa->nisn]);
        }
        $calculationData['hasil_akhir_mentah'] = $hasilAkhirPerSiswa;

        // Tahap 6: Simpan ke Database dan Ranking
        $hasilFinalDenganRanking = [];
        if (!$calculationData['has_errors']) {
            DB::beginTransaction();
            try {
                Hasil::query()->delete();

                $dataToInsert = [];
                foreach ($hasilAkhirPerSiswa as $nisn => $nilai) {
                    $dataToInsert[] = [
                        'siswa_nisn' => $nisn,
                        'nilai_total_smart' => $nilai,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                if (!empty($dataToInsert)) {
                    Hasil::insert($dataToInsert);
                }

                $results = Hasil::with('siswa')->orderByDesc('nilai_total_smart')->get();
                $rank = 1;
                foreach ($results as $result) {
                    $result->ranking = $rank++;
                    $result->save();

                    $hasilFinalDenganRanking[$result->siswa_nisn] = [
                        'nisn' => $result->siswa_nisn,
                        'nama' => $result->siswa->nama ?? 'N/A',
                        'kelas' => $result->siswa->kelas ?? 'N/A',
                        'nilai_total_smart' => $result->nilai_total_smart,
                        'ranking' => $result->ranking,
                    ];
                }

                DB::commit();
                $calculationData['success_message'] = 'Perhitungan SMART berhasil dilakukan dan hasil disimpan.';

            } catch (\Exception $e) {
                DB::rollBack();
                $calculationData['has_errors'] = true;
                $calculationData['error_messages'][] = 'Gagal menyimpan hasil perhitungan: ' . $e->getMessage();
                $hasilFinalDenganRanking = [];
            }
        }

        $calculationData['hasil_final_ranking'] = $hasilFinalDenganRanking;
        $calculationData['siswas'] = $siswas->keyBy('nisn');
        $calculationData['kriterias'] = $kriterias->keyBy('id');

        return view('perhitungan.index', compact('calculationData'));
    }
}
