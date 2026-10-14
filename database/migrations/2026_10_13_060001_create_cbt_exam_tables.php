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
        // 1. Tabel Ujian / Ulangan Harian
        Schema::create('ujian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('guru_profiles')->onDelete('cascade');
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran')->onDelete('cascade');
            $table->string('judul'); // Contoh: "Ulangan Harian 1 - Vektor"
            $table->text('deskripsi')->nullable();
            $table->enum('tipe_ujian', ['ulangan_harian', 'kuis', 'uts', 'uas'])->default('ulangan_harian');
            $table->unsignedInteger('durasi_menit')->default(60);
            $table->dateTime('waktu_mulai')->nullable();
            $table->dateTime('waktu_selesai')->nullable();
            $table->boolean('acak_soal')->default(true);
            $table->boolean('acak_opsi')->default(true);
            $table->boolean('tampilkan_nilai')->default(true);
            $table->enum('status', ['draft', 'terbit', 'selesai'])->default('draft');
            $table->timestamps();
        });

        // 2. Pivot Ujian dan Kelas (Ujian ini diikuti oleh kelas apa saja)
        Schema::create('ujian_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ujian_id')->constrained('ujian')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->timestamps();
        });

        // 3. Butir Soal Ujian
        Schema::create('soal_ujian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ujian_id')->constrained('ujian')->onDelete('cascade');
            $table->enum('tipe_soal', ['pilihan_ganda', 'esai'])->default('pilihan_ganda');
            $table->longText('pertanyaan');
            $table->string('gambar')->nullable();
            $table->decimal('bobot_nilai', 5, 2)->default(10.00);
            $table->unsignedInteger('urutan')->default(1);
            $table->timestamps();
        });

        // 4. Opsi Pilihan Ganda (A, B, C, D, E)
        Schema::create('opsi_jawaban', function (Blueprint $table) {
            $table->id();
            $table->foreignId('soal_id')->constrained('soal_ujian')->onDelete('cascade');
            $table->char('label', 1); // 'A', 'B', 'C', 'D', 'E'
            $table->text('teks_opsi');
            $table->string('gambar_opsi')->nullable();
            $table->boolean('is_benar')->default(false);
            $table->timestamps();
        });

        // 5. Sesi Ujian Siswa (Mobile CBT session)
        Schema::create('siswa_ujian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ujian_id')->constrained('ujian')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('siswa_profiles')->onDelete('cascade');
            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_selesai')->nullable();
            $table->unsignedInteger('sisa_waktu_detik')->nullable();
            $table->enum('status', ['sedang_mengerjakan', 'selesai', 'dibatalkan'])->default('sedang_mengerjakan');
            $table->decimal('total_nilai', 5, 2)->default(0.00);
            $table->timestamps();
        });

        // 6. Lembar Jawaban Butir Soal Siswa
        Schema::create('siswa_jawaban', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_ujian_id')->constrained('siswa_ujian')->onDelete('cascade');
            $table->foreignId('soal_id')->constrained('soal_ujian')->onDelete('cascade');
            $table->foreignId('opsi_jawaban_id')->nullable()->constrained('opsi_jawaban')->nullOnDelete();
            $table->text('jawaban_esai')->nullable();
            $table->boolean('is_ragu')->default(false);
            $table->decimal('nilai_didapat', 5, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa_jawaban');
        Schema::dropIfExists('siswa_ujian');
        Schema::dropIfExists('opsi_jawaban');
        Schema::dropIfExists('soal_ujian');
        Schema::dropIfExists('ujian_kelas');
        Schema::dropIfExists('ujian');
    }
};
