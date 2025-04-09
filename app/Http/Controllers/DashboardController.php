<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\Siswa;
use App\Models\Penilaian;
use App\Models\Hasil;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $kriteriaCount = Kriteria::count();
        $alternatifCount = Siswa::count();

        // Menghitung jumlah penilaian yang dikelompokkan berdasarkan siswa
        $penilaianCount = Penilaian::select('nisn')
            ->groupBy('nisn')
            ->get()
            ->count();

        $hasilCount = Hasil::count();

        $query = Hasil::with('siswa')->orderBy('nilai_akhir', 'desc');

        $hasil = $query->get();
        $kriterias = Kriteria::all();

        return view('dashboard', compact('kriteriaCount', 'alternatifCount', 'penilaianCount', 'hasilCount', 'hasil', 'kriterias'));
    }
}
