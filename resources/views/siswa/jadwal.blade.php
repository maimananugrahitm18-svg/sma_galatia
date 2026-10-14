@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-1">
        <a href="{{ route('siswa.dashboard') }}" class="hover:text-primary-600">Portal Siswa</a>
        <span>/</span>
        <span class="text-slate-800">Jadwal Pelajaran Kelas</span>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Jadwal Pelajaran Mingguan</h1>
            <p class="text-xs sm:text-sm text-slate-500">
                Kelas: <span class="font-bold text-primary-600">{{ $siswa->kelas->nama_kelas ?? 'Belum Terdaftar di Kelas' }}</span>
            </p>
        </div>
    </div>

    @if(!$siswa->kelas_id)
        <div class="p-6 bg-amber-50 rounded-3xl border border-amber-200 text-amber-800 text-sm">
            <i class="fa-solid fa-circle-exclamation mr-2"></i> Akun Anda belum ditempatkan ke dalam rombongan belajar kelas. Silakan hubungi Tata Usaha (Admin).
        </div>
    @else
        <!-- Grouped by Day Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $hariOrder = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
            @endphp

            @foreach ($hariOrder as $h)
                @php
                    $jadwalHariIni = $jadwal->where('hari', $h);
                @endphp
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-2xs flex flex-col">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                        <h3 class="font-bold text-slate-900 capitalize text-base flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full {{ $jadwalHariIni->isNotEmpty() ? 'bg-primary-600' : 'bg-slate-300' }}"></span>
                            {{ ucfirst($h) }}
                        </h3>
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-md {{ $jadwalHariIni->isNotEmpty() ? 'bg-primary-50 text-primary-700' : 'bg-slate-100 text-slate-400' }}">
                            {{ $jadwalHariIni->count() }} Pelajaran
                        </span>
                    </div>

                    <div class="space-y-3 grow">
                        @forelse ($jadwalHariIni as $j)
                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-mono font-bold text-primary-700 bg-white px-2 py-0.5 rounded-md border border-slate-200">
                                        {{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}
                                    </span>
                                    <span class="text-[11px] text-slate-500 font-medium">Ruang: {{ $j->ruangan ?? 'R-Umum' }}</span>
                                </div>
                                <h4 class="text-sm font-bold text-slate-900 mt-1">{{ $j->mataPelajaran->nama_mapel }}</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Guru: {{ $j->guru->nama_lengkap }}</p>
                            </div>
                        @empty
                            <div class="text-center py-8 text-xs text-slate-400">
                                Tidak ada pelajaran pada hari {{ ucfirst($h) }}.
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
