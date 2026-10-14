<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    use HasFactory;

    protected $table = 'berita';

    protected $fillable = [
        'author_id',
        'judul',
        'slug',
        'thumbnail',
        'kategori',
        'konten',
        'is_published',
        'views',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'views' => 'integer',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
