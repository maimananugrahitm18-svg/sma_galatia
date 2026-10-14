<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalUjian extends Model
{
    use HasFactory;

    protected $table = 'soal_ujian';

    protected $fillable = [
        'ujian_id',
        'tipe_soal',
        'pertanyaan',
        'gambar',
        'bobot_nilai',
        'urutan',
    ];

    protected $casts = [
        'bobot_nilai' => 'decimal:2',
        'urutan' => 'integer',
    ];

    public function ujian()
    {
        return $this->belongsTo(Ujian::class, 'ujian_id');
    }

    public function opsi()
    {
        return $this->hasMany(OpsiJawaban::class, 'soal_id')->orderBy('label');
    }

    public function jawabanSiswa()
    {
        return $this->hasMany(SiswaJawaban::class, 'soal_id');
    }
}
