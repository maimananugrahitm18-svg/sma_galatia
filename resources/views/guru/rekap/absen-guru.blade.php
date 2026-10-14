@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-sky-50 text-sky-700 border border-sky-200/80 mb-2">
                <i class="fa-solid fa-clipboard-user"></i>
                <span>Laporan Kehadiran Mandiri Guru</span>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Rekap Absen Guru (Kehadiran & Jurnal)</h1>
            <p class="text-sm text-slate-500 mt-0.5">Laporan rekapitulasi presensi harian mandiri Anda dan jurnal pelaksanaan sesi tatap muka di kelas.</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-bold text-sm shadow-md transition cursor-pointer">
                <i class="fa-solid fa-print"></i>
                <span>Cetak / Ekspor PDF</span>
            </button>
        </div>
    </div>

    <!-- Filter Bulan -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 mb-8 print:hidden">
        <form method="GET" action="{{ route('guru.rekap.absen-guru') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">
            <div class="sm:col-span-9">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pilih Bulan Periode</label>
                <input type="month" name="bulan" value="{{ $bulan }}" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm focus:outline-hidden focus:ring-2 focus:ring-sky-500 bg-slate-50 font-semibold text-slate-700">
            </div>

            <div class="sm:col-span-3 flex gap-2">
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm shadow-md shadow-sky-500/20 transition cursor-pointer">
                    <i class="fa-solid fa-filter"></i>
                    <span>Tampilkan</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-6 gap-3 mb-8">
        <div class="bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Hari</p>
            <p class="text-2xl font-extrabold text-slate-800 mt-1">{{ $statsHarian['total'] }}</p>
        </div>
        <div class="bg-emerald-50/70 p-3.5 rounded-2xl border border-emerald-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider">Hadir</p>
            <p class="text-2xl font-extrabold text-emerald-700 mt-1">{{ $statsHarian['hadir'] }}</p>
        </div>
        <div class="bg-amber-50/70 p-3.5 rounded-2xl border border-amber-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-amber-600 uppercase tracking-wider">Sakit</p>
            <p class="text-2xl font-extrabold text-amber-700 mt-1">{{ $statsHarian['sakit'] }}</p>
        </div>
        <div class="bg-sky-50/70 p-3.5 rounded-2xl border border-sky-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-sky-600 uppercase tracking-wider">Izin</p>
            <p class="text-2xl font-extrabold text-sky-700 mt-1">{{ $statsHarian['izin'] }}</p>
        </div>
        <div class="bg-rose-50/70 p-3.5 rounded-2xl border border-rose-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-rose-600 uppercase tracking-wider">Alfa</p>
            <p class="text-2xl font-extrabold text-rose-700 mt-1">{{ $statsHarian['alfa'] }}</p>
        </div>
        <div class="bg-indigo-50/70 p-3.5 rounded-2xl border border-indigo-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-indigo-600 uppercase tracking-wider">% Kehadiran</p>
            <p class="text-2xl font-extrabold text-indigo-700 mt-1">{{ $statsHarian['persen'] }}%</p>
        </div>
    </div>

    <!-- Section 1: Presensi Harian Guru Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-extrabold text-slate-800">1. Rekapitulasi Presensi Harian Guru</h2>
                <p class="text-xs text-slate-400 mt-0.5">Periode: {{ \Carbon\Carbon::parse($tglMulai)->locale('id')->isoFormat('MMMM Y') }}</p>
            </div>
            <a href="{{ route('guru.kehadiran.index') }}" class="text-xs font-bold text-sky-600 hover:text-sky-700 print:hidden">
                <span>Input Absen Hari Ini &rarr;</span>
            </a>
        </div>

        @if ($presensiHarian->isEmpty())
            <div class="text-center py-12 px-4">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 text-xl">
                    <i class="fa-solid fa-calendar-xmark"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-700">Belum Ada Presensi Harian di Bulan Ini</h3>
                <p class="text-xs text-slate-400 mt-1">Lakukan check-in presensi harian pada menu Absen Saya.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3.5 w-12 text-center">No</th>
                            <th class="px-6 py-3.5">Tanggal</th>
                            <th class="px-6 py-3.5 text-center">Jam Masuk</th>
                            <th class="px-6 py-3.5 text-center">Status</th>
                            <th class="px-6 py-3.5">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($presensiHarian as $idx => $p)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-6 py-3.5 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                                <td class="px-6 py-3.5">
                                    <div class="font-bold text-slate-900">
                                        {{ \Carbon\Carbon::parse($p->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                                    </div>
                                    <div class="text-xs text-slate-400 font-mono">{{ $p->tanggal->format('Y-m-d') }}</div>
                                </td>
                                <td class="px-6 py-3.5 text-center font-mono text-xs font-semibold text-slate-700">
                                    {{ $p->jam_masuk ?: '-' }}
                                </td>
                                <td class="px-6 py-3.5 text-center">
                                    @php
                                        $badges = [
                                            'hadir' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'sakit' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'izin'  => 'bg-sky-50 text-sky-700 border-sky-200',
                                            'alfa'  => 'bg-rose-50 text-rose-700 border-rose-200',
                                        ];
                                    @endphp
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-black uppercase border {{ $badges[$p->status] ?? 'bg-slate-100 text-slate-600' }}">
                                        {{ $p->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 text-xs text-slate-600">
                                    {{ $p->keterangan ?: '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Section 2: Jurnal Mengajar di Kelas -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-extrabold text-slate-800">2. Jurnal Sesi Tatap Muka di Kelas</h2>
                <p class="text-xs text-slate-400 mt-0.5">Daftar kelas yang diajar dan presensi siswa yang telah diinput.</p>
            </div>
            <span class="text-xs font-bold text-slate-600 bg-slate-100 px-3 py-1 rounded-lg">
                Total: {{ $totalSesi }} Sesi Selesai
            </span>
        </div>

        @if ($logMengajar->isEmpty())
            <div class="text-center py-12 px-4">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 text-xl">
                    <i class="fa-solid fa-chalkboard-user"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-700">Belum Ada Sesi Mengajar di Bulan Ini</h3>
                <p class="text-xs text-slate-400 mt-1">Presensi siswa di kelas akan muncul otomatis di sini.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3.5 w-12 text-center">No</th>
                            <th class="px-6 py-3.5">Tanggal Mengajar</th>
                            <th class="px-6 py-3.5">Kelas</th>
                            <th class="px-6 py-3.5">Mata Pelajaran</th>
                            <th class="px-6 py-3.5">Jam Mengajar</th>
                            <th class="px-6 py-3.5 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($logMengajar as $idx => $log)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-6 py-3.5 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                                <td class="px-6 py-3.5">
                                    <div class="font-bold text-slate-900">
                                        {{ \Carbon\Carbon::parse($log->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                                    </div>
                                    <div class="text-xs text-slate-400 font-mono">{{ $log->tanggal }}</div>
                                </td>
                                <td class="px-6 py-3.5">
                                    <span class="inline-flex px-2 py-0.5 rounded text-xs font-bold bg-slate-100 text-slate-700">
                                        {{ $log->kelas->nama_kelas ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 font-semibold text-slate-800">
                                    {{ $log->jadwal->mataPelajaran->nama_mapel ?? '-' }}
                                </td>
                                <td class="px-6 py-3.5 text-xs font-mono text-slate-500">
                                    @if ($log->jadwal)
                                        {{ substr($log->jadwal->jam_mulai, 0, 5) }} - {{ substr($log->jadwal->jam_selesai, 0, 5) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-3.5 text-center">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-check text-[10px]"></i>
                                        <span>Terlaksana</span>
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection
