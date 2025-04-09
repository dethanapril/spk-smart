<?php

namespace App\Http\Controllers;

use App\Models\Hasil;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HasilController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'periode' => 'nullable|string',
        ]);

        $today = Carbon::today();
        $year = $today->year;

        $periode = $request->input('periode', $year);

        $query = Hasil::with('siswa')->orderBy('nilai_akhir', 'desc');

        if ($periode) {
            $query->where('periode', $periode);
        }

        $hasil = $query->take(10)->get();

        return view('hasil.index', compact('hasil', 'periode'));
    }

    public function generatePDF(Request $request)
    {
        $periode = $request->input('periode');

        // Tetapkan nilai default jika tidak ada input
        if (!$periode) {
            $today = Carbon::today();
            $year = $today->year;
            $periode = $year;
        }

        $query = Hasil::with('siswa')->orderBy('nilai_akhir', 'desc');

        if ($periode) {
            $query->where('periode', $periode);
        }

        $hasil = $query->take(10)->get();

        $pdf = FacadePdf::loadView('hasil.pdf', compact('hasil', 'periode'));
        return $pdf->download('hasil-akhir.pdf');
    }
}
