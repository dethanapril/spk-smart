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
        Schema::create('hasils', function (Blueprint $table) {
            $table->id(); // Primary key untuk tabel hasil itu sendiri

            // Foreign key ke tabel siswas, merujuk ke kolom 'nisn'
            $table->string('siswa_nisn', 12); // Tipe data & panjang sama dengan siswas.nisn
            $table->foreign('siswa_nisn')
                  ->references('nisn') // Merujuk ke kolom 'nisn'
                  ->on('siswas')       // Di tabel 'siswas'
                  ->onDelete('cascade') // Jika siswa dihapus, hasilnya ikut terhapus
                  ->onUpdate('cascade'); // Jika nisn siswa berubah, update di sini

            $table->float('nilai_total_smart')->comment('Nilai akhir hasil perhitungan SMART');
            $table->integer('ranking')->nullable()->comment('Peringkat siswa berdasarkan nilai SMART');
            $table->timestamps(); // Kolom created_at dan updated_at

            // Pastikan setiap siswa hanya punya satu hasil akhir
            $table->unique('siswa_nisn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasils');
    }
};
