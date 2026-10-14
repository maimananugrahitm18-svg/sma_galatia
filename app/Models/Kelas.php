<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'tingkat',
        'wali_guru_id',
    ];

    public function waliKelas()
    {
        return $this->belongsTo(GuruProfile::class, 'wali_guru_id');
    }

    public function siswa()
    {
        return $this->hasMany(SiswaProfile::class, 'kelas_id');
    }

    public function jadwalPelajaran()
    {
        return $this->hasMany(JadwalPelajaran::class, 'kelas_id');
    }

    public function ujian()
    {
        return $this->belongsToMany(Ujian::class, 'ujian_kelas', 'kelas_id', 'ujian_id');
    }
}
