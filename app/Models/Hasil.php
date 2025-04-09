<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hasil extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model.
     *
     * @var string
     */
    protected $table = 'hasils';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'siswa_nisn', // Sesuaikan dengan nama kolom di migrasi
        'nilai_total_smart',
        'ranking',
    ];

    /**
     * Relasi ke Siswa: Hasil ini milik satu siswa.
     * Kita perlu menentukan foreign key ('siswa_nisn') dan owner key ('nisn').
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_nisn', 'nisn');
    }
}
