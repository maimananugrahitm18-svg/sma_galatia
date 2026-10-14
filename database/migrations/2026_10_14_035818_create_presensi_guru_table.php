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
        Schema::create('presensi_guru', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('guru_id')->constrained('guru_profiles')->onDelete('cascade');
            $table->enum('status', ['hadir', 'sakit', 'izin', 'alfa'])->default('hadir');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['tanggal', 'guru_id'], 'presensi_guru_unique_tanggal_guru');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_guru');
    }
};
