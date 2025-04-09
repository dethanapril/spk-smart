<?php

namespace App\Models;

use App\Models\Siswa;
use App\Models\Kriteria;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penilaian extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model.
     *
     * @var string
     */
    protected $table = 'penilaians';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'siswa_nisn', // Sesuaikan dengan nama kolom di migrasi
        'kriteria_id',
        'semester',
        'nilai',
    ];

    /**
     * Relasi ke Siswa: Penilaian ini milik satu siswa.
     * Kita perlu menentukan foreign key ('siswa_nisn') dan owner key ('nisn').
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_nisn', 'nisn');
    }

    /**
     * Relasi ke Kriteria: Penilaian ini untuk satu kriteria.
     * Menggunakan foreign key default 'kriteria_id'.
     */
    public function kriteria(): BelongsTo
    {
        return $this->belongsTo(Kriteria::class); // Laravel akan mengasumsikan foreign key 'kriteria_id'
    }
}
