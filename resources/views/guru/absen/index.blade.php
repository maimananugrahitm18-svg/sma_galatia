@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/80 mb-2">
                <i class="fa-solid fa-clipboard-user"></i>
                <span>Presensi Kelas Saya</span>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Presensi Mengajar Guru</h1>
            <p class="text-sm text-slate-500 mt-0.5">Pilih jadwal kelas mengajar Anda hari ini untuk mengisi absensi siswa.</p>
        </div>
    </div>

    <!-- Jadwal Hari Ini Section -->
    <div class="mb-10">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-extrabold text-slate-800 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Jadwal Mengajar Hari Ini ({{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }})</span>
            </h2>
        </div>

        @if ($jadwalHariIni->isEmpty())
            <div class="bg-white rounded-2xl border border-slate-200/80 p-8 text-center shadow-xs">
                <i class="fa-solid fa-mug-hot text-4xl text-slate-300 mb-3 block"></i>
                <p class="font-bold text-slate-700">Tidak ada jadwal mengajar untuk hari ini</p>
                <p class="text-xs text-slate-400 mt-1">Anda bisa memilih kelas dari daftar semua jadwal mengajar di bawah jika ingin mencatat absensi.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($jadwalHariIni as $j)
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition p-5 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-extrabold bg-primary-50 text-primary-700">
                                    {{ $j->kelas->nama_kelas ?? 'Kelas' }}
                                </span>
                                <span class="text-xs font-bold text-slate-500">
                                    <i class="fa-regular fa-clock mr-1 text-slate-400"></i>
                                    {{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}
                                </span>
                            </div>
                            <h3 class="text-base font-extrabold text-slate-900 mb-1">
                                {{ $j->mataPelajaran->nama_mapel ?? 'Mata Pelajaran' }}
                            </h3>
                            <p class="text-xs text-slate-400 mb-4">
                                Ruang: {{ $j->ruangan ?? 'Kelas Reguler' }}
                            </p>
                        </div>

                        <a href="{{ route('guru.absen.kelas', $j->id) }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-sm shadow-sm transition">
                            <i class="fa-solid fa-clipboard-check"></i>
                            <span>Input Presensi Sekarang</span>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Semua Jadwal Mengajar Section -->
    <div>
        <h2 class="text-lg font-extrabold text-slate-800 mb-4">Semua Kelas yang Anda Ajar</h2>
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            @if ($semuaJadwal->isEmpty())
                <div class="p-8 text-center text-slate-400 text-sm">
                    Belum ada jadwal mengajar yang diplot oleh Admin.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 text-[11px] font-extrabold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                                <th class="py-3 px-4">Hari</th>
                                <th class="py-3 px-4">Jam</th>
                                <th class="py-3 px-4">Kelas</th>
                                <th class="py-3 px-4">Mata Pelajaran</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @foreach ($semuaJadwal as $j)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="py-3.5 px-4 font-bold text-slate-800 uppercase text-xs">{{ $j->hari }}</td>
                                    <td class="py-3.5 px-4 text-slate-500 text-xs">{{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}</td>
                                    <td class="py-3.5 px-4 font-bold text-primary-700">{{ $j->kelas->nama_kelas ?? '-' }}</td>
                                    <td class="py-3.5 px-4 font-semibold text-slate-700">{{ $j->mataPelajaran->nama_mapel ?? '-' }}</td>
                                    <td class="py-3.5 px-4 text-center">
                                        <a href="{{ route('guru.absen.kelas', $j->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-700 font-bold text-xs border border-amber-200 transition">
                                            <i class="fa-solid fa-clipboard-user"></i>
                                            <span>Buka Absensi</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
