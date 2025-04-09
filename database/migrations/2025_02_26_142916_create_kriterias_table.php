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
        Schema::create('kriterias', function (Blueprint $table) {
            $table->id(); // Primary key auto-increment standar
            $table->string('kode_kriteria')->unique()->nullable(); // Tambahan: Kode unik (C1, C2, etc.), boleh null jika tidak dipakai
            $table->string('nama', 255); // Nama kriteria (Nilai Raport, Presensi, etc.)
            $table->enum('jenis', ['benefit', 'cost']);
            $table->float('bobot');
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kriterias');
    }
};
