<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuruProfile extends Model
{
    use HasFactory;

    protected $table = 'guru_profiles';

    protected $fillable = [
        'user_id',
        'nip',
        'gelar_depan',
        'gelar_belakang',
        'jenis_kelamin',
        'no_hp',
        'foto',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getNamaLengkapAttribute(): string
    {
        $nama = $this->user?->name ?? '';
        $depan = $this->gelar_depan ? "{$this->gelar_depan} " : '';
        $belakang = $this->gelar_belakang ? ", {$this->gelar_belakang}" : '';
        return "{$depan}{$nama}{$belakang}";
    }

    public function waliKelas()
    {
        return $this->hasOne(Kelas::class, 'wali_guru_id');
    }

    public function jadwalPelajaran()
    {
        return $this->hasMany(JadwalPelajaran::class, 'guru_id');
    }

    public function ujian()
    {
        return $this->hasMany(Ujian::class, 'guru_id');
    }

    public function presensi()
    {
        return $this->hasMany(PresensiGuru::class, 'guru_id');
    }
}
