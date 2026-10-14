<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpsiJawaban extends Model
{
    use HasFactory;

    protected $table = 'opsi_jawaban';

    protected $fillable = [
        'soal_id',
        'label',
        'teks_opsi',
        'gambar_opsi',
        'is_benar',
    ];

    protected $casts = [
        'is_benar' => 'boolean',
    ];

    public function soal()
    {
        return $this->belongsTo(SoalUjian::class, 'soal_id');
    }
}
