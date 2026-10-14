<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Galeri;
use App\Models\GuruProfile;
use App\Models\Kelas;
use App\Models\LandingSetting;
use App\Models\SiswaProfile;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $beritaTerbaru = Berita::where('is_published', true)->latest()->take(3)->get();
        $galeri = Galeri::latest()->take(6)->get();
        $settings = LandingSetting::all()->pluck('value', 'key')->toArray();
        
        $stats = [
            'guru' => GuruProfile::count(),
            'siswa' => SiswaProfile::count(),
            'kelas' => Kelas::count(),
            'akreditasi' => 'A (Unggul)',
        ];

        return view('landing.index', compact('beritaTerbaru', 'galeri', 'stats', 'settings'));
    }

    public function detailBerita($slug)
    {
        $berita = Berita::where('slug', $slug)->firstOrFail();
        $berita->increment('views');
        $beritaLainnya = Berita::where('id', '!=', $berita->id)->latest()->take(4)->get();
        return view('landing.berita-detail', compact('berita', 'beritaLainnya'));
    }
}
