<?php

namespace App\Models;

use App\Models\Penilaian;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kriteria extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model.
     *
     * @var string
     */
    protected $table = 'kriterias';

     /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_kriteria', // Tambahkan jika Anda menyertakannya di migrasi
        'nama',
        'jenis',
        'bobot',
    ];

    /**
     * Relasi ke Penilaian: Satu kriteria digunakan dalam banyak penilaian.
     * Menggunakan foreign key default 'kriteria_id'.
     */
    public function penilaians(): HasMany
    {
        return $this->hasMany(Penilaian::class); // Laravel akan mengasumsikan foreign key 'kriteria_id'
    }
}
