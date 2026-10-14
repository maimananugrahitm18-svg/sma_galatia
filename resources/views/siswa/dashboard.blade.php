@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-8 py-8">
    
    <!-- Hero Header Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-navy-900 via-primary-950 to-indigo-900 p-6 sm:p-8 text-white shadow-xl mb-8 border border-slate-800">
        <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-emerald-300 text-xs font-bold uppercase tracking-wider mb-3 backdrop-blur-xs border border-white/10">
                    <i class="fa-solid fa-graduation-cap text-emerald-400"></i>
                    <span>Portal Siswa &bull; SMA Galatia</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">Selamat Belajar, {{ Auth::user()->name }}</h1>
                <p class="text-xs sm:text-sm text-indigo-200/80 mt-1">
                    Kelas: <span class="font-bold text-white">{{ $siswa->kelas->nama_kelas ?? 'Belum Ditentukan' }}</span> &bull; 
                    NIS: <span class="font-mono text-emerald-300 font-bold">{{ $siswa->nis ?? '-' }}</span>
                </p>
            </div>

            <!-- Quick Action Buttons -->
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('siswa.absen.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-extrabold shadow-lg shadow-amber-500/25 transition-all transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-clipboard-check text-sm"></i>
                    <span>Absen / Kehadiran</span>
                </a>
                <a href="{{ route('siswa.jadwal.saya') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 backdrop-blur-xs transition">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span>Jadwal Pelajaran</span>
                </a>
                <a href="{{ route('siswa.ujian.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-md shadow-indigo-600/30 transition">
                    <i class="fa-solid fa-mobile-screen"></i>
                    <span>Ulangan Harian</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Active Exams Hero Alert (Mobile Ready Alert) -->
    @php
        $activeExams = $ujianTersedia->filter(function($u) {
            return $u->pesertaUjian->isEmpty() || $u->pesertaUjian->first()->status !== 'selesai';
        });
    @endphp

    @if($activeExams->isNotEmpty())
        <div class="mb-8 p-6 sm:p-7 rounded-3xl bg-gradient-to-r from-indigo-900 via-primary-900 to-purple-950 text-white shadow-xl relative overflow-hidden border border-indigo-700/50">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-extrabold uppercase tracking-wider mb-2 border border-emerald-500/30">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                        Ulangan Harian Sedang Berlangsung!
                    </span>
                    <h2 class="text-xl sm:text-2xl font-extrabold mt-1">{{ $activeExams->first()->judul }}</h2>
                    <p class="text-xs sm:text-sm text-indigo-200 mt-1">
                        Mapel: <strong class="text-white">{{ $activeExams->first()->mataPelajaran->nama_mapel }}</strong> &bull; Durasi: {{ $activeExams->first()->durasi_menit }} Menit &bull; Bisa dibuka langsung lewat HP!
                    </p>
                </div>
                <div class="shrink-0">
                    <a href="{{ route('siswa.ujian.kerjakan', $activeExams->first()->id) }}" class="inline-flex items-center gap-2.5 px-6 py-3.5 rounded-2xl bg-white text-slate-900 hover:bg-slate-100 font-extrabold text-sm shadow-xl transition-all transform hover:scale-105">
                        <i class="fa-solid fa-mobile-screen-button text-base text-indigo-600"></i>
                        <span>Kerjakan di HP Sekarang</span>
                    </a>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left: Jadwal Pelajaran Kelas Hari Ini -->
        <div class="lg:col-span-6 space-y-6">
            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>Jadwal Kelas Hari Ini</span>
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5 capitalize">{{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
                    </div>
                    <a href="{{ route('siswa.jadwal.saya') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 hover:underline">
                        Lihat Jadwal Penuh &rarr;
                    </a>
                </div>

                <div class="space-y-3.5">
                    @forelse ($jadwalHariIni as $j)
                        <div class="p-4 rounded-2xl bg-slate-50/70 hover:bg-slate-50 border border-slate-200/70 flex items-center justify-between transition">
                            <div>
                                <h4 class="text-sm font-bold text-slate-900 leading-snug">{{ $j->mataPelajaran->nama_mapel }}</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Guru: {{ $j->guru->nama_lengkap }}</p>
                                <p class="text-[11px] text-indigo-600 font-semibold mt-1">
                                    <i class="fa-solid fa-location-dot mr-1 text-indigo-400"></i> Ruang: {{ $j->ruangan ?? 'R-Reguler' }}
                                </p>
                            </div>
                            <div class="text-right shrink-0 pl-3">
                                <span class="text-xs font-mono font-bold text-slate-800 bg-white px-2.5 py-1.5 rounded-xl border border-slate-200 shadow-2xs block">
                                    {{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <i class="fa-regular fa-calendar-check text-4xl text-slate-300 mb-2 block"></i>
                            <p class="text-xs font-bold text-slate-500">Tidak ada mata pelajaran di kelas Anda hari ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right: Daftar Ulangan Harian Kelas -->
        <div class="lg:col-span-6 space-y-6">
            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                            <i class="fa-solid fa-laptop-code text-indigo-600"></i>
                            <span>Ulangan Harian & CBT Online</span>
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5">Daftar evaluasi belajar kelas Anda</p>
                    </div>
                    <a href="{{ route('siswa.ujian.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 hover:underline">
                        Lihat Semua &rarr;
                    </a>
                </div>

                <div class="space-y-3.5">
                    @forelse ($ujianTersedia as $u)
                        @php
                            $sesi = $u->pesertaUjian->first();
                            $isSelesai = $sesi && $sesi->status === 'selesai';
                        @endphp
                        <div class="p-4 rounded-2xl bg-slate-50/70 hover:bg-slate-50 border border-slate-200/70 flex items-center justify-between gap-4 transition">
                            <div>
                                <span class="text-[10px] font-black text-indigo-700 bg-indigo-100 px-2 py-0.5 rounded-md uppercase">
                                    {{ $u->mataPelajaran->nama_mapel }}
                                </span>
                                <h4 class="text-sm font-bold text-slate-900 mt-1.5 leading-snug">{{ $u->judul }}</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Durasi: {{ $u->durasi_menit }} Menit &bull; Guru: {{ $u->guru->user->name }}</p>
                            </div>
                            <div class="shrink-0">
                                @if ($isSelesai)
                                    <a href="{{ route('siswa.ujian.hasil', $u->id) }}" class="px-3.5 py-2 text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl hover:bg-emerald-100 transition block text-center shadow-2xs">
                                        Nilai: {{ number_format($sesi->total_nilai, 0) }}
                                    </a>
                                @else
                                    <a href="{{ route('siswa.ujian.kerjakan', $u->id) }}" class="px-4 py-2 text-xs font-extrabold text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl shadow-xs transition block text-center">
                                        Kerjakan
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <i class="fa-solid fa-face-smile text-4xl text-slate-300 mb-2 block"></i>
                            <p class="text-xs font-bold text-slate-500">Belum ada ulangan harian yang dijadwalkan untuk kelas Anda.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
