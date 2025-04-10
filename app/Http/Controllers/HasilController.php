<?php

namespace App\Http\Controllers;

use App\Models\Hasil;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon; // Untuk tanggal

class HasilController extends Controller
{
    /**
     * Menampilkan 10 siswa dengan peringkat teratas.
     */
    public function index(): View
    {
        // Ambil data hasil, eager load relasi siswa
        $top10Hasil = Hasil::with('siswa')
                           ->orderBy('ranking', 'asc') // Urutkan berdasarkan ranking
                           ->take(10)                 // Ambil 10 teratas
                           ->get();

        return view('hasil.index', compact('top10Hasil'));
    }

    /**
     * Menghasilkan laporan PDF berisi hasil perankingan.
     * (Contoh ini mengambil SEMUA hasil yang diranking untuk laporan)
     */
    public function generatePdf()
    {
        // Ambil SEMUA data hasil yang sudah diranking untuk laporan
        $allHasil = Hasil::with('siswa')
                         ->orderBy('ranking', 'asc')
                         ->take(10)
                         ->get();

        // Cek jika tidak ada data hasil
        if ($allHasil->isEmpty()) {
            return redirect()->route('hasil.index')->with('error', 'Tidak ada data hasil untuk dibuat laporan PDF.');
        }

        // Siapkan data tambahan jika perlu (misal tanggal cetak)
        // Menggunakan Carbon untuk format tanggal Indonesia
        $tanggalCetak = Carbon::now('Asia/Makassar')->isoFormat('D MMMM YYYY'); // WITA = Asia/Makassar

        // Data yang akan dikirim ke view PDF
        $data = [
            'allHasil' => $allHasil,
            'tanggalCetak' => $tanggalCetak,
            'judulLaporan' => 'Laporan Hasil Seleksi Siswa Berprestasi Kelas XII' // Judul Laporan
        ];

        // Load view PDF dengan data
        $pdf = Pdf::loadView('hasil.pdf_template', $data);

        // Atur Ukuran Kertas dan Orientasi (Opsional)
        $pdf->setPaper('A4', 'portrait'); // Contoh: Kertas A4, potrait

        // Generate nama file unik atau standar
        $namaFile = 'laporan_hasil_seleksi_' . date('Ymd_His') . '.pdf';

        // Download file PDF
        return $pdf->download($namaFile);

        // return $pdf->stream($namaFile);
    }
}