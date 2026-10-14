<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswa_ujian', function (Blueprint $table) {
            // Simpan urutan ID soal yang sudah diacak dalam bentuk JSON
            // agar setiap refresh siswa tetap mendapat urutan soal yang sama
            $table->json('urutan_soal_ids')->nullable()->after('total_nilai');
        });
    }

    public function down(): void
    {
        Schema::table('siswa_ujian', function (Blueprint $table) {
            $table->dropColumn('urutan_soal_ids');
        });
    }
};
