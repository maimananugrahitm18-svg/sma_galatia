<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiGuru extends Model
{
    use HasFactory;

    protected $table = 'presensi_guru';

    protected $fillable = [
        'tanggal',
        'guru_id',
        'status',
        'jam_masuk',
        'jam_pulang',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function guru()
    {
        return $this->belongsTo(GuruProfile::class, 'guru_id');
    }
}
