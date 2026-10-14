@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-8 py-8">
    
    <!-- Hero Header Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-navy-900 via-primary-900 to-indigo-950 p-6 sm:p-8 text-white shadow-xl mb-8 border border-slate-800">
        <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-40 top-0 w-48 h-48 bg-sky-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-indigo-200 text-xs font-bold uppercase tracking-wider mb-3 backdrop-blur-xs border border-white/10">
                    <i class="fa-solid fa-shield-halved text-indigo-400"></i>
                    <span>Control Panel Administrator &bull; SMA Galatia</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">Selamat Datang, {{ Auth::user()->name }}</h1>
                <p class="text-xs sm:text-sm text-indigo-200/80 mt-1 max-w-xl">
                    Pusat kendali operasional sekolah — kelola data guru, siswa, jadwal mengajar, dan rekap presensi secara terpusat.
                </p>
            </div>

            <!-- Quick Action Buttons -->
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('admin.absen.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-extrabold shadow-lg shadow-amber-500/25 transition-all transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-clipboard-user text-sm"></i>
                    <span>Absen Siswa</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 backdrop-blur-xs transition">
                    <i class="fa-solid fa-users-gear"></i>
                    <span>Kelola Akun</span>
                </a>
                <a href="{{ route('admin.jadwal.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-md shadow-indigo-600/30 transition">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span>Jadwal Mengajar</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Metric Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-3">
                <i class="fa-solid fa-chalkboard-user text-lg"></i>
            </div>
            <p class="text-2xl font-black text-slate-900 tracking-tight">{{ $stats['total_guru'] }}</p>
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Guru Pengampu</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3">
                <i class="fa-solid fa-user-graduate text-lg"></i>
            </div>
            <p class="text-2xl font-black text-slate-900 tracking-tight">{{ $stats['total_siswa'] }}</p>
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Siswa Terdaftar</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center mb-3">
                <i class="fa-solid fa-door-open text-lg"></i>
            </div>
            <p class="text-2xl font-black text-slate-900 tracking-tight">{{ $stats['total_kelas'] }}</p>
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Rombel Kelas</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition">
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center mb-3">
                <i class="fa-solid fa-book-bookmark text-lg"></i>
            </div>
            <p class="text-2xl font-black text-slate-900 tracking-tight">{{ $stats['total_mapel'] }}</p>
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Mata Pelajaran</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-3">
                <i class="fa-solid fa-calendar-check text-lg"></i>
            </div>
            <p class="text-2xl font-black text-slate-900 tracking-tight">{{ $stats['total_jadwal'] }}</p>
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Jadwal Mengajar</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition">
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center mb-3">
                <i class="fa-solid fa-laptop-code text-lg"></i>
            </div>
            <p class="text-2xl font-black text-slate-900 tracking-tight">{{ $stats['total_ujian'] }}</p>
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Paket Ujian CBT</p>
        </div>
    </div>

    <!-- 2 Column Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Jadwal Pelajaran Hari Ini -->
        <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Jadwal Mengajar Hari Ini</span>
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5 capitalize">{{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
                </div>
                <a href="{{ route('admin.jadwal.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 hover:underline">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="space-y-3">
                @forelse ($jadwalHariIni as $j)
                    <div class="p-4 rounded-2xl bg-slate-50/70 hover:bg-slate-50 border border-slate-200/70 flex items-center justify-between transition">
                        <div class="flex items-start gap-3">
                            <span class="text-xs font-black text-indigo-700 bg-indigo-100 px-2.5 py-1 rounded-lg mt-0.5">
                                {{ $j->kelas->nama_kelas }}
                            </span>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900 leading-snug">{{ $j->mataPelajaran->nama_mapel }}</h4>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    <i class="fa-solid fa-chalkboard-user mr-1 text-slate-400"></i>{{ $j->guru->nama_lengkap }} &bull; Ruang {{ $j->ruangan ?? 'Reguler' }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right shrink-0 pl-3">
                            <span class="text-xs font-mono font-bold text-slate-700 bg-white px-2.5 py-1.5 rounded-xl border border-slate-200 shadow-2xs block">
                                {{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <i class="fa-regular fa-calendar-xmark text-4xl text-slate-300 mb-2 block"></i>
                        <p class="text-xs font-bold text-slate-500">Tidak ada jadwal mengajar pada hari ini.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Ujian CBT Terbaru -->
        <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                        <i class="fa-solid fa-file-pen text-indigo-600"></i>
                        <span>Ulangan Harian & CBT Aktif</span>
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Daftar paket evaluasi soal terbaru</p>
                </div>
                <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-slate-100 text-slate-600">
                    Aktif
                </span>
            </div>

            <div class="space-y-3">
                @forelse ($ujianTerbaru as $u)
                    <div class="p-4 rounded-2xl bg-slate-50/70 hover:bg-slate-50 border border-slate-200/70 flex items-center justify-between transition">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-[10px] font-black text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-md uppercase">
                                    {{ $u->tipe_ujian }}
                                </span>
                                <span class="text-xs font-semibold text-slate-600">{{ $u->mataPelajaran->nama_mapel }}</span>
                            </div>
                            <h4 class="text-sm font-bold text-slate-900 leading-snug">{{ $u->judul }}</h4>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Guru: {{ $u->guru->nama_lengkap }} &bull; Durasi: {{ $u->durasi_menit }} Menit
                            </p>
                        </div>
                        <div class="text-right shrink-0 pl-3">
                            <span class="text-xs font-bold px-2.5 py-1 rounded-lg {{ $u->status === 'terbit' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-200 text-slate-700' }}">
                                {{ ucfirst($u->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <i class="fa-solid fa-folder-open text-4xl text-slate-300 mb-2 block"></i>
                        <p class="text-xs font-bold text-slate-500">Belum ada paket ujian yang diterbitkan.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
