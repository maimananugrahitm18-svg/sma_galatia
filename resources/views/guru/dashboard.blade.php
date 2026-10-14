@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-8 py-8">
    
    <!-- Hero Header Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-navy-900 via-indigo-950 to-primary-900 p-6 sm:p-8 text-white shadow-xl mb-8 border border-slate-800">
        <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-sky-200 text-xs font-bold uppercase tracking-wider mb-3 backdrop-blur-xs border border-white/10">
                    <i class="fa-solid fa-chalkboard-user text-sky-400"></i>
                    <span>Portal Guru Pengajar &bull; SMA Galatia</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">Halo, {{ $guru->nama_lengkap }}</h1>
                <p class="text-xs sm:text-sm text-indigo-200/80 mt-1">
                    NIP: <span class="font-mono text-white font-semibold">{{ $guru->nip ?? 'Belum Diisi' }}</span> &bull; Kelola jadwal mengajar, input presensi siswa, dan pantau hasil ulangan harian.
                </p>
            </div>

            <!-- Quick Action Buttons -->
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('guru.absen.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-extrabold shadow-lg shadow-amber-500/25 transition-all transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-clipboard-user text-sm"></i>
                    <span>Absen Siswa</span>
                </a>
                <a href="{{ route('guru.jadwal.saya') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 backdrop-blur-xs transition">
                    <i class="fa-solid fa-calendar"></i>
                    <span>Jadwal Saya</span>
                </a>
                <a href="{{ route('guru.ujian.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-md shadow-indigo-600/30 transition">
                    <i class="fa-solid fa-file-circle-plus"></i>
                    <span>Buat Ulangan Harian</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 2 Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left Column: Mengajar Hari Ini -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>Mengajar Hari Ini</span>
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5 capitalize">{{ now()->locale('id')->isoFormat('dddd') }}</p>
                    </div>
                    <a href="{{ route('guru.jadwal.saya') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 hover:underline">
                        Jadwal Lengkap &rarr;
                    </a>
                </div>

                <div class="space-y-3.5">
                    @forelse ($jadwalHariIni as $j)
                        <div class="p-4 rounded-2xl bg-slate-50/70 hover:bg-slate-50 border border-slate-200/70 flex items-center justify-between transition">
                            <div>
                                <span class="text-xs font-black text-indigo-700 bg-indigo-100 px-2.5 py-1 rounded-lg">
                                    {{ $j->kelas->nama_kelas }}
                                </span>
                                <h4 class="text-sm font-bold text-slate-900 mt-2">{{ $j->mataPelajaran->nama_mapel }}</h4>
                                <p class="text-xs text-slate-400 mt-0.5">Ruang: {{ $j->ruangan ?? 'R-Reguler' }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-mono font-bold text-slate-700 bg-white px-2.5 py-1.5 rounded-xl border border-slate-200 shadow-2xs block mb-2">
                                    {{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}
                                </span>
                                <a href="{{ route('guru.absen.kelas', $j->id) }}" class="inline-flex items-center gap-1 text-[11px] font-extrabold text-amber-700 bg-amber-100/80 hover:bg-amber-200/80 px-2.5 py-1 rounded-lg transition">
                                    <i class="fa-solid fa-clipboard-user"></i> Absen
                                </a>
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
        </div>

        <!-- Right Column: Ulangan Harian CBT Aktif -->
        <div class="lg:col-span-7 space-y-6">
            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                            <i class="fa-solid fa-laptop-code text-indigo-600"></i>
                            <span>Ulangan Harian & Evaluasi CBT</span>
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5">Paket soal yang telah Anda buat</p>
                    </div>
                    <a href="{{ route('guru.ujian.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 hover:underline">
                        Lihat Semua &rarr;
                    </a>
                </div>

                <div class="space-y-3.5">
                    @forelse ($ujianSaya as $u)
                        <div class="p-4 rounded-2xl bg-slate-50/70 hover:bg-slate-50 border border-slate-200/70 flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition">
                            <div>
                                <div class="flex items-center gap-2 mb-1.5">
                                    <span class="text-[10px] font-black text-indigo-700 bg-indigo-100 px-2 py-0.5 rounded-md uppercase">
                                        {{ $u->mataPelajaran->nama_mapel }}
                                    </span>
                                    <span class="text-xs font-medium text-slate-500">&bull; {{ $u->durasi_menit }} Menit</span>
                                </div>
                                <h4 class="text-sm font-bold text-slate-900 leading-snug">{{ $u->judul }}</h4>
                                <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500 mt-1">
                                    <span>Kelas: 
                                        @foreach($u->kelas as $k)
                                            <span class="font-bold text-slate-700">{{ $k->nama_kelas }}</span>{{ !$loop->last ? ',' : '' }}
                                        @endforeach
                                    </span>
                                    <span>&bull; {{ $u->pesertaUjian->count() }} siswa mengumpulkan</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <a href="{{ route('guru.ujian.detail', $u->id) }}" class="px-3.5 py-2 text-xs font-bold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-100 shadow-2xs transition">
                                    <i class="fa-solid fa-list-check mr-1 text-slate-500"></i> Soal ({{ $u->soal->count() ?? 0 }})
                                </a>
                                <a href="{{ route('guru.ujian.rekap', $u->id) }}" class="px-3.5 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl shadow-xs transition">
                                    <i class="fa-solid fa-chart-simple mr-1"></i> Rekap Nilai
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <i class="fa-solid fa-folder-open text-4xl text-slate-300 mb-2 block"></i>
                            <p class="text-xs font-bold text-slate-500">Anda belum membuat ulangan harian.</p>
                            <a href="{{ route('guru.ujian.index') }}" class="inline-block mt-2 text-xs font-bold text-indigo-600 hover:underline">Buat Paket Soal Baru</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
