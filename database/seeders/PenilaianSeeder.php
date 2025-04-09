<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penilaian;
use App\Models\Siswa;
use App\Models\Kriteria;
use Illuminate\Support\Facades\DB;

class PenilaianSeeder extends Seeder
{
    public function run()
    {
        $siswas = Siswa::all();
        $kriterias = Kriteria::all();
        $periode = '2024';

        foreach ($siswas as $siswa) {
            foreach ($kriterias as $kriteria) {
                $nilai = $this->generateRandomNilai($kriteria->nama);
                Penilaian::updateOrCreate(
                    ['nisn' => $siswa->nisn, 'kriteria_id' => $kriteria->id, 'periode' => $periode],
                    ['nilai' => $nilai]
                );
            }
        }
    }

    private function generateRandomNilai($kriteria)
    {
        switch ($kriteria) {
            case 'Nilai Raport':
                return rand(1000, 1500);
            case 'Presensi':
                return rand(0, 10);
            case 'Prestasi':
                return rand(25, 50);
            case 'Ekstrakulikuler':
                return rand(70, 100); 
            default:
                return rand(0);
        }
    }
}
