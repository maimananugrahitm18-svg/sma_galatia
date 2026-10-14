<?php

namespace App\Http\Controllers;

use App\Models\LandingSetting;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function editLanding()
    {
        $settings = LandingSetting::all()->pluck('value', 'key')->toArray();
        return view('superadmin.landing.edit', compact('settings'));
    }

    public function updateLanding(Request $request)
    {
        $inputs = $request->except(['_token']);

        foreach ($inputs as $key => $value) {
            $group = 'general';
            if (str_starts_with($key, 'hero_')) {
                $group = 'hero';
            } elseif (str_starts_with($key, 'profil_') || in_array($key, ['visi', 'misi_1', 'misi_2', 'misi_3'])) {
                $group = 'profil';
            } elseif (str_starts_with($key, 'kepsek_')) {
                $group = 'kepsek';
            } elseif (str_starts_with($key, 'kontak_') || str_starts_with($key, 'footer_')) {
                $group = 'kontak';
            }

            LandingSetting::set($key, $value, $group);
        }

        return back()->with('success', 'Konten landing page berhasil diperbarui oleh Super Admin!');
    }
}
