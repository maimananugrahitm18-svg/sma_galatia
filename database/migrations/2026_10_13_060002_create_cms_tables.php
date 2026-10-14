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
        // 1. Berita & Kegiatan Sekolah
        Schema::create('berita', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->string('judul');
            $table->string('slug')->unique();
            $table->string('thumbnail')->nullable();
            $table->string('kategori')->default('Berita'); // Berita, Pengumuman, Prestasi, Ekstrakurikuler
            $table->longText('konten');
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();
        });

        // 2. Galeri Foto & Kegiatan
        Schema::create('galeri', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('foto');
            $table->string('kategori')->default('Kegiatan'); // Fasilitas, Kegiatan, Ekstrakurikuler, Prestasi
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galeri');
        Schema::dropIfExists('berita');
    }
};
