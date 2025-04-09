<?php

namespace App\Models;

use App\Models\Hasil;
use App\Models\Penilaian;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Siswa extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model.
     *
     * @var string
     */
    protected $table = 'siswas';

    /**
     * Primary key untuk model ini.
     *
     * @var string
     */
    protected $primaryKey = 'nisn';

    /**
     * Tipe data dari primary key.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Menunjukkan jika ID auto-incrementing.
     * Karena NISN bukan auto-increment, set ke false.
     * @var bool
     */
    public $incrementing = false;

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nisn',
        'nama',
        'kelas',
        'jeniskelamin',
        'alamat',
    ];

    /**
     * Relasi ke Penilaian: Satu siswa memiliki banyak data penilaian.
     * Kita perlu menentukan foreign key ('siswa_nisn') dan local key ('nisn').
     */
    public function penilaians(): HasMany
    {
        return $this->hasMany(Penilaian::class, 'siswa_nisn', 'nisn');
    }

    /**
     * Relasi ke Hasil: Satu siswa memiliki satu hasil akhir SMART.
     * Asumsi model Hasil ada dan foreign key di tabel hasils adalah 'siswa_nisn'.
     * Jika Anda masih menggunakan model Hasil dari contoh sebelumnya,
     * pastikan migrasi dan model Hasil juga diupdate.
     */
    public function hasil(): HasOne
    {
        // Pastikan foreign key di tabel 'hasils' adalah 'siswa_nisn'
        // dan local key di tabel 'siswas' adalah 'nisn'
        return $this->hasOne(Hasil::class, 'siswa_nisn', 'nisn');
    }
}
