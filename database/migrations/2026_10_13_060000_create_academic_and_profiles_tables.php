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
        // 1. Tahun Ajaran
        Schema::create('tahun_ajaran', function (Blueprint $table) {
            $table->id();
            $table->string('tahun'); // contoh: '2026/2027'
            $table->enum('semester', ['ganjil', 'genap'])->default('ganjil');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Guru Profiles
        Schema::create('guru_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nip')->nullable()->unique();
            $table->string('gelar_depan')->nullable();
            $table->string('gelar_belakang')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->default('L');
            $table->string('no_hp')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });

        // 3. Kelas
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas'); // contoh: 'X-MIPA-1'
            $table->enum('tingkat', ['10', '11', '12'])->default('10');
            $table->foreignId('wali_guru_id')->nullable()->constrained('guru_profiles')->nullOnDelete();
            $table->timestamps();
        });

        // 4. Siswa Profiles
        Schema::create('siswa_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nis')->nullable()->unique();
            $table->string('nisn')->nullable()->unique();
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->nullOnDelete();
            $table->enum('jenis_kelamin', ['L', 'P'])->default('L');
            $table->string('no_hp')->nullable();
            $table->year('tahun_masuk')->nullable();
            $table->timestamps();
        });

        // 5. Mata Pelajaran
        Schema::create('mata_pelajaran', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mapel')->unique();
            $table->string('nama_mapel');
            $table->enum('kategori', ['umum', 'peminatan', 'muatan_lokal'])->default('umum');
            $table->timestamps();
        });

        // 6. Jadwal Pelajaran (Jadwal Mengajar Guru)
        Schema::create('jadwal_pelajaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran')->onDelete('cascade');
            $table->foreignId('guru_id')->constrained('guru_profiles')->onDelete('cascade');
            $table->enum('hari', ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu']);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('ruangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_pelajaran');
        Schema::dropIfExists('mata_pelajaran');
        Schema::dropIfExists('siswa_profiles');
        Schema::dropIfExists('kelas');
        Schema::dropIfExists('guru_profiles');
        Schema::dropIfExists('tahun_ajaran');
    }
};
