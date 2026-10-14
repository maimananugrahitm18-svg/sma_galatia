<?php

namespace Database\Seeders;

use App\Models\LandingSetting;
use Illuminate\Database\Seeder;

class LandingSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Hero Section
            ['key' => 'hero_badge', 'value' => 'Tahun Ajaran 2026/2027 • Akreditasi A Unggul', 'group' => 'hero'],
            ['key' => 'hero_title_1', 'value' => 'Membangun Generasi', 'group' => 'hero'],
            ['key' => 'hero_title_2', 'value' => 'Cerdas & Berkarakter', 'group' => 'hero'],
            ['key' => 'hero_desc', 'value' => 'Selamat datang di website resmi SMA Galatia. Kami berdedikasi menyelenggarakan pendidikan berkualitas tinggi dengan integrasi teknologi modern, sistem pembelajaran digital, dan bimbingan karakter unggul.', 'group' => 'hero'],
            ['key' => 'hero_image', 'value' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=900&q=80', 'group' => 'hero'],
            ['key' => 'hero_tagline_foto', 'value' => 'Pembelajaran Interaktif & Ujian Berbasis Komputer & HP (CBT)', 'group' => 'hero'],

            // Profil & Sambutan
            ['key' => 'profil_subjudul', 'value' => 'Menjadi lembaga pendidikan terdepan yang menghasilkan lulusan berintegritas dan siap bersaing di perguruan tinggi favorit.', 'group' => 'profil'],
            ['key' => 'visi', 'value' => 'Terwujudnya insan pembelajar yang berakhlak mulia, unggul dalam prestasi akademik dan non-akademik, berwawasan global, serta adaptif terhadap perkembangan teknologi informasi.', 'group' => 'profil'],
            ['key' => 'misi_1', 'value' => 'Menanamkan nilai-nilai moral, etika, dan disiplin dalam setiap aktivitas pembelajaran.', 'group' => 'profil'],
            ['key' => 'misi_2', 'value' => 'Mengembangkan kurikulum inovatif dengan pemanfaatan platform Computer-Based Testing (CBT) dan e-learning.', 'group' => 'profil'],
            ['key' => 'misi_3', 'value' => 'Memfasilitasi bakat dan minat siswa melalui kegiatan ekstrakurikuler serta olimpiade sains dan seni.', 'group' => 'profil'],
            
            // Kepala Sekolah
            ['key' => 'kepsek_nama', 'value' => 'Drs. H. Hendra Wijaya, M.Pd', 'group' => 'kepsek'],
            ['key' => 'kepsek_jabatan', 'value' => 'Kepala Sekolah SMA Galatia', 'group' => 'kepsek'],
            ['key' => 'kepsek_sambutan', 'value' => 'Pendidikan bukan sekadar mengisi wadah dengan ilmu, melainkan menyalakan lentera potensi setiap peserta didik. Di SMA Galatia, kami mendampingi putra-putri Anda untuk bertumbuh cerdas secara intelektual, tangguh secara emosional, dan luhur budi pekertinya.', 'group' => 'kepsek'],

            // Kontak & Footer
            ['key' => 'kontak_alamat', 'value' => 'Jl. Pendidikan No. 45, Kompleks Galatia Cendekia, Kota Jakarta Barat', 'group' => 'kontak'],
            ['key' => 'kontak_telepon', 'value' => '(021) 582-9901 / +62 812-3456-7890', 'group' => 'kontak'],
            ['key' => 'kontak_email', 'value' => 'info@galatia.sch.id', 'group' => 'kontak'],
            ['key' => 'kontak_jam_kerja', 'value' => 'Senin - Jumat: 07.00 - 15.30 WIB', 'group' => 'kontak'],
            ['key' => 'footer_deskripsi', 'value' => 'Mencetak generasi cerdas, berkarakter, dan berdaya saing global melalui lingkungan pendidikan yang asri, modern, dan teknologi terdepan.', 'group' => 'kontak'],
        ];

        foreach ($settings as $s) {
            LandingSetting::updateOrCreate(
                ['key' => $s['key']],
                ['value' => $s['value'], 'group' => $s['group']]
            );
        }
    }
}
