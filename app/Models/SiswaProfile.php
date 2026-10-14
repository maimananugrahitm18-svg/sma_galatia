<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiswaProfile extends Model
{
    use HasFactory;

    protected $table = 'siswa_profiles';

    protected $fillable = [
        'user_id',
        'nis',
        'nisn',
        'kelas_id',
        'jenis_kelamin',
        'no_hp',
        'tahun_masuk',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function ujianSiswa()
    {
        return $this->hasMany(SiswaUjian::class, 'siswa_id');
    }
}
