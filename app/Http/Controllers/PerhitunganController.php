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
    /**
     * Melakukan perhitungan SMART langkah demi langkah dan menampilkan hasilnya.
     */
    public function index(): View
    {
        // --- Tahap 0: Persiapan & Validasi Data Awal ---
        $siswas = Siswa::with('penilaians')->get();
        $kriterias = Kriteria::all();
        $semesters = range(1, 5); // Semester yang dievaluasi
        $jumlahSemester = count($semesters);

        $calculationData = []; // Array untuk menyimpan semua hasil per langkah
        $calculationData['has_errors'] = false;
        $calculationData['error_messages'] = [];

        // Validasi dasar: Apakah ada data siswa dan kriteria?
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

        // Validasi & Normalisasi Bobot Kriteria
        $totalBobot = $kriterias->sum('bobot');
        $bobotNormal = [];
        foreach ($kriterias as $kriteria) {
            $bobotNormal[$kriteria->id] = $kriteria->bobot / $totalBobot;
        }
        
        $calculationData['bobot_kriteria'] = $kriterias->mapWithKeys(function ($item) use ($bobotNormal) {
            return [$item->id => ['nama' => $item->nama, 'bobot_awal' => $item->bobot, 'bobot_normal' => $bobotNormal[$item->id]]];
        })->all();

        // --- Tahap 1: Agregasi Nilai per Siswa per Kriteria ---
        $dataAgregasi = [];
        foreach ($siswas as $siswa) {
            $dataAgregasi[$siswa->nisn] = [];
            $penilaianSiswa = $siswa->penilaians->keyBy(function($item) {
                // Buat key unik untuk akses cepat: 'kriteriaId_semester'
                return $item->kriteria_id . '_' . $item->semester;
            });

            foreach ($kriterias as $kriteria) {
                $totalNilaiSemester = 0;
                $nilaiDitemukan = false;
                for ($semester = 1; $semester <= $jumlahSemester; $semester++) {
                    $key = $kriteria->id . '_' . $semester;
                    if (isset($penilaianSiswa[$key])) {
                        $totalNilaiSemester += $penilaianSiswa[$key]->nilai;
                        $nilaiDitemukan = true;
                    }
                }
                // Simpan hanya jika ada nilai di salah satu semester (atau sesuaikan aturan)
                // Jika ingin default 0 jika tidak ada nilai sama sekali:
                // $dataAgregasi[$siswa->nisn][$kriteria->id] = $totalNilaiSemester;
                // Jika ingin null jika tidak ada nilai:
                 $dataAgregasi[$siswa->nisn][$kriteria->id] = $nilaiDitemukan ? $totalNilaiSemester : null;

                 // Opsional: Cek kelengkapan data per siswa per kriteria
                 if (!$nilaiDitemukan) {
                     $calculationData['warning_messages'][] = "Siswa {$siswa->nama} ({$siswa->nisn}) tidak memiliki nilai untuk kriteria '{$kriteria->nama}' di semua semester.";
                 }
            }
        }
        $calculationData['data_agregasi'] = $dataAgregasi;

        // --- Tahap 2: Menghitung Nilai Min/Max per Kriteria (dari data agregasi) ---
        $minMaxValues = [];
        foreach ($kriterias as $kriteria) {
            // Ambil semua nilai agregat untuk kriteria ini, abaikan null
            $allValuesForKriteria = collect($dataAgregasi)->pluck($kriteria->id)->filter(function ($value) {
                return $value !== null;
            });

            if ($allValuesForKriteria->isNotEmpty()) {
                $minMaxValues[$kriteria->id] = [
                    'min' => $allValuesForKriteria->min(),
                    'max' => $allValuesForKriteria->max(),
                ];
            } else {
                 // Tidak ada nilai agregat untuk kriteria ini
                $minMaxValues[$kriteria->id] = ['min' => null, 'max' => null];
                $calculationData['warning_messages'][] = "Tidak ada nilai agregat yang valid ditemukan untuk kriteria '{$kriteria->nama}'. Kriteria ini mungkin tidak dapat dinormalisasi.";
            }
        }
        $calculationData['min_max_values'] = $minMaxValues;

        // --- Tahap 3: Normalisasi Nilai (Utility Function) ---
        $dataNormalisasi = [];
        foreach ($siswas as $siswa) {
            $dataNormalisasi[$siswa->nisn] = [];
            foreach ($kriterias as $kriteria) {
                $nilaiSiswa = $dataAgregasi[$siswa->nisn][$kriteria->id] ?? null;
                $min = $minMaxValues[$kriteria->id]['min'] ?? null;
                $max = $minMaxValues[$kriteria->id]['max'] ?? null;
                $utility = 0; // Default jika tidak bisa dihitung

                if ($nilaiSiswa !== null && $min !== null && $max !== null) {
                    // Hindari pembagian dengan nol jika semua nilai sama
                    if (($max - $min) != 0) {
                        if ($kriteria->jenis == 'benefit') {
                            $utility = ($nilaiSiswa - $min) / ($max - $min);
                        } elseif ($kriteria->jenis == 'cost') {
                            $utility = ($max - $nilaiSiswa) / ($max - $min);
                        }
                    } else {
                        // Jika max == min dan nilaiSiswa ada, berarti semua nilai sama, utility bisa dianggap 1
                        $utility = 1;
                    }
                } else {
                    // Jika nilai siswa null atau min/max null, utility = 0
                     $utility = 0;
                     if ($nilaiSiswa === null && ($min !== null || $max !== null)) {
                         // Beri peringatan jika siswa tak punya nilai tapi kriteria ada min/max nya
                         // $calculationData['warning_messages'][] = "Siswa {$siswa->nama} tidak dapat dinormalisasi untuk kriteria '{$kriteria->nama}' karena tidak memiliki nilai agregat.";
                     }
                }
                // Hasil utility antara 0 dan 1
                $dataNormalisasi[$siswa->nisn][$kriteria->id] = $utility;
            }
        }
        $calculationData['data_normalisasi'] = $dataNormalisasi;

        // --- Tahap 4: Perhitungan Nilai Terbobot ---
        $dataTerbobot = [];
         foreach ($siswas as $siswa) {
            $dataTerbobot[$siswa->nisn] = [];
            foreach ($kriterias as $kriteria) {
                $utilityScore = $dataNormalisasi[$siswa->nisn][$kriteria->id] ?? 0;
                $bobot = $bobotNormal[$kriteria->id] ?? 0;
                $weightedScore = $utilityScore * $bobot;
                $dataTerbobot[$siswa->nisn][$kriteria->id] = $weightedScore;
            }
        }
        $calculationData['data_terbobot'] = $dataTerbobot;

        // --- Tahap 5: Perhitungan Nilai Akhir SMART ---
        $hasilAkhirPerSiswa = [];
         foreach ($siswas as $siswa) {
            $totalNilaiSmart = 0;
            foreach ($kriterias as $kriteria) {
                $totalNilaiSmart += $dataTerbobot[$siswa->nisn][$kriteria->id] ?? 0;
            }
            $hasilAkhirPerSiswa[$siswa->nisn] = $totalNilaiSmart;
        }
        // Simpan hasil akhir mentah sebelum diranking
        $calculationData['hasil_akhir_mentah'] = $hasilAkhirPerSiswa;


        // --- Tahap 6: Simpan Hasil ke Database & Lakukan Ranking ---
        $hasilFinalDenganRanking = []; // Untuk dikirim ke view setelah disimpan
        if (!$calculationData['has_errors']) { // Hanya simpan jika tidak ada error fatal
            DB::beginTransaction();
            try {
                // Hapus hasil lama
                Hasil::query()->delete();

                $dataToInsert = [];
                foreach ($hasilAkhirPerSiswa as $siswaNisn => $nilaiTotal) {
                    $dataToInsert[] = [
                        'siswa_nisn' => $siswaNisn,
                        'nilai_total_smart' => $nilaiTotal,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                // Insert semua hasil
                if(!empty($dataToInsert)) {
                    Hasil::insert($dataToInsert);
                }

                // Lakukan Ranking berdasarkan data yang baru disimpan
                $results = Hasil::with('siswa') // Eager load data siswa
                                ->orderBy('nilai_total_smart', 'desc')
                                ->get();
                $rank = 1;
                foreach ($results as $result) {
                    $result->ranking = $rank++;
                    $result->save(); // Simpan ranking

                    // Siapkan data untuk view
                    $hasilFinalDenganRanking[$result->siswa_nisn] = [
                        'nisn' => $result->siswa_nisn,
                        'nama' => $result->siswa->nama ?? 'N/A', // Ambil dari relasi
                        'kelas' => $result->siswa->kelas ?? 'N/A',
                        'nilai_total_smart' => $result->nilai_total_smart,
                        'ranking' => $result->ranking,
                    ];
                }

                DB::commit(); // Simpan perubahan jika semua berhasil
                $calculationData['success_message'] = 'Perhitungan SMART berhasil dilakukan dan hasil disimpan.';

            } catch (\Exception $e) {
                DB::rollBack(); // Batalkan jika ada error
                $calculationData['has_errors'] = true;
                $calculationData['error_messages'][] = 'Gagal menyimpan hasil perhitungan ke database: ' . $e->getMessage();
                // Kosongkan hasil ranking jika gagal simpan
                $hasilFinalDenganRanking = [];
                 // Log error $e->getMessage();
            }
        } else {
            $calculationData['error_messages'][] = 'Perhitungan dibatalkan atau hasil tidak disimpan karena ada error sebelumnya.';
        }
        $calculationData['hasil_final_ranking'] = $hasilFinalDenganRanking;

        // --- Tahap 7: Kirim semua data ke View ---
        // Gabungkan data siswa dan kriteria ke calculationData agar lebih rapi
        $calculationData['siswas'] = $siswas->keyBy('nisn'); // Key by NISN for easy access in view
        $calculationData['kriterias'] = $kriterias->keyBy('id'); // Key by ID

        return view('perhitungan.index', compact('calculationData'));
    }
}