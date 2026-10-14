@extends('layouts.app')

@section('content')

@auth
    @if(Auth::user()->hasRole('super-admin'))
        <!-- Floating Super Admin CMS Shortcut -->
        <div class="fixed bottom-6 right-6 z-50 animate-bounce">
            <a href="{{ route('superadmin.landing.edit') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs shadow-2xl border-2 border-white transition transform hover:scale-105">
                <i class="fa-solid fa-crown text-base text-slate-900"></i>
                <span>Edit Konten Halaman Ini (Super Admin)</span>
            </a>
        </div>
    @endif
@endauth

<!-- Hero Section -->
<section class="relative overflow-hidden bg-gradient-to-b from-primary-50/60 via-white to-white py-20 lg:py-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary-100/80 text-primary-800 text-xs font-semibold tracking-wide border border-primary-200">
                    <span class="w-2 h-2 rounded-full bg-primary-600 animate-pulse"></span>
                    {{ $settings['hero_badge'] ?? 'Tahun Ajaran 2026/2027 • Akreditasi A Unggul' }}
                </div>
                
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight leading-[1.15]">
                    {{ $settings['hero_title_1'] ?? 'Membangun Generasi' }} <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 via-primary-700 to-accent-600">
                        {{ $settings['hero_title_2'] ?? 'Cerdas & Berkarakter' }}
                    </span>
                </h1>
                
                <p class="text-base sm:text-lg text-slate-600 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                    {{ $settings['hero_desc'] ?? 'Selamat datang di website resmi SMA Galatia. Kami berdedikasi menyelenggarakan pendidikan berkualitas tinggi dengan integrasi teknologi modern, sistem pembelajaran digital, dan bimbingan karakter unggul.' }}
                </p>

                <!-- Action CTA -->
                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-3 pt-2">
                    <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-semibold shadow-lg shadow-primary-500/25 transition-all hover:-translate-y-0.5">
                        <i class="fa-solid fa-laptop-code"></i>
                        Masuk Portal CBT Siswa
                    </a>
                    <a href="#profil" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 font-semibold border border-slate-200 shadow-xs transition-all">
                        <i class="fa-solid fa-compass"></i>
                        Jelajahi Profil Sekolah
                    </a>
                </div>

                <!-- Stats Bar -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-6 border-t border-slate-200/80">
                    <div class="p-3 bg-white/60 rounded-xl border border-slate-100 shadow-2xs">
                        <div class="text-2xl font-bold text-slate-900">{{ $stats['siswa'] ?? '350+' }}</div>
                        <div class="text-xs text-slate-500">Siswa Aktif</div>
                    </div>
                    <div class="p-3 bg-white/60 rounded-xl border border-slate-100 shadow-2xs">
                        <div class="text-2xl font-bold text-slate-900">{{ $stats['guru'] ?? '30+' }}</div>
                        <div class="text-xs text-slate-500">Guru Berdedikasi</div>
                    </div>
                    <div class="p-3 bg-white/60 rounded-xl border border-slate-100 shadow-2xs">
                        <div class="text-2xl font-bold text-slate-900">{{ $stats['kelas'] ?? '12' }}</div>
                        <div class="text-xs text-slate-500">Rombongan Belajar</div>
                    </div>
                    <div class="p-3 bg-white/60 rounded-xl border border-slate-100 shadow-2xs">
                        <div class="text-2xl font-bold text-primary-600">A</div>
                        <div class="text-xs text-slate-500">Akreditasi BAN-S/M</div>
                    </div>
                </div>

            </div>

            <!-- Hero Image Banner -->
            <div class="lg:col-span-5 relative">
                <div class="relative mx-auto max-w-md lg:max-w-none">
                    <div class="absolute -top-4 -left-4 w-72 h-72 bg-primary-300 rounded-full mix-blend-multiply filter blur-2xl opacity-40 animate-blob"></div>
                    <div class="absolute -bottom-4 -right-4 w-72 h-72 bg-accent-300 rounded-full mix-blend-multiply filter blur-2xl opacity-40 animate-blob animation-delay-2000"></div>
                    
                    <div class="relative rounded-3xl overflow-hidden shadow-2xl border-4 border-white bg-slate-900 group">
                        <img src="{{ $settings['hero_image'] ?? 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=900&q=80' }}" alt="Siswa SMA Galatia" class="w-full h-[420px] object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent flex flex-col justify-end p-6 text-white">
                            <span class="inline-block px-2.5 py-1 rounded-md bg-primary-600/90 text-white text-[11px] font-bold uppercase tracking-wider w-fit mb-2">Smart Campus</span>
                            <p class="font-semibold text-sm">{{ $settings['hero_tagline_foto'] ?? 'Pembelajaran Interaktif & Ujian Berbasis Komputer & HP (CBT)' }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Profil & Sambutan Section -->
<section id="profil" class="py-20 bg-white border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-bold text-primary-600 tracking-wider uppercase">Tentang Kami</span>
            <h2 class="text-3xl font-bold text-slate-900 mt-2">Profil & Visi Misi SMA Galatia</h2>
            <p class="text-slate-600 text-sm mt-3">{{ $settings['profil_subjudul'] ?? 'Menjadi lembaga pendidikan terdepan yang menghasilkan lulusan berintegritas dan siap bersaing di perguruan tinggi favorit.' }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80">
                    <h3 class="text-xl font-bold text-slate-900 mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-bullseye text-primary-600"></i>
                        Visi Sekolah
                    </h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        "{{ $settings['visi'] ?? 'Terwujudnya insan pembelajar yang berakhlak mulia, unggul dalam prestasi akademik dan non-akademik, berwawasan global, serta adaptif terhadap perkembangan teknologi informasi.' }}"
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80">
                    <h3 class="text-xl font-bold text-slate-900 mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-compass text-primary-600"></i>
                        Misi Utama
                    </h3>
                    <ul class="space-y-2.5 text-sm text-slate-600">
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-check text-emerald-500 mt-1"></i>
                            <span>{{ $settings['misi_1'] ?? 'Menanamkan nilai-nilai moral, etika, dan disiplin dalam setiap aktivitas pembelajaran.' }}</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-check text-emerald-500 mt-1"></i>
                            <span>{{ $settings['misi_2'] ?? 'Mengembangkan kurikulum inovatif dengan pemanfaatan platform Computer-Based Testing (CBT) dan e-learning.' }}</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-check text-emerald-500 mt-1"></i>
                            <span>{{ $settings['misi_3'] ?? 'Memfasilitasi bakat dan minat siswa melalui kegiatan ekstrakurikuler serta olimpiade sains dan seni.' }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Sambutan Card -->
            <div class="relative bg-gradient-to-br from-primary-900 via-primary-800 to-slate-900 rounded-3xl p-8 text-white shadow-xl">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-3xl text-accent-400">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold">{{ $settings['kepsek_nama'] ?? 'Drs. H. Hendra Wijaya, M.Pd' }}</h4>
                        <p class="text-xs text-primary-200">{{ $settings['kepsek_jabatan'] ?? 'Kepala Sekolah SMA Galatia' }}</p>
                    </div>
                </div>
                <blockquote class="text-sm text-slate-200 leading-relaxed italic mb-6">
                    "{{ $settings['kepsek_sambutan'] ?? 'Pendidikan bukan sekadar mengisi wadah dengan ilmu, melainkan menyalakan lentera potensi setiap peserta didik. Di SMA Galatia, kami mendampingi putra-putri Anda untuk bertumbuh cerdas secara intelektual, tangguh secara emosional, dan luhur budi pekertinya.' }}"
                </blockquote>
                <div class="pt-4 border-t border-white/10 flex items-center justify-between text-xs text-primary-300">
                    <span>Maju Bersama, Hebat Semua!</span>
                    <span class="font-semibold text-white">SMA Galatia Mandiri</span>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Fasilitas Section -->
<section id="fasilitas" class="py-20 bg-slate-50 border-t border-slate-200/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-bold text-primary-600 tracking-wider uppercase">Fasilitas Unggulan</span>
            <h2 class="text-3xl font-bold text-slate-900 mt-2">Lingkungan Belajar yang Menunjang</h2>
            <p class="text-slate-600 text-sm mt-3">Menyediakan sarana dan prasarana modern untuk kenyamanan dan efektivitas pembelajaran siswa.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-2xl overflow-hidden shadow-xs border border-slate-200/80 hover:shadow-md transition">
                <img src="https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?auto=format&fit=crop&w=700&q=80" alt="Lab Komputer" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="font-bold text-base text-slate-900 mb-2">Laboratorium Komputer & CBT</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">Dilengkapi 40 unit PC berspesifikasi tinggi, pendingin ruangan, dan koneksi internet fiber optic untuk ujian online serta materi IT.</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl overflow-hidden shadow-xs border border-slate-200/80 hover:shadow-md transition">
                <img src="https://images.unsplash.com/photo-1521587760476-6c12a4b040da?auto=format&fit=crop&w=700&q=80" alt="Perpustakaan" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="font-bold text-base text-slate-900 mb-2">Perpustakaan Digital Terpadu</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">Koleksi literatur fisik lengkap serta ribuan buku elektronik yang dapat diakses oleh seluruh siswa secara cuma-cuma.</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl overflow-hidden shadow-xs border border-slate-200/80 hover:shadow-md transition">
                <img src="https://images.unsplash.com/photo-1577896851231-70ef18881754?auto=format&fit=crop&w=700&q=80" alt="Fasilitas Olahraga" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="font-bold text-base text-slate-900 mb-2">Lapangan Olahraga Serbaguna</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">Fasilitas basket, futsal, dan bulutangkis berstandar untuk mendukung kebugaran dan prestasi non-akademik siswa.</p>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Berita & Pengumuman -->
<section id="berita" class="py-20 bg-white border-t border-slate-200/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-12">
            <div>
                <span class="text-xs font-bold text-primary-600 tracking-wider uppercase">Kabar Sekolah</span>
                <h2 class="text-3xl font-bold text-slate-900 mt-2">Warta & Berita Terkini</h2>
            </div>
            <a href="#" class="mt-4 sm:mt-0 text-sm font-semibold text-primary-600 hover:text-primary-700 flex items-center gap-1">
                Lihat Semua Berita <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse ($beritaTerbaru as $b)
                <article class="bg-slate-50 rounded-2xl overflow-hidden border border-slate-200/80 flex flex-col hover:shadow-md transition">
                    <img src="{{ $b->thumbnail }}" alt="{{ $b->judul }}" class="w-full h-48 object-cover">
                    <div class="p-6 flex flex-col grow">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-md uppercase bg-primary-100 text-primary-800">{{ $b->kategori }}</span>
                            <span class="text-xs text-slate-400">• {{ $b->created_at->format('d M Y') }}</span>
                        </div>
                        <h3 class="font-bold text-base text-slate-900 mb-2 line-clamp-2 hover:text-primary-600 transition">
                            <a href="{{ route('berita.detail', $b->slug) }}">{{ $b->judul }}</a>
                        </h3>
                        <p class="text-xs text-slate-600 line-clamp-3 mb-4 leading-relaxed">{{ $b->konten }}</p>
                        <div class="mt-auto pt-3 border-t border-slate-200 flex items-center justify-between text-xs">
                            <span class="text-slate-400"><i class="fa-regular fa-eye mr-1"></i>{{ $b->views }} views</span>
                            <a href="{{ route('berita.detail', $b->slug) }}" class="font-semibold text-primary-600 hover:text-primary-700">Baca Selengkapnya &rarr;</a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-3 text-center py-8 text-slate-400 text-sm">Belum ada berita yang dipublikasikan.</div>
            @endforelse
        </div>

    </div>
</section>

<!-- Call to Action Banner & Kontak -->
<section id="kontak" class="py-16 bg-primary-900 text-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <h2 class="text-3xl font-extrabold mb-4">Siap Bergabung dengan SMA Galatia?</h2>
        <p class="text-primary-200 max-w-2xl mx-auto text-sm sm:text-base mb-8">
            {{ $settings['footer_deskripsi'] ?? 'Informasi PPDB & pendaftaran siswa baru dapat ditanyakan langsung kepada Tata Usaha SMA Galatia.' }}
        </p>
        <div class="flex flex-wrap items-center justify-center gap-4">
            <a href="{{ route('login') }}" class="px-6 py-3 rounded-xl bg-white text-primary-900 font-bold hover:bg-slate-100 shadow-lg transition">
                <i class="fa-solid fa-right-to-bracket mr-2"></i> Masuk ke Portal Sekolah
            </a>
            @if(!empty($settings['kontak_telepon']))
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $settings['kontak_telepon']) }}" class="px-6 py-3 rounded-xl bg-primary-800 text-white font-semibold border border-primary-700 hover:bg-primary-700 transition">
                    <i class="fa-solid fa-phone mr-2"></i> {{ $settings['kontak_telepon'] }}
                </a>
            @endif
        </div>
        <div class="mt-8 pt-6 border-t border-primary-800/80 text-xs text-primary-300 flex flex-wrap justify-center gap-4">
            <span><i class="fa-solid fa-location-dot mr-1 text-primary-400"></i> {{ $settings['kontak_alamat'] ?? 'Jl. Pendidikan No. 45' }}</span>
            <span><i class="fa-solid fa-envelope mr-1 text-primary-400"></i> {{ $settings['kontak_email'] ?? 'info@galatia.sch.id' }}</span>
            <span><i class="fa-solid fa-clock mr-1 text-primary-400"></i> {{ $settings['kontak_jam_kerja'] ?? 'Senin - Jumat' }}</span>
        </div>
        <p class="mt-4 text-[11px] text-primary-400">
            <i class="fa-solid fa-circle-info mr-1"></i>
            Pembuatan akun siswa/guru hanya dapat dilakukan oleh Admin / Tata Usaha Sekolah.
        </p>
    </div>
</section>
@endsection
