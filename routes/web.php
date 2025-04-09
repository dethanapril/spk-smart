<?php

use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\PerhitunganController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\HasilController;
use App\Http\Controllers\DashboardController;
use App\Models\Hasil;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\Siswa;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('siswas', SiswaController::class);
    Route::resource('kriterias', KriteriaController::class);
    

    Route::post('/import-siswa', [SiswaController::class, 'import'])->name('siswas.import');

    Route::get('/perhitungan', [PerhitunganController::class, 'index'])->name('perhitungan.index');

});

// Grup route untuk Penilaian (mungkin perlu middleware auth)
Route::prefix('penilaian')->name('penilaian.')->middleware('auth')->group(function () {
    Route::get('/', [PenilaianController::class, 'index'])->name('index');
    Route::get('/{siswa:nisn}/edit', [PenilaianController::class, 'edit'])->name('edit');
    Route::put('/{siswa:nisn}', [PenilaianController::class, 'update'])->name('update');
});
Route::prefix('hasil')->name('hasil.')->middleware('auth')->group(function () {
    Route::get('/', [HasilController::class, 'index'])->name('index');
    Route::get('/pdf', [HasilController::class, 'generatePdf'])->name('pdf');
});

require __DIR__ . '/auth.php';
