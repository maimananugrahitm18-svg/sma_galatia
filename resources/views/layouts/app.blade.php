<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'SMA Galatia' }} - Portal Akademik & Sekolah</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN & FontAwesome -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        },
                        accent: {
                            500: '#0ea5e9',
                            600: '#0284c7',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="flex flex-col min-h-screen text-slate-800 antialiased font-sans">

    <!-- Global Top Navigation -->
    <header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-slate-200/80 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <!-- Brand Logo -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-primary-700 via-primary-600 to-accent-500 flex items-center justify-center text-white shadow-md shadow-primary-500/20 group-hover:scale-105 transition-transform duration-200">
                            <i class="fa-solid fa-graduation-cap text-lg"></i>
                        </div>
                        <div>
                            <span class="text-lg font-bold tracking-tight text-slate-900 block leading-tight">SMA GALATIA</span>
                            <span class="text-[10px] uppercase font-semibold text-primary-600 tracking-wider">Veritas et Sapientia</span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center gap-6">
                    <a href="{{ route('home') }}" class="text-sm font-medium text-slate-600 hover:text-primary-600 transition-colors">Beranda</a>
                    <a href="{{ route('home') }}#profil" class="text-sm font-medium text-slate-600 hover:text-primary-600 transition-colors">Profil</a>
                    <a href="{{ route('home') }}#fasilitas" class="text-sm font-medium text-slate-600 hover:text-primary-600 transition-colors">Fasilitas</a>
                    <a href="{{ route('home') }}#berita" class="text-sm font-medium text-slate-600 hover:text-primary-600 transition-colors">Berita</a>
                    <a href="{{ route('home') }}#kontak" class="text-sm font-medium text-slate-600 hover:text-primary-600 transition-colors">Kontak</a>
                </nav>

                <!-- Auth / User Actions -->
                <div class="flex items-center gap-3">
                    @auth
                        @php
                            $user = Auth::user();
                            $roleName = $user->getRoleNames()->first() ?? 'User';
                            $dashboardRoute = match($roleName) {
                                'super-admin', 'admin' => route('admin.dashboard'),
                                'guru' => route('guru.dashboard'),
                                'siswa' => route('siswa.dashboard'),
                                default => route('home'),
                            };
                        @endphp

                        <a href="{{ $dashboardRoute }}" class="hidden sm:inline-flex items-center gap-2 px-3 py-1.5 text-xs font-semibold rounded-lg bg-primary-50 text-primary-700 hover:bg-primary-100 transition-colors">
                            <i class="fa-solid fa-gauge-high"></i>
                            Dashboard
                        </a>

                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 p-1 rounded-full hover:ring-2 hover:ring-primary-400 focus:outline-hidden">
                                <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center font-bold text-xs uppercase border border-slate-300">
                                    {{ substr($user->name, 0, 2) }}
                                </div>
                                <span class="hidden lg:block text-xs font-medium text-slate-700">{{ Str::limit($user->name, 15) }}</span>
                                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 hidden lg:block"></i>
                            </button>

                            <div x-show="open" @click.outside="open = false" x-cloak class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-lg border border-slate-100 py-1 z-50 text-sm">
                                <div class="px-4 py-2 border-b border-slate-100">
                                    <p class="font-semibold text-slate-800 text-xs truncate">{{ $user->name }}</p>
                                    <span class="inline-block px-1.5 py-0.5 mt-1 text-[10px] font-bold rounded-md uppercase bg-primary-50 text-primary-600">
                                        {{ $roleName }}
                                    </span>
                                </div>
                                <a href="{{ $dashboardRoute }}" class="block px-4 py-2 text-slate-700 hover:bg-slate-50"><i class="fa-solid fa-gauge mr-2 text-slate-400"></i>Panel {{ ucfirst($roleName) }}</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50"><i class="fa-solid fa-right-from-bracket mr-2"></i>Keluar</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded-xl text-white bg-primary-600 hover:bg-primary-700 shadow-sm shadow-primary-500/25 transition-all">
                            <i class="fa-solid fa-right-to-bracket mr-2"></i> Masuk
                        </a>
                    @endauth
                </div>

            </div>
        </div>
    </header>

    <!-- Flash Notification Banners -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if (session('success'))
            <div class="flex items-center p-4 mb-4 text-emerald-800 rounded-xl bg-emerald-50 border border-emerald-200" role="alert">
                <i class="fa-solid fa-circle-check text-lg mr-3"></i>
                <div class="text-sm font-medium">{{ session('success') }}</div>
            </div>
        @endif

        @if (session('error'))
            <div class="flex items-center p-4 mb-4 text-rose-800 rounded-xl bg-rose-50 border border-rose-200" role="alert">
                <i class="fa-solid fa-triangle-exclamation text-lg mr-3"></i>
                <div class="text-sm font-medium">{{ session('error') }}</div>
            </div>
        @endif

        @if (session('info'))
            <div class="flex items-center p-4 mb-4 text-sky-800 rounded-xl bg-sky-50 border border-sky-200" role="alert">
                <i class="fa-solid fa-circle-info text-lg mr-3"></i>
                <div class="text-sm font-medium">{{ session('info') }}</div>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="grow">
        @yield('content')
    </main>

    <!-- Global Footer -->
    <footer class="bg-slate-900 text-slate-400 text-sm mt-16 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-primary-600 flex items-center justify-center text-white">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <span class="text-lg font-bold text-white">SMA GALATIA</span>
                    </div>
                    <p class="text-slate-400 text-sm max-w-sm mb-4 leading-relaxed">
                        Membentuk generasi unggul berkarakter, beriman, dan menguasai ilmu pengetahuan serta teknologi untuk masa depan gemilang.
                    </p>
                    <div class="flex items-center gap-3 text-slate-400">
                        <a href="#" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-primary-600 hover:text-white flex items-center justify-center transition"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-primary-600 hover:text-white flex items-center justify-center transition"><i class="fa-brands fa-youtube"></i></a>
                        <a href="#" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-primary-600 hover:text-white flex items-center justify-center transition"><i class="fa-brands fa-facebook"></i></a>
                    </div>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-3">Tautan Cepat</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('home') }}#profil" class="hover:text-white transition">Profil Sekolah</a></li>
                        <li><a href="{{ route('home') }}#fasilitas" class="hover:text-white transition">Fasilitas & Lab</a></li>
                        <li><a href="{{ route('home') }}#berita" class="hover:text-white transition">Warta & Berita</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition">Portal CBT Siswa</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-3">Kontak Sekolah</h4>
                    <p class="text-xs text-slate-400 leading-relaxed mb-1"><i class="fa-solid fa-location-dot mr-1.5 text-primary-400"></i> Jl. Pendidikan No. 45, Kompleks SMA Galatia</p>
                    <p class="text-xs text-slate-400 leading-relaxed mb-1"><i class="fa-solid fa-phone mr-1.5 text-primary-400"></i> (021) 8765-4321</p>
                    <p class="text-xs text-slate-400 leading-relaxed"><i class="fa-solid fa-envelope mr-1.5 text-primary-400"></i> info@galatia.sch.id</p>
                </div>
            </div>
            <div class="border-t border-slate-800 pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} SMA GALATIA. Hak Cipta Dilindungi Undang-Undang.</p>
                <p class="mt-2 sm:mt-0">Sistem Informasi Akademik & Portal CBT Mobile-Friendly</p>
            </div>
        </div>
    </footer>

</body>
</html>
