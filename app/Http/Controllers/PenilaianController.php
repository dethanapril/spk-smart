<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $siswas = Siswa::orderBy('nama')->paginate(15); // Contoh pagination

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
        // Ambil semua kriteria
        $kriterias = Kriteria::orderBy('id')->get(); // Urutkan berdasarkan ID atau kode

        // Ambil semua data penilaian yang SUDAH ADA untuk siswa ini
        $existingPenilaian = Penilaian::where('siswa_nisn', $siswa->nisn)->get();

        // Buat map nilai agar mudah diakses di view: [kriteria_id][semester] => nilai
        $nilaiMap = [];
        foreach ($existingPenilaian as $penilaian) {
            $nilaiMap[$penilaian->kriteria_id][$penilaian->semester] = $penilaian->nilai;
        }

        // Jumlah semester yang akan ditampilkan (misal 1-5)
        $semesters = range(1, 5);

        return view('penilaian.edit', compact('siswa', 'kriterias', 'nilaiMap', 'semesters'));
    }

    /**
     * Memperbarui atau membuat data penilaian untuk seorang siswa.
     *
     * @param Request $request
     * @param Siswa $siswa Model Siswa yang dibinding dari route {siswa:nisn}
     */
    public function update(Request $request, Siswa $siswa): RedirectResponse
    {
        // Ambil semua input nilai dari form (berupa array)
        $inputNilai = $request->input('nilai', []);
        $kriterias = Kriteria::all(); // Ambil semua kriteria untuk iterasi

        // Validasi (Contoh sederhana, sesuaikan kebutuhan)
        $validatorRules = [];
        foreach ($kriterias as $kriteria) {
            for ($semester = 1; $semester <= 5; $semester++) {
                // Membuat aturan validasi untuk setiap input nilai
                // 'nullable' agar boleh kosong, 'numeric' harus angka
                // Tambahkan aturan lain jika perlu (min, max, etc.)
                $validatorRules["nilai.{$kriteria->id}.{$semester}"] = 'nullable|numeric|min:0'; // Contoh: min 0
            }
        }

        $request->validate($validatorRules, [
            'nilai.*.*.numeric' => 'Nilai harus berupa angka.',
            'nilai.*.*.min' => 'Nilai tidak boleh negatif.',
        ]);


        // Gunakan database transaction untuk memastikan semua data disimpan atau tidak sama sekali
        DB::beginTransaction();
        try {
            // Iterasi melalui semua kriteria dan semester
            foreach ($kriterias as $kriteria) {
                 for ($semester = 1; $semester <= 5; $semester++) {
                    // Ambil nilai dari input, default ke null jika tidak ada atau kosong
                    $nilaiInput = $inputNilai[$kriteria->id][$semester] ?? null;

                     // Hanya proses jika nilai tidak null (atau sesuai aturan bisnis Anda)
                     // Jika input kosong dianggap 0, Anda bisa set $nilaiInput = $nilaiInput ?? 0;
                    if ($nilaiInput !== null && $nilaiInput !== '') {
                         Penilaian::updateOrCreate(
                            [
                                // Kunci untuk mencari record yang ada
                                'siswa_nisn' => $siswa->nisn,
                                'kriteria_id' => $kriteria->id,
                                'semester' => $semester,
                            ],
                            [
                                // Nilai yang akan diupdate atau dibuat
                                'nilai' => (float) $nilaiInput, // Pastikan tipe datanya float/double
                            ]
                        );
                    } else {
                         // Opsional: Jika input kosong, apakah record penilaian yang ada harus dihapus?
                         // Penilaian::where('siswa_nisn', $siswa->nisn)
                         //        ->where('kriteria_id', $kriteria->id)
                         //        ->where('semester', $semester)
                         //        ->delete();
                    }
                }
            }

            DB::commit(); // Simpan semua perubahan jika tidak ada error

            return redirect()->route('penilaian.edit', $siswa->nisn)
                         ->with('success', 'Data penilaian untuk ' . $siswa->nama . ' berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua perubahan jika terjadi error

            // Log error $e->getMessage();
            return redirect()->route('penilaian.edit', $siswa->nisn)
                         ->with('error', 'Terjadi kesalahan saat menyimpan data penilaian: ' . $e->getMessage());
        }
    }
}