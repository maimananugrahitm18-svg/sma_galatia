<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $table = 'mata_pelajaran';

    protected $fillable = [
        'kode_mapel',
        'nama_mapel',
        'kategori',
    ];

    public function jadwalPelajaran()
    {
        return $this->hasMany(JadwalPelajaran::class, 'mata_pelajaran_id');
    }

    public function ujian()
    {
        return $this->hasMany(Ujian::class, 'mata_pelajaran_id');
    }
}
