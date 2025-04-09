<?php

namespace App\Http\Controllers;

use App\Models\Hasil;
use App\Models\Kriteria;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PerhitunganController extends Controller
{
    public function index(Request $request)
    {
        // Langkah 1: Persiapan Data
        $periode = $request->query('periode', now()->year);

        $query = DB::table('siswas as s')
            ->leftJoin('penilaians as n', 's.nisn', '=', 'n.nisn')
            ->leftJoin('kriterias as k', 'n.kriteria_id', '=', 'k.id')
            ->select(
                's.nisn',
                's.nama',
                DB::raw('COALESCE(MAX(CASE WHEN k.nama = "Nilai Raport" THEN n.nilai END), NULL) AS Nilai_Raport'),
                DB::raw('COALESCE(MAX(CASE WHEN k.nama = "Presensi" THEN n.nilai END), NULL) AS Presensi'),
                DB::raw('COALESCE(MAX(CASE WHEN k.nama = "Prestasi" THEN n.nilai END), NULL) AS Prestasi'),
                DB::raw('COALESCE(MAX(CASE WHEN k.nama = "Ekstrakulikuler" THEN n.nilai END), NULL) AS Ekstrakulikuler')
            )
            ->when($periode, fn($q) => $q->where('n.periode', $periode))
            ->groupBy('s.nisn', 's.nama');

        $rawData = $query->get();

        // Langkah 2: Normalisasi Bobot Kriteria
        $kriterias = Kriteria::all();
        $totalBobot = $kriterias->sum('bobot');

        $bobotNormalisasi = $kriterias->mapWithKeys(fn($k) => [
            $k->nama => $totalBobot > 0 ? $k->bobot / $totalBobot : 0
        ])->toArray();

        // Langkah 3: Menghitung Nilai Utility
        $maxValues = [
            'Nilai Raport' => $rawData->max('Nilai_Raport'),
            'Presensi' => $rawData->max('Presensi'),
            'Prestasi' => $rawData->max('Prestasi'),
            'Ekstrakulikuler' => $rawData->max('Ekstrakulikuler')
        ];

        $minValues = [
            'Nilai Raport' => $rawData->min('Nilai_Raport'),
            'Presensi' => $rawData->min('Presensi'),
            'Prestasi' => $rawData->min('Prestasi'),
            'Ekstrakulikuler' => $rawData->min('Ekstrakulikuler')
        ];

        $jenisKriteria = $kriterias->pluck('jenis', 'nama');

        // Hitung semua nilai yang diperlukan untuk setiap siswa
        $data = $rawData->map(function ($siswa) use ($maxValues, $minValues, $jenisKriteria, $bobotNormalisasi) {
            // Hitung utility
            $utility = [
                'Nilai Raport' => $this->calculateUtility(
                    $siswa->Nilai_Raport,
                    $minValues['Nilai Raport'],
                    $maxValues['Nilai Raport'],
                    $jenisKriteria['Nilai Raport']
                ),
                'Presensi' => $this->calculateUtility(
                    $siswa->Presensi,
                    $minValues['Presensi'],
                    $maxValues['Presensi'],
                    $jenisKriteria['Presensi']
                ),
                'Prestasi' => $this->calculateUtility(
                    $siswa->Prestasi,
                    $minValues['Prestasi'],
                    $maxValues['Prestasi'],
                    $jenisKriteria['Prestasi']
                ),
                'Ekstrakulikuler' => $this->calculateUtility(
                    $siswa->Ekstrakulikuler,
                    $minValues['Ekstrakulikuler'],
                    $maxValues['Ekstrakulikuler'],
                    $jenisKriteria['Ekstrakulikuler']
                )
            ];

            // Hitung nilai akhir
            $nilai_akhir = round(
                ($utility['Nilai Raport'] * $bobotNormalisasi['Nilai Raport']) +
                    ($utility['Presensi'] * $bobotNormalisasi['Presensi']) +
                    ($utility['Prestasi'] * $bobotNormalisasi['Prestasi']) +
                    ($utility['Ekstrakulikuler'] * $bobotNormalisasi['Ekstrakulikuler']),
                2
            );

            return (object)[
                'nisn' => $siswa->nisn,
                'nama' => $siswa->nama,
                'raw' => $siswa,
                'utility' => $utility,
                'nilai_akhir' => $nilai_akhir
            ];
        });

        // Langkah 4: Perangkingan
        $sortedData = $data->sortByDesc('nilai_akhir')->take(10);

        // Simpan hasil
        $this->simpanHasil($sortedData, $periode);

        return view('perhitungan.index', compact(
            'bobotNormalisasi',
            'kriterias',
            'data',
            'sortedData',
            'periode',
            'maxValues',
            'minValues'
        ));
    }

    private function calculateUtility($value, $c_min, $c_max, $jenis)
    {
        if ($jenis === 'benefit') {
            return ($c_max - $c_min) != 0 ? (($value - $c_min) / ($c_max - $c_min)) : 0;
        }

        if ($jenis === 'cost') {
            return ($c_max - $c_min) != 0 ? (($c_max - $value) / ($c_max - $c_min)) : 0;
        }

        return 0;
    }

    private function simpanHasil($data, $periode)
    {
        foreach ($data as $siswa) {
            Hasil::updateOrCreate(
                ['nisn' => $siswa->nisn, 'periode' => $periode],
                ['nilai_akhir' => $siswa->nilai_akhir]
            );
        }
    }
}
