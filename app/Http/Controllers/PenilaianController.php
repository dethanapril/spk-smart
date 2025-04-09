<?php

namespace App\Http\Controllers;

use App\Models\Penilaian;
use App\Models\Siswa;
use App\Models\Kriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PenilaianController extends Controller
{
    /**
     * Tampilkan daftar penilaian siswa.
     */
    public function index(Request $request)
    {
        $periode = $this->getCurrentPeriod();

        // Gunakan scope query yang sama untuk filter dan default
        $query = $this->buildBaseQuery()
            ->where('n.periode', $periode);

        $data = $query->get();

        return view('penilaian.index', compact('data', 'periode'));
    }

    public function filterPeriode(Request $request)
    {
        $validated = $request->validate([
            'periode' => ['required']
        ]);

        $query = $this->buildBaseQuery()
            ->where('n.periode', $validated['periode']);

        $periode = $validated['periode'];
        $data = $query->get();

        return view('penilaian.index', compact('data', 'periode'));
    }

    private function buildBaseQuery()
    {
        return DB::table('siswas as s')
            ->leftJoin('penilaians as n', 's.nisn', '=', 'n.nisn')
            ->leftJoin('kriterias as k', 'n.kriteria_id', '=', 'k.id')
            ->select(
                's.nisn',
                's.nama',
                'n.periode',
                DB::raw('MAX(CASE WHEN k.nama = "Nilai Raport" THEN n.nilai END) AS Nilai_Raport'),
                DB::raw('MAX(CASE WHEN k.nama = "Presensi" THEN n.nilai END) AS Presensi'),
                DB::raw('MAX(CASE WHEN k.nama = "Prestasi" THEN n.nilai END) AS Prestasi'),
                DB::raw('MAX(CASE WHEN k.nama = "Ekstrakulikuler" THEN n.nilai END) AS Ekstrakulikuler')
            )
            ->groupBy('s.nisn', 's.nama', 'n.periode');
    }

    private function getCurrentPeriod()
    {
        return now()->year;
    }

    /**
     * Form tambah penilaian.
     */
    public function create()
    {
        $siswas = Siswa::all();
        $kriterias = Kriteria::all();
        return view('penilaian.create', compact('siswas', 'kriterias'));
    }

    /**
     * Simpan data penilaian baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nisn' => 'required|exists:siswas,nisn',
            'nilai' => 'required|array',
            'nilai.*' => 'numeric|min:0',
            'periode' => 'required',
        ]);

        foreach ($request->nilai as $kriteria_id => $nilai) {
            Penilaian::updateOrCreate(
                ['nisn' => $request->nisn, 'kriteria_id' => $kriteria_id, 'periode' => $request->periode],
                ['nilai' => $nilai]
            );
        }

        return redirect()->route('penilaians.index')->with('success', 'Penilaian berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail penilaian berdasarkan NISN.
     */
    public function show($nisn)
    {
        $siswa = Siswa::where('nisn', $nisn)->firstOrFail();

        $penilaian = DB::table('penilaians')
            ->join('kriterias', 'penilaians.kriteria_id', '=', 'kriterias.id')
            ->where('penilaians.nisn', $nisn)
            ->select('kriterias.nama as kriteria', 'penilaians.nilai', 'penilaians.periode')
            ->get();
        $periode = $penilaian->isNotEmpty() ? $penilaian->first()->periode : null;

        return view('penilaian.show', compact('siswa', 'penilaian', 'periode'));
    }

    /**
     * Form edit penilaian.
     */
    public function edit($nisn)
    {
        $siswa = Siswa::where('nisn', $nisn)->firstOrFail();
        $kriterias = Kriteria::all();
        $penilaians = Penilaian::where('nisn', $nisn)->get()->keyBy('kriteria_id');
        $periode = $penilaians->isNotEmpty() ? $penilaians->first()->periode : null;
        return view('penilaian.edit', compact('siswa', 'kriterias', 'penilaians', 'periode'));
    }

    /**
     * Update penilaian siswa.
     */
    public function update(Request $request, $nisn)
    {
        $request->validate([
            'nilai' => 'required|array',
            'nilai.*' => 'numeric|min:0',
            'periode' => 'required',
        ]);

        foreach ($request->nilai as $kriteria_id => $nilai) {
            Penilaian::updateOrCreate(
                ['nisn' => $nisn, 'kriteria_id' => $kriteria_id, 'periode' => $request->periode],
                ['nilai' => $nilai]
            );
        }

        return redirect()->route('penilaians.index')->with('success', 'Penilaian berhasil diperbarui.');
    }

    /**
     * Hapus semua penilaian siswa berdasarkan NISN.
     */
    public function destroy($nisn)
    {
        Penilaian::where('nisn', $nisn)->delete();

        return redirect()->route('penilaians.index')->with('success', 'Penilaian berhasil dihapus.');
    }
}
