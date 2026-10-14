<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi';

    protected $fillable = [
        'tanggal',
        'kelas_id',
        'siswa_id',
        'jadwal_id',
        'guru_id',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function siswa()
    {
        return $this->belongsTo(SiswaProfile::class, 'siswa_id');
    }

    public function jadwal()
    {
        return $this->belongsTo(JadwalPelajaran::class, 'jadwal_id');
    }

    public function guru()
    {
        return $this->belongsTo(GuruProfile::class, 'guru_id');
    }
}
