<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    use HasFactory;

    protected $table = 'penilaians';

    protected $fillable = [
        'nilai',
        'nisn',
        'kriteria_id',
        'periode',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nisn', 'nisn');
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id', 'id');
    }
}
