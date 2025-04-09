<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswas';
    protected $primaryKey = 'nisn';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nisn',
        'nama',
        'jeniskelamin',
        'kelas',
        'alamat',
    ];
}
