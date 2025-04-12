<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB; // Untuk transaction
use Illuminate\View\View;

class PenilaianController extends Controller
{
    /**
     * Menampilkan daftar siswa untuk memilih siapa yang akan dinilai.
     */
    public function index(): View
    {
        // Ambil semua siswa, mungkin dengan pagination jika banyak
        $siswas = Siswa::orderBy('nisn')->get(); // Contoh pagination

        return view('penilaian.index', compact('siswas'));
    }

    /**
     * Menampilkan form untuk mengedit/memasukkan nilai semua kriteria
     * untuk seorang siswa tertentu.
     *
     * @param Siswa $siswa Model Siswa yang dibinding dari route {siswa:nisn}
     */
    public function edit(Siswa $siswa): View
    {
        $kriterias = Kriteria::orderBy('id')->get();
        $existingPenilaian = Penilaian::where('siswa_nisn', $siswa->nisn)->get();

        $nilaiMap = [];
        foreach ($existingPenilaian as $penilaian) {
            $nilaiMap[$penilaian->kriteria_id][$penilaian->periode] = $penilaian->nilai;
        }

        // Hitung tahun ajaran dari 3 tahun sebelumnya sampai sekarang
        $tahunSekarang = Carbon::now()->year;
        $jumlahTahun = 3;
        $periodes = [];

        for ($i = $jumlahTahun; $i >= 1; $i--) {
            $tahunAwal = $tahunSekarang - $i;
            $tahunAkhir = $tahunAwal + 1;

            $periodes[] = "$tahunAwal/$tahunAkhir Ganjil";
            $periodes[] = "$tahunAwal/$tahunAkhir Genap";
        }

        return view('penilaian.edit', compact('siswa', 'kriterias', 'nilaiMap', 'periodes'));
    }

    /**
     * Memperbarui atau membuat data penilaian untuk seorang siswa.
     *
     * @param Request $request
     * @param Siswa $siswa Model Siswa yang dibinding dari route {siswa:nisn}
     */
    public function update(Request $request, Siswa $siswa): RedirectResponse
    {
        $inputNilai = $request->input('nilai', []);
        $kriterias = Kriteria::all();

        // Generate daftar periode dinamis
        $tahunSekarang = Carbon::now()->year;
        $jumlahTahun = 3;
        $periodes = [];

        for ($i = $jumlahTahun; $i >= 1; $i--) {
            $tahunAwal = $tahunSekarang - $i;
            $tahunAkhir = $tahunAwal + 1;

            $periodes[] = "$tahunAwal/$tahunAkhir Ganjil";
            $periodes[] = "$tahunAwal/$tahunAkhir Genap";
        }

        // Validasi
        $validatorRules = [];
        foreach ($kriterias as $kriteria) {
            foreach ($periodes as $periode) {
                $validatorRules["nilai.{$kriteria->id}.{$periode}"] = 'nullable|numeric|min:0';
            }
        }

        $request->validate($validatorRules, [
            'nilai.*.*.numeric' => 'Nilai harus berupa angka.',
            'nilai.*.*.min' => 'Nilai tidak boleh negatif.',
        ]);

        DB::beginTransaction();
        try {
            foreach ($kriterias as $kriteria) {
                foreach ($periodes as $periode) {
                    $nilaiInput = $inputNilai[$kriteria->id][$periode] ?? 0;

                    Penilaian::updateOrCreate(
                        [
                            'siswa_nisn' => $siswa->nisn,
                            'kriteria_id' => $kriteria->id,
                            'periode' => $periode,
                        ],
                        [
                            'nilai' => (float) $nilaiInput,
                        ]
                    );
                }
            }

            DB::commit();

            return redirect()->route('penilaian.edit', $siswa->nisn)
                ->with('success', 'Data penilaian untuk ' . $siswa->nama . ' berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('penilaian.edit', $siswa->nisn)
                ->with('error', 'Terjadi kesalahan saat menyimpan data penilaian: ' . $e->getMessage());
        }
    }
}