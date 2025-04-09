<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penilaians', function (Blueprint $table) {
            $table->id(); // Primary key untuk tabel penilaian

            // Foreign key ke tabel siswas, merujuk ke kolom 'nisn'
            $table->string('siswa_nisn', 12); // Tipe data dan panjang harus sama dengan kolom nisn di tabel siswas
            $table->foreign('siswa_nisn')
                  ->references('nisn') // Merujuk ke kolom 'nisn'
                  ->on('siswas')       // Di tabel 'siswas'
                  ->onDelete('cascade') // Jika siswa dihapus, penilaiannya ikut terhapus
                  ->onUpdate('cascade'); // Jika nisn siswa berubah (jarang terjadi), update di sini

            // Foreign key ke tabel kriterias, merujuk ke kolom 'id'
            $table->foreignId('kriteria_id')
                  ->constrained('kriterias') // Merujuk ke kolom 'id' di tabel 'kriterias'
                  ->onDelete('cascade') // Jika kriteria dihapus, penilaiannya ikut terhapus
                  ->onUpdate('cascade');

            $table->integer('semester')->comment('Semester ke- (1 s/d 5)');
            $table->float('nilai')->comment('Nilai mentah untuk kriteria ini di semester ini');
            $table->timestamps(); // Kolom created_at dan updated_at

            // Mencegah duplikasi data untuk siswa, kriteria, dan semester yang sama
            $table->unique(['siswa_nisn', 'kriteria_id', 'semester']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaians');
    }
};
