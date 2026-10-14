<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiswaJawaban extends Model
{
    use HasFactory;

    protected $table = 'siswa_jawaban';

    protected $fillable = [
        'siswa_ujian_id',
        'soal_id',
        'opsi_jawaban_id',
        'jawaban_esai',
        'is_ragu',
        'nilai_didapat',
    ];

    protected $casts = [
        'is_ragu' => 'boolean',
        'nilai_didapat' => 'decimal:2',
    ];

    public function siswaUjian()
    {
        return $this->belongsTo(SiswaUjian::class, 'siswa_ujian_id');
    }

    public function soal()
    {
        return $this->belongsTo(SoalUjian::class, 'soal_id');
    }

    public function opsiTerpilih()
    {
        return $this->belongsTo(OpsiJawaban::class, 'opsi_jawaban_id');
    }
}
