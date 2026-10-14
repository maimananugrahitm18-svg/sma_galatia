<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    use HasFactory;

    protected $table = 'ujian';

    protected $fillable = [
        'guru_id',
        'mata_pelajaran_id',
        'judul',
        'deskripsi',
        'tipe_ujian',
        'durasi_menit',
        'waktu_mulai',
        'waktu_selesai',
        'acak_soal',
        'acak_opsi',
        'tampilkan_nilai',
        'status',
    ];

    protected $casts = [
        'durasi_menit' => 'integer',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'acak_soal' => 'boolean',
        'acak_opsi' => 'boolean',
        'tampilkan_nilai' => 'boolean',
    ];

    public function guru()
    {
        return $this->belongsTo(GuruProfile::class, 'guru_id');
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id');
    }

    public function kelas()
    {
        return $this->belongsToMany(Kelas::class, 'ujian_kelas', 'ujian_id', 'kelas_id');
    }

    public function soal()
    {
        return $this->hasMany(SoalUjian::class, 'ujian_id')->orderBy('urutan');
    }

    public function pesertaUjian()
    {
        return $this->hasMany(SiswaUjian::class, 'ujian_id');
    }
}
