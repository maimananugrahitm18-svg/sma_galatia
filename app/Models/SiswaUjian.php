<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiswaUjian extends Model
{
    use HasFactory;

    protected $table = 'siswa_ujian';

    protected $fillable = [
        'ujian_id',
        'siswa_id',
        'waktu_mulai',
        'waktu_selesai',
        'sisa_waktu_detik',
        'status',
        'total_nilai',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'sisa_waktu_detik' => 'integer',
        'total_nilai' => 'decimal:2',
    ];

    public function ujian()
    {
        return $this->belongsTo(Ujian::class, 'ujian_id');
    }

    public function siswa()
    {
        return $this->belongsTo(SiswaProfile::class, 'siswa_id');
    }

    public function jawaban()
    {
        return $this->hasMany(SiswaJawaban::class, 'siswa_ujian_id');
    }
}
