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
        Schema::create('presensi', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('siswa_profiles')->onDelete('cascade');
            $table->foreignId('jadwal_id')->nullable()->constrained('jadwal_pelajaran')->nullOnDelete();
            $table->foreignId('guru_id')->nullable()->constrained('guru_profiles')->nullOnDelete();
            $table->enum('status', ['hadir', 'sakit', 'izin', 'alfa'])->default('hadir');
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['tanggal', 'siswa_id', 'jadwal_id'], 'presensi_unique_siswa_jadwal_hari');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi');
    }
};
