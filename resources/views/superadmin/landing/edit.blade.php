@extends('layouts.dashboard')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-8 py-8" x-data="{ activeTab: 'hero' }">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-black bg-amber-500/10 text-amber-600 border border-amber-500/30 mb-2">
                <i class="fa-solid fa-crown text-amber-500"></i>
                <span>Super Admin Exclusive</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Pengaturan Konten Landing Page</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Ubah teks, judul, visi-misi, sambutan kepala sekolah, foto banner, dan informasi kontak website utama.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold border border-slate-200 shadow-2xs transition">
                <i class="fa-solid fa-arrow-up-right-from-square text-indigo-600"></i>
                <span>Lihat Tampilan Web</span>
            </a>
        </div>
    </div>

    <!-- Tab Bar -->
    <div class="flex items-center gap-2 border-b border-slate-200 mb-8 overflow-x-auto pb-1">
        <button type="button" @click="activeTab = 'hero'" 
                :class="activeTab === 'hero' ? 'border-indigo-600 text-indigo-600 font-bold bg-indigo-50/50' : 'border-transparent text-slate-500 hover:text-slate-800 font-medium'"
                class="px-4 py-2.5 text-xs sm:text-sm rounded-t-xl border-b-2 flex items-center gap-2 transition cursor-pointer">
            <i class="fa-solid fa-star text-amber-500"></i>
            <span>Banner Utama (Hero)</span>
        </button>

        <button type="button" @click="activeTab = 'profil'" 
                :class="activeTab === 'profil' ? 'border-indigo-600 text-indigo-600 font-bold bg-indigo-50/50' : 'border-transparent text-slate-500 hover:text-slate-800 font-medium'"
                class="px-4 py-2.5 text-xs sm:text-sm rounded-t-xl border-b-2 flex items-center gap-2 transition cursor-pointer">
            <i class="fa-solid fa-bullseye text-primary-500"></i>
            <span>Profil & Visi Misi</span>
        </button>

        <button type="button" @click="activeTab = 'kepsek'" 
                :class="activeTab === 'kepsek' ? 'border-indigo-600 text-indigo-600 font-bold bg-indigo-50/50' : 'border-transparent text-slate-500 hover:text-slate-800 font-medium'"
                class="px-4 py-2.5 text-xs sm:text-sm rounded-t-xl border-b-2 flex items-center gap-2 transition cursor-pointer">
            <i class="fa-solid fa-user-tie text-emerald-500"></i>
            <span>Sambutan Kepala Sekolah</span>
        </button>

        <button type="button" @click="activeTab = 'kontak'" 
                :class="activeTab === 'kontak' ? 'border-indigo-600 text-indigo-600 font-bold bg-indigo-50/50' : 'border-transparent text-slate-500 hover:text-slate-800 font-medium'"
                class="px-4 py-2.5 text-xs sm:text-sm rounded-t-xl border-b-2 flex items-center gap-2 transition cursor-pointer">
            <i class="fa-solid fa-address-book text-sky-500"></i>
            <span>Kontak & Footer</span>
        </button>
    </div>

    <!-- Main Edit Form -->
    <form method="POST" action="{{ route('superadmin.landing.update') }}" class="space-y-6">
        @csrf

        <!-- TAB 1: HERO SECTION -->
        <div x-show="activeTab === 'hero'" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
            <div class="border-b border-slate-100 pb-4 mb-4">
                <h2 class="text-lg font-extrabold text-slate-900">Bagian Banner Utama (Hero Section)</h2>
                <p class="text-xs text-slate-500 mt-0.5">Teks utama yang pertama kali dilihat oleh pengunjung saat membuka website SMA Galatia.</p>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Badge Teks Kecil</label>
                    <input type="text" name="hero_badge" value="{{ $settings['hero_badge'] ?? 'Tahun Ajaran 2026/2027 • Akreditasi A Unggul' }}" 
                           class="w-full text-sm rounded-xl border border-slate-200 px-4 py-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 font-medium text-slate-800">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Judul Utama (Baris 1)</label>
                        <input type="text" name="hero_title_1" value="{{ $settings['hero_title_1'] ?? 'Membangun Generasi' }}" 
                               class="w-full text-sm rounded-xl border border-slate-200 px-4 py-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 font-bold text-slate-900">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Judul Utama Bergradasi (Baris 2)</label>
                        <input type="text" name="hero_title_2" value="{{ $settings['hero_title_2'] ?? 'Cerdas & Berkarakter' }}" 
                               class="w-full text-sm rounded-xl border border-slate-200 px-4 py-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 font-bold text-indigo-700">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Deskripsi Paragraf Hero</label>
                    <textarea name="hero_desc" rows="3" 
                              class="w-full text-sm rounded-xl border border-slate-200 p-4 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 font-medium text-slate-700 leading-relaxed">{{ $settings['hero_desc'] ?? 'Selamat datang di website resmi SMA Galatia. Kami berdedikasi menyelenggarakan pendidikan berkualitas tinggi dengan integrasi teknologi modern, sistem pembelajaran digital, dan bimbingan karakter unggul.' }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">URL Foto Banner</label>
                        <input type="text" name="hero_image" value="{{ $settings['hero_image'] ?? 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=900&q=80' }}" 
                               class="w-full text-sm rounded-xl border border-slate-200 px-4 py-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 font-mono text-xs text-slate-800">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Keterangan Tagline Foto</label>
                        <input type="text" name="hero_tagline_foto" value="{{ $settings['hero_tagline_foto'] ?? 'Pembelajaran Interaktif & Ujian Berbasis Komputer & HP (CBT)' }}" 
                               class="w-full text-sm rounded-xl border border-slate-200 px-4 py-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 font-medium text-slate-800">
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 2: PROFIL & VISI MISI -->
        <div x-show="activeTab === 'profil'" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
            <div class="border-b border-slate-100 pb-4 mb-4">
                <h2 class="text-lg font-extrabold text-slate-900">Profil, Visi, & Misi Sekolah</h2>
                <p class="text-xs text-slate-500 mt-0.5">Penjelasan arah dan tujuan SMA Galatia dalam mendidik siswa-siswi.</p>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Subjudul Ringkas Profil</label>
                    <input type="text" name="profil_subjudul" value="{{ $settings['profil_subjudul'] ?? 'Menjadi lembaga pendidikan terdepan yang menghasilkan lulusan berintegritas dan siap bersaing di perguruan tinggi favorit.' }}" 
                           class="w-full text-sm rounded-xl border border-slate-200 px-4 py-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 font-medium text-slate-800">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Visi Sekolah</label>
                    <textarea name="visi" rows="3" 
                              class="w-full text-sm rounded-xl border border-slate-200 p-4 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 font-medium text-slate-700 leading-relaxed">{{ $settings['visi'] ?? 'Terwujudnya insan pembelajar yang berakhlak mulia, unggul dalam prestasi akademik dan non-akademik, berwawasan global, serta adaptif terhadap perkembangan teknologi informasi.' }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Misi Utama #1</label>
                    <input type="text" name="misi_1" value="{{ $settings['misi_1'] ?? 'Menanamkan nilai-nilai moral, etika, dan disiplin dalam setiap aktivitas pembelajaran.' }}" 
                           class="w-full text-sm rounded-xl border border-slate-200 px-4 py-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 font-medium text-slate-800">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Misi Utama #2</label>
                    <input type="text" name="misi_2" value="{{ $settings['misi_2'] ?? 'Mengembangkan kurikulum inovatif dengan pemanfaatan platform Computer-Based Testing (CBT) dan e-learning.' }}" 
                           class="w-full text-sm rounded-xl border border-slate-200 px-4 py-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 font-medium text-slate-800">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Misi Utama #3</label>
                    <input type="text" name="misi_3" value="{{ $settings['misi_3'] ?? 'Memfasilitasi bakat dan minat siswa melalui kegiatan ekstrakurikuler serta olimpiade sains dan seni.' }}" 
                           class="w-full text-sm rounded-xl border border-slate-200 px-4 py-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 font-medium text-slate-800">
                </div>
            </div>
        </div>

        <!-- TAB 3: KEPALA SEKOLAH -->
        <div x-show="activeTab === 'kepsek'" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
            <div class="border-b border-slate-100 pb-4 mb-4">
                <h2 class="text-lg font-extrabold text-slate-900">Sambutan Kepala Sekolah</h2>
                <p class="text-xs text-slate-500 mt-0.5">Profil nama, jabatan, dan amanat sambutan kepala sekolah pada kartu bergradasi di landing page.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Kepala Sekolah</label>
                    <input type="text" name="kepsek_nama" value="{{ $settings['kepsek_nama'] ?? 'Drs. H. Hendra Wijaya, M.Pd' }}" 
                           class="w-full text-sm rounded-xl border border-slate-200 px-4 py-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 font-bold text-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Jabatan Resmi</label>
                    <input type="text" name="kepsek_jabatan" value="{{ $settings['kepsek_jabatan'] ?? 'Kepala Sekolah SMA Galatia' }}" 
                           class="w-full text-sm rounded-xl border border-slate-200 px-4 py-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 font-medium text-slate-800">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Amanat Sambutan</label>
                <textarea name="kepsek_sambutan" rows="4" 
                          class="w-full text-sm rounded-xl border border-slate-200 p-4 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 font-medium text-slate-700 leading-relaxed">{{ $settings['kepsek_sambutan'] ?? 'Pendidikan bukan sekadar mengisi wadah dengan ilmu, melainkan menyalakan lentera potensi setiap peserta didik. Di SMA Galatia, kami mendampingi putra-putri Anda untuk bertumbuh cerdas secara intelektual, tangguh secara emosional, dan luhur budi pekertinya.' }}</textarea>
            </div>
        </div>

        <!-- TAB 4: KONTAK & FOOTER -->
        <div x-show="activeTab === 'kontak'" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
            <div class="border-b border-slate-100 pb-4 mb-4">
                <h2 class="text-lg font-extrabold text-slate-900">Informasi Kontak & Footer Sekolah</h2>
                <p class="text-xs text-slate-500 mt-0.5">Alamat sekolah, saluran komunikasi, serta deskripsi di bagian bawah (footer).</p>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Alamat Lengkap Sekolah</label>
                    <input type="text" name="kontak_alamat" value="{{ $settings['kontak_alamat'] ?? 'Jl. Pendidikan No. 45, Kompleks Galatia Cendekia, Kota Jakarta Barat' }}" 
                           class="w-full text-sm rounded-xl border border-slate-200 px-4 py-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 font-medium text-slate-800">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nomor Telepon / WhatsApp</label>
                        <input type="text" name="kontak_telepon" value="{{ $settings['kontak_telepon'] ?? '(021) 582-9901 / +62 812-3456-7890' }}" 
                               class="w-full text-sm rounded-xl border border-slate-200 px-4 py-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 font-medium text-slate-800">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email Resmi Sekolah</label>
                        <input type="email" name="kontak_email" value="{{ $settings['kontak_email'] ?? 'info@galatia.sch.id' }}" 
                               class="w-full text-sm rounded-xl border border-slate-200 px-4 py-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 font-medium text-slate-800">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Jam Operasional / Pelayanan</label>
                    <input type="text" name="kontak_jam_kerja" value="{{ $settings['kontak_jam_kerja'] ?? 'Senin - Jumat: 07.00 - 15.30 WIB' }}" 
                           class="w-full text-sm rounded-xl border border-slate-200 px-4 py-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 font-medium text-slate-800">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Deskripsi Footer Sekolah</label>
                    <textarea name="footer_deskripsi" rows="2" 
                              class="w-full text-sm rounded-xl border border-slate-200 p-4 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 font-medium text-slate-700 leading-relaxed">{{ $settings['footer_deskripsi'] ?? 'Mencetak generasi cerdas, berkarakter, dan berdaya saing global melalui lingkungan pendidikan yang asri, modern, dan teknologi terdepan.' }}</textarea>
                </div>
            </div>
        </div>

        <!-- Sticky Save Action Button -->
        <div class="flex items-center justify-between p-5 bg-white rounded-2xl border border-slate-200/80 shadow-md">
            <span class="text-xs text-slate-500 font-medium">
                <i class="fa-solid fa-circle-check text-emerald-500 mr-1"></i>
                Hanya akun <strong>Super Admin</strong> yang memiliki hak akses untuk menyimpan perubahan ini.
            </span>

            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold text-sm shadow-md shadow-indigo-600/25 transition cursor-pointer">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Simpan Perubahan Landing Page</span>
            </button>
        </div>

    </form>

</div>
@endsection
