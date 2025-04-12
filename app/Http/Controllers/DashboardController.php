<?php

namespace App\Http\Controllers;

use App\Models\Hasil;       // Import model Hasil
use App\Models\Kriteria;   // Import model Kriteria
use App\Models\Penilaian;  // Import model Penilaian
use App\Models\Siswa;      // Import model Siswa
use Illuminate\Http\Request;
use Illuminate\View\View; // Import View

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        // 1. Hitung Jumlah Siswa
        $alternatifCount = Siswa::count();

        // 2. Hitung Jumlah Kriteria
        $kriteriaCount = Kriteria::count();

        // 3. Hitung Jumlah Penilaian
        // $penilaianCount = Penilaian::count();

        // 4. Hitung Jumlah Hasil dan Data Hasil
        // $hasilCount = Hasil::count();
        // $hasil      = Hasil::all();

        // 5. Data Kriteria
        $kriterias = Kriteria::all();

        // Kirim data ke view
        return view('dashboard', [
            'alternatifCount' => $alternatifCount,
            'kriteriaCount' => $kriteriaCount,
            // 'hasilCount' => $hasilCount,
            // 'hasil' => $hasil,
            'kriterias' => $kriterias,
            // 'penilaianCount' => $penilaianCount,
        ]);
    }
}