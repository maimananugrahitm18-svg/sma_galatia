<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Portal SMA Galatia' }} — Sistem Informasi Akademik</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS & Font Awesome -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: {
                        primary: {
                            50: '#eef2ff', 100: '#e0e7ff', 200: '#c7d2fe',
                            300: '#a5b4fc', 400: '#818cf8', 500: '#6366f1',
                            600: '#4f46e5', 700: '#4338ca', 800: '#3730a3',
                            900: '#312e81', 950: '#1e1b4b',
                        },
                        navy: {
                            800: '#0f172a',
                            900: '#0b1329',
                            950: '#060a17',
                        },
                        accent: { 500: '#0ea5e9', 600: '#0284c7' }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(148, 163, 184, 0.3); border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(148, 163, 184, 0.5); }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.85rem;
            border-radius: 0.875rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.18s ease-in-out;
        }
        .nav-link-active {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: #ffffff;
            font-weight: 700;
            box-shadow: 0 4px 14px -1px rgba(79, 70, 229, 0.35);
        }
        .nav-link-inactive {
            color: #94a3b8;
        }
        .nav-link-inactive:hover {
            color: #f8fafc;
            background-color: rgba(255, 255, 255, 0.06);
            transform: translateX(2px);
        }
    </style>
</head>
<body class="flex h-full font-sans antialiased bg-[#f8fafc] text-slate-800" x-data="{ sidebarOpen: false }">

    <!-- ========================
         OVERLAY (Mobile Only)
         ======================== -->
    <div x-show="sidebarOpen"
         x-cloak
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 z-30 bg-slate-950/60 backdrop-blur-xs lg:hidden">
    </div>

    <!-- ========================
         SIDEBAR (Deep Dark Elegant Luxury)
         ======================== -->
    <aside class="fixed top-0 left-0 z-40 h-full w-64 flex flex-col bg-navy-900 border-r border-slate-800/80 shadow-2xl transition-transform duration-300 ease-in-out"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

        <!-- Brand Logo Header -->
        <div class="px-5 py-5 border-b border-slate-800/90 shrink-0">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 via-primary-600 to-sky-400 flex items-center justify-center text-white shadow-lg shadow-indigo-500/25 group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-graduation-cap text-base"></i>
                </div>
                <div class="leading-tight">
                    <span class="text-base font-extrabold tracking-tight text-white block group-hover:text-indigo-300 transition">SMA GALATIA</span>
                    <span class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest">Portal Akademik</span>
                </div>
            </a>
        </div>

        <!-- User Profile Card -->
        <div class="px-4 py-4 border-b border-slate-800/80 bg-navy-950/40 shrink-0">
            @php
                $user = Auth::user();
                $roleName = $user->getRoleNames()->first() ?? 'user';
                $roleLabels = [
                    'super-admin' => ['label' => 'Super Admin', 'badge' => 'bg-purple-500/20 text-purple-300 border-purple-500/30', 'icon' => 'fa-shield-halved'],
                    'admin'       => ['label' => 'Admin TU',    'badge' => 'bg-indigo-500/20 text-indigo-300 border-indigo-500/30', 'icon' => 'fa-building-user'],
                    'guru'        => ['label' => 'Guru Pengajar', 'badge' => 'bg-sky-500/20 text-sky-300 border-sky-500/30',       'icon' => 'fa-chalkboard-user'],
                    'siswa'       => ['label' => 'Siswa Aktif',   'badge' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30', 'icon' => 'fa-user-graduate'],
                ];
                $ri = $roleLabels[$roleName] ?? ['label' => ucfirst($roleName), 'badge' => 'bg-slate-700/50 text-slate-300 border-slate-600', 'icon' => 'fa-user'];
            @endphp
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500/30 to-purple-500/30 border border-indigo-500/30 flex items-center justify-center font-black text-sm text-indigo-200 shrink-0 shadow-inner">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-bold text-white truncate">{{ Str::limit($user->name, 18) }}</p>
                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 mt-0.5 rounded-md text-[10px] font-extrabold uppercase border {{ $ri['badge'] }}">
                        <i class="fa-solid {{ $ri['icon'] }} text-[8px]"></i>
                        {{ $ri['label'] }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">

            @php
                $currentRoute = Route::currentRouteName();
            @endphp

            <!-- ======== MENU: SUPER ADMIN & ADMIN ======== -->
            @if (in_array($roleName, ['super-admin', 'admin']))

                <p class="px-3 pt-1 pb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Menu Utama</p>

                <a href="{{ route('admin.dashboard') }}"
                   class="nav-link {{ str_starts_with($currentRoute, 'admin.dashboard') ? 'nav-link-active' : 'nav-link-inactive' }}">
                    <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs shrink-0 {{ str_starts_with($currentRoute, 'admin.dashboard') ? 'bg-white/20 text-white' : 'bg-slate-800 text-slate-400' }}">
                        <i class="fa-solid fa-house"></i>
                    </span>
                    <span>Beranda</span>
                </a>

                <p class="px-3 pt-4 pb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Presensi Harian</p>

                <a href="{{ route('admin.absen.index') }}"
                   class="nav-link {{ $currentRoute === 'admin.absen.index' ? 'nav-link-active' : 'nav-link-inactive' }}">
                    <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs shrink-0 {{ $currentRoute === 'admin.absen.index' ? 'bg-white/20 text-white' : 'bg-slate-800 text-amber-400' }}">
                        <i class="fa-solid fa-clipboard-user"></i>
                    </span>
                    <span>Absen Siswa</span>
                </a>

                <a href="{{ route('admin.absen-guru.index') }}"
                   class="nav-link {{ $currentRoute === 'admin.absen-guru.index' ? 'nav-link-active' : 'nav-link-inactive' }}">
                    <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs shrink-0 {{ $currentRoute === 'admin.absen-guru.index' ? 'bg-white/20 text-white' : 'bg-slate-800 text-sky-400' }}">
                        <i class="fa-solid fa-user-clock"></i>
                    </span>
                    <span>Absen Guru</span>
                </a>

                <p class="px-3 pt-4 pb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kelola Pengguna</p>

                <a href="{{ route('admin.users.index') }}?role=guru"
                   class="nav-link {{ request('role') === 'guru' && str_starts_with($currentRoute, 'admin.users') ? 'nav-link-active' : 'nav-link-inactive' }}">
                    <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs shrink-0 {{ request('role') === 'guru' && str_starts_with($currentRoute, 'admin.users') ? 'bg-white/20 text-white' : 'bg-slate-800 text-sky-400' }}">
                        <i class="fa-solid fa-chalkboard-user"></i>
                    </span>
                    <span>Data Guru</span>
                </a>

                <a href="{{ route('admin.users.index') }}?role=siswa"
                   class="nav-link {{ request('role') === 'siswa' && str_starts_with($currentRoute, 'admin.users') ? 'nav-link-active' : 'nav-link-inactive' }}">
                    <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs shrink-0 {{ request('role') === 'siswa' && str_starts_with($currentRoute, 'admin.users') ? 'bg-white/20 text-white' : 'bg-slate-800 text-emerald-400' }}">
                        <i class="fa-solid fa-user-graduate"></i>
                    </span>
                    <span>Data Siswa</span>
                </a>

                <p class="px-3 pt-4 pb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Akademik & Sistem</p>

                <a href="{{ route('admin.jadwal.index') }}"
                   class="nav-link {{ str_starts_with($currentRoute, 'admin.jadwal') ? 'nav-link-active' : 'nav-link-inactive' }}">
                    <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs shrink-0 {{ str_starts_with($currentRoute, 'admin.jadwal') ? 'bg-white/20 text-white' : 'bg-slate-800 text-indigo-400' }}">
                        <i class="fa-solid fa-calendar-days"></i>
                    </span>
                    <span>Jadwal Mengajar</span>
                </a>

                <a href="{{ route('admin.kelas.index') }}"
                   class="nav-link {{ str_starts_with($currentRoute, 'admin.kelas') ? 'nav-link-active' : 'nav-link-inactive' }}">
                    <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs shrink-0 {{ str_starts_with($currentRoute, 'admin.kelas') ? 'bg-white/20 text-white' : 'bg-slate-800 text-sky-400' }}">
                        <i class="fa-solid fa-school"></i>
                    </span>
                    <span>Manajemen Kelas</span>
                </a>

                <a href="{{ route('admin.mapel.index') }}"
                   class="nav-link {{ str_starts_with($currentRoute, 'admin.mapel') ? 'nav-link-active' : 'nav-link-inactive' }}">
                    <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs shrink-0 {{ str_starts_with($currentRoute, 'admin.mapel') ? 'bg-white/20 text-white' : 'bg-slate-800 text-violet-400' }}">
                        <i class="fa-solid fa-book-open"></i>
                    </span>
                    <span>Mata Pelajaran</span>
                </a>

                <a href="{{ route('admin.users.index') }}"
                   class="nav-link {{ !request('role') && str_starts_with($currentRoute, 'admin.users') ? 'nav-link-active' : 'nav-link-inactive' }}">
                    <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs shrink-0 {{ !request('role') && str_starts_with($currentRoute, 'admin.users') ? 'bg-white/20 text-white' : 'bg-slate-800 text-slate-400' }}">
                        <i class="fa-solid fa-users-gear"></i>
                    </span>
                    <span>Semua Akun</span>
                </a>

                <p class="px-3 pt-4 pb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Laporan & Rekapitulasi</p>

                <a href="{{ route('admin.rekap.absen-siswa') }}"
                   class="nav-link {{ str_starts_with($currentRoute, 'admin.rekap.absen-siswa') ? 'nav-link-active' : 'nav-link-inactive' }}">
                    <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs shrink-0 {{ str_starts_with($currentRoute, 'admin.rekap.absen-siswa') ? 'bg-white/20 text-white' : 'bg-slate-800 text-emerald-400' }}">
                        <i class="fa-solid fa-clipboard-check"></i>
                    </span>
                    <span>Rekap Absen Siswa</span>
                </a>

                <a href="{{ route('admin.rekap.absen-guru') }}"
                   class="nav-link {{ str_starts_with($currentRoute, 'admin.rekap.absen-guru') ? 'nav-link-active' : 'nav-link-inactive' }}">
                    <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs shrink-0 {{ str_starts_with($currentRoute, 'admin.rekap.absen-guru') ? 'bg-white/20 text-white' : 'bg-slate-800 text-sky-400' }}">
                        <i class="fa-solid fa-chalkboard-user"></i>
                    </span>
                    <span>Rekap Absen Guru</span>
                </a>

                <a href="{{ route('admin.rekap.nilai') }}"
                   class="nav-link {{ str_starts_with($currentRoute, 'admin.rekap.nilai') ? 'nav-link-active' : 'nav-link-inactive' }}">
                    <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs shrink-0 {{ str_starts_with($currentRoute, 'admin.rekap.nilai') ? 'bg-white/20 text-white' : 'bg-slate-800 text-purple-400' }}">
                        <i class="fa-solid fa-award"></i>
                    </span>
                    <span>Rekap Nilai Siswa</span>
                </a>

                @if ($roleName === 'super-admin')
                    <p class="px-3 pt-5 pb-2 text-[10px] font-black text-amber-400 uppercase tracking-widest flex items-center gap-1.5">
                        <i class="fa-solid fa-crown text-amber-400"></i>
                        <span>Super Admin CMS</span>
                    </p>

                    <a href="{{ route('superadmin.landing.edit') }}"
                       class="nav-link {{ str_starts_with($currentRoute, 'superadmin.landing') ? 'nav-link-active' : 'nav-link-inactive' }}">
                        <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs shrink-0 {{ str_starts_with($currentRoute, 'superadmin.landing') ? 'bg-white/20 text-white' : 'bg-slate-800 text-amber-400' }}">
                            <i class="fa-solid fa-sliders"></i>
                        </span>
                        <span>Kelola Landing Page</span>
                    </a>
                @endif

            @endif

            <!-- ======== MENU: GURU ======== -->
            @if ($roleName === 'guru')

                <p class="px-3 pt-1 pb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Portal Guru</p>

                <a href="{{ route('guru.dashboard') }}"
                   class="nav-link {{ $currentRoute === 'guru.dashboard' ? 'nav-link-active' : 'nav-link-inactive' }}">
                    <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs shrink-0 {{ $currentRoute === 'guru.dashboard' ? 'bg-white/20 text-white' : 'bg-slate-800 text-slate-400' }}">
                        <i class="fa-solid fa-house"></i>
                    </span>
                    <span>Beranda</span>
                </a>

                <a href="{{ route('guru.kehadiran.index') }}"
                   class="nav-link {{ str_starts_with($currentRoute, 'guru.kehadiran') ? 'nav-link-active' : 'nav-link-inactive' }}">
                    <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs shrink-0 {{ str_starts_with($currentRoute, 'guru.kehadiran') ? 'bg-white/20 text-white' : 'bg-slate-800 text-emerald-400' }}">
                        <i class="fa-solid fa-fingerprint"></i>
                    </span>
                    <span>Absen Saya (Guru)</span>
                </a>

                <a href="{{ route('guru.absen.index') }}"
                   class="nav-link {{ str_starts_with($currentRoute, 'guru.absen') ? 'nav-link-active' : 'nav-link-inactive' }}">
                    <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs shrink-0 {{ str_starts_with($currentRoute, 'guru.absen') ? 'bg-white/20 text-white' : 'bg-slate-800 text-amber-400' }}">
                        <i class="fa-solid fa-clipboard-user"></i>
                    </span>
                    <span>Absen Siswa (Kelas)</span>
                </a>

                <a href="{{ route('guru.jadwal.saya') }}"
                   class="nav-link {{ $currentRoute === 'guru.jadwal.saya' ? 'nav-link-active' : 'nav-link-inactive' }}">
                    <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs shrink-0 {{ $currentRoute === 'guru.jadwal.saya' ? 'bg-white/20 text-white' : 'bg-slate-800 text-sky-400' }}">
                        <i class="fa-solid fa-calendar-week"></i>
                    </span>
                    <span>Jadwal Mengajar</span>
                </a>

                <a href="{{ route('guru.ujian.index') }}"
                   class="nav-link {{ str_starts_with($currentRoute, 'guru.ujian') ? 'nav-link-active' : 'nav-link-inactive' }}">
                    <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs shrink-0 {{ str_starts_with($currentRoute, 'guru.ujian') ? 'bg-white/20 text-white' : 'bg-slate-800 text-purple-400' }}">
                        <i class="fa-solid fa-file-pen"></i>
                    </span>
                    <span>Ulangan Harian</span>
                </a>

                <p class="px-3 pt-5 pb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Laporan & Rekap</p>

                <a href="{{ route('guru.rekap.absen-guru') }}"
                   class="nav-link {{ str_starts_with($currentRoute, 'guru.rekap.absen-guru') ? 'nav-link-active' : 'nav-link-inactive' }}">
                    <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs shrink-0 {{ str_starts_with($currentRoute, 'guru.rekap.absen-guru') ? 'bg-white/20 text-white' : 'bg-slate-800 text-sky-400' }}">
                        <i class="fa-solid fa-user-clock"></i>
                    </span>
                    <span>Rekap Absen Guru</span>
                </a>

                <a href="{{ route('guru.rekap.absen-siswa') }}"
                   class="nav-link {{ str_starts_with($currentRoute, 'guru.rekap.absen-siswa') ? 'nav-link-active' : 'nav-link-inactive' }}">
                    <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs shrink-0 {{ str_starts_with($currentRoute, 'guru.rekap.absen-siswa') ? 'bg-white/20 text-white' : 'bg-slate-800 text-emerald-400' }}">
                        <i class="fa-solid fa-calendar-check"></i>
                    </span>
                    <span>Rekap Absen Siswa</span>
                </a>

                <a href="{{ route('guru.rekap.nilai') }}"
                   class="nav-link {{ str_starts_with($currentRoute, 'guru.rekap.nilai') ? 'nav-link-active' : 'nav-link-inactive' }}">
                    <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs shrink-0 {{ str_starts_with($currentRoute, 'guru.rekap.nilai') ? 'bg-white/20 text-white' : 'bg-slate-800 text-purple-400' }}">
                        <i class="fa-solid fa-chart-line"></i>
                    </span>
                    <span>Rekap Nilai Siswa</span>
                </a>

            @endif

            <!-- ======== MENU: SISWA ======== -->
            @if ($roleName === 'siswa')

                <p class="px-3 pt-1 pb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Portal Siswa</p>

                <a href="{{ route('siswa.dashboard') }}"
                   class="nav-link {{ $currentRoute === 'siswa.dashboard' ? 'nav-link-active' : 'nav-link-inactive' }}">
                    <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs shrink-0 {{ $currentRoute === 'siswa.dashboard' ? 'bg-white/20 text-white' : 'bg-slate-800 text-slate-400' }}">
                        <i class="fa-solid fa-house"></i>
                    </span>
                    <span>Beranda</span>
                </a>

                <a href="{{ route('siswa.absen.index') }}"
                   class="nav-link {{ str_starts_with($currentRoute, 'siswa.absen') ? 'nav-link-active' : 'nav-link-inactive' }}">
                    <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs shrink-0 {{ str_starts_with($currentRoute, 'siswa.absen') ? 'bg-white/20 text-white' : 'bg-slate-800 text-amber-400' }}">
                        <i class="fa-solid fa-clipboard-check"></i>
                    </span>
                    <span>Absen / Kehadiran</span>
                </a>

                <a href="{{ route('siswa.jadwal.saya') }}"
                   class="nav-link {{ $currentRoute === 'siswa.jadwal.saya' ? 'nav-link-active' : 'nav-link-inactive' }}">
                    <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs shrink-0 {{ $currentRoute === 'siswa.jadwal.saya' ? 'bg-white/20 text-white' : 'bg-slate-800 text-sky-400' }}">
                        <i class="fa-solid fa-calendar-days"></i>
                    </span>
                    <span>Jadwal Pelajaran</span>
                </a>

                <a href="{{ route('siswa.ujian.index') }}"
                   class="nav-link {{ str_starts_with($currentRoute, 'siswa.ujian') ? 'nav-link-active' : 'nav-link-inactive' }}">
                    <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs shrink-0 {{ str_starts_with($currentRoute, 'siswa.ujian') ? 'bg-white/20 text-white' : 'bg-slate-800 text-purple-400' }}">
                        <i class="fa-solid fa-laptop-code"></i>
                    </span>
                    <span>Ulangan Harian</span>
                </a>

            @endif

        </nav>

        <!-- Bottom: Logout -->
        <div class="border-t border-slate-800/90 px-3 py-3 space-y-1.5 shrink-0 bg-navy-950/40">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full nav-link text-rose-400 hover:text-white hover:bg-rose-500/20 border border-transparent hover:border-rose-500/30 transition-all cursor-pointer">
                    <span class="w-7 h-7 rounded-lg bg-rose-500/10 text-rose-400 flex items-center justify-center text-xs shrink-0">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </span>
                    <span class="font-bold">Keluar</span>
                </button>
            </form>
        </div>

    </aside>

    <!-- ========================
         MAIN CONTENT AREA
         ======================== -->
    <div class="flex flex-col flex-1 min-h-full lg:ml-64">

        <!-- Top Header Bar (Desktop & Mobile) -->
        <header class="sticky top-0 z-20 bg-white/90 backdrop-blur-md border-b border-slate-200/80 shadow-xs flex items-center justify-between px-4 sm:px-8 py-3.5">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = true" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition lg:hidden cursor-pointer">
                    <i class="fa-solid fa-bars text-sm"></i>
                </button>
                <div class="hidden sm:flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80 shadow-2xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>T.A 2026/2027 &bull; Ganjil</span>
                    </span>
                    <span class="text-xs font-semibold text-slate-400">&bull;</span>
                    <span class="text-xs font-medium text-slate-500 flex items-center gap-1.5">
                        <i class="fa-regular fa-calendar-check text-slate-400"></i>
                        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-3">

                {{-- Profile Dropdown --}}
                <div class="relative pl-2 border-l border-slate-200"
                     x-data="{
                        open: false,
                        showNameModal: false,
                        showPassModal: false,
                        showPassCurrent: false,
                        showPassNew: false,
                        showPassConfirm: false,
                     }">

                    {{-- Trigger --}}
                    <button @click="open = !open"
                            class="flex items-center gap-2.5 hover:bg-slate-50 rounded-xl px-2 py-1.5 transition">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-indigo-600 to-primary-600 text-white flex items-center justify-center font-extrabold text-xs shadow-xs shrink-0">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div class="hidden sm:block text-left leading-tight">
                            <p class="text-xs font-bold text-slate-900 truncate max-w-[110px]">{{ $user->name }}</p>
                            <p class="text-[10px] font-semibold text-slate-400 capitalize">{{ $roleName }}</p>
                        </div>
                        <i class="fa-solid fa-chevron-down text-[9px] text-slate-400 hidden sm:block transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </button>

                    {{-- Dropdown --}}
                    <div x-show="open" x-cloak @click.outside="open = false"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 top-full mt-2 w-60 bg-white rounded-2xl border border-slate-200 shadow-xl z-50 overflow-hidden">

                        <div class="px-4 py-3.5 border-b border-slate-100 bg-slate-50">
                            <div class="flex items-center gap-2.5">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-primary-600 text-white flex items-center justify-center font-extrabold text-sm shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-slate-900 truncate">{{ $user->name }}</p>
                                    <p class="text-xs text-slate-400 truncate">{{ $user->email }}</p>
                                    <span class="inline-block mt-0.5 px-2 py-0.5 rounded-md bg-indigo-100 text-indigo-700 text-[10px] font-bold capitalize">{{ $roleName }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="py-1.5">
                            <button @click="open = false; showNameModal = true"
                                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-primary-600 transition text-left">
                                <i class="fa-solid fa-user-pen w-4 text-slate-400"></i> Ubah Nama
                            </button>
                            <button @click="open = false; showPassModal = true"
                                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-primary-600 transition text-left">
                                <i class="fa-solid fa-lock w-4 text-slate-400"></i> Ubah Kata Sandi
                            </button>
                            @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('super-admin'))
                                <div class="border-t border-slate-100 my-1"></div>
                                <a href="{{ route('admin.users.reset-password') }}"
                                   class="flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-rose-600 hover:bg-rose-50 transition">
                                    <i class="fa-solid fa-key w-4"></i> Reset Password Pengguna
                                </a>
                            @endif
                            <div class="border-t border-slate-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-slate-500 hover:bg-rose-50 hover:text-rose-600 transition text-left">
                                    <i class="fa-solid fa-right-from-bracket w-4"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Modal: Ubah Nama --}}
                    <div x-show="showNameModal" x-cloak
                         class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                        <div class="bg-white rounded-3xl w-full max-w-sm p-6 shadow-2xl border border-slate-200"
                             @click.outside="showNameModal = false">
                            <div class="flex items-center gap-3 mb-5">
                                <div class="w-12 h-12 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl">
                                    <i class="fa-solid fa-user-pen"></i>
                                </div>
                                <div>
                                    <h3 class="text-base font-extrabold text-slate-900">Ubah Nama</h3>
                                    <p class="text-xs text-slate-500">Perbarui nama tampilan akun Anda</p>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('profile.name.update') }}">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Baru</label>
                                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
                                               placeholder="Masukkan nama lengkap">
                                        @error('name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="flex gap-2.5">
                                        <button type="button" @click="showNameModal = false"
                                                class="flex-1 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm transition">Batal</button>
                                        <button type="submit"
                                                class="flex-1 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm transition shadow-sm">
                                            <i class="fa-solid fa-check mr-1"></i>Simpan
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Modal: Ubah Kata Sandi --}}
                    <div x-show="showPassModal" x-cloak
                         class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                        <div class="bg-white rounded-3xl w-full max-w-sm p-6 shadow-2xl border border-slate-200"
                             @click.outside="showPassModal = false">
                            <div class="flex items-center gap-3 mb-5">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl">
                                    <i class="fa-solid fa-lock"></i>
                                </div>
                                <div>
                                    <h3 class="text-base font-extrabold text-slate-900">Ubah Kata Sandi</h3>
                                    <p class="text-xs text-slate-500">Masukkan password lama terlebih dahulu</p>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('profile.password.update') }}">
                                @csrf
                                <div class="space-y-3.5">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Password Lama</label>
                                        <div class="relative">
                                            <input :type="showPassCurrent ? 'text' : 'password'" name="current_password" required
                                                   class="w-full px-3.5 py-2.5 pr-10 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
                                                   placeholder="Password saat ini">
                                            <button type="button" @click="showPassCurrent = !showPassCurrent"
                                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                                <i :class="showPassCurrent ? 'fa-eye-slash' : 'fa-eye'" class="fa-regular text-sm"></i>
                                            </button>
                                        </div>
                                        @error('current_password') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Password Baru <span class="text-slate-400 font-normal">(min. 6 karakter)</span></label>
                                        <div class="relative">
                                            <input :type="showPassNew ? 'text' : 'password'" name="password" required minlength="6"
                                                   class="w-full px-3.5 py-2.5 pr-10 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
                                                   placeholder="Password baru">
                                            <button type="button" @click="showPassNew = !showPassNew"
                                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                                <i :class="showPassNew ? 'fa-eye-slash' : 'fa-eye'" class="fa-regular text-sm"></i>
                                            </button>
                                        </div>
                                        @error('password') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Konfirmasi Password Baru</label>
                                        <div class="relative">
                                            <input :type="showPassConfirm ? 'text' : 'password'" name="password_confirmation" required
                                                   class="w-full px-3.5 py-2.5 pr-10 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
                                                   placeholder="Ulangi password baru">
                                            <button type="button" @click="showPassConfirm = !showPassConfirm"
                                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                                <i :class="showPassConfirm ? 'fa-eye-slash' : 'fa-eye'" class="fa-regular text-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="flex gap-2.5 pt-1">
                                        <button type="button" @click="showPassModal = false"
                                                class="flex-1 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm transition">Batal</button>
                                        <button type="submit"
                                                class="flex-1 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm transition shadow-sm">
                                            <i class="fa-solid fa-shield-check mr-1"></i>Perbarui
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </header>


        <!-- Flash Notifications -->
        @if (session('success') || session('error') || session('info'))
            <div class="px-4 sm:px-8 pt-5">
                @if (session('success'))
                    <div class="flex items-center gap-3 p-4 mb-2 text-emerald-800 rounded-2xl bg-emerald-50 border border-emerald-200 shadow-xs text-sm animate-fade-in">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-base shrink-0"></i>
                        <span class="font-semibold">{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="flex items-center gap-3 p-4 mb-2 text-rose-800 rounded-2xl bg-rose-50 border border-rose-200 shadow-xs text-sm animate-fade-in">
                        <i class="fa-solid fa-triangle-exclamation text-rose-600 text-base shrink-0"></i>
                        <span class="font-semibold">{{ session('error') }}</span>
                    </div>
                @endif
                @if (session('info'))
                    <div class="flex items-center gap-3 p-4 mb-2 text-sky-800 rounded-2xl bg-sky-50 border border-sky-200 shadow-xs text-sm animate-fade-in">
                        <i class="fa-solid fa-circle-info text-sky-600 text-base shrink-0"></i>
                        <span class="font-semibold">{{ session('info') }}</span>
                    </div>
                @endif
            </div>
        @endif

        <!-- Page Content -->
        <main class="flex-1 pb-10">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="border-t border-slate-200/80 bg-white/80 px-4 sm:px-8 py-3.5 text-xs text-slate-400 flex flex-col sm:flex-row items-center justify-between gap-2">
            <span>&copy; {{ date('Y') }} SMA GALATIA &bull; Sistem Informasi Akademik & Portal Pembelajaran</span>
            <span class="text-slate-400 font-medium">Elegan &bull; Cepat &bull; Responsif</span>
        </footer>

    </div>

    @stack('scripts')
</body>
</html>
