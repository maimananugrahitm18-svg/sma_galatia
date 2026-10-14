@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200/80 mb-2">
                <i class="fa-solid fa-chart-pie"></i>
                <span>Laporan & Rekapitulasi Presensi</span>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Rekap Absensi Siswa (Sekolah)</h1>
            <p class="text-sm text-slate-500 mt-0.5">Laporan rekapitulasi kehadiran siswa seluruh jenjang kelas berdasarkan periode bulanan.</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-bold text-sm shadow-md transition cursor-pointer">
                <i class="fa-solid fa-print"></i>
                <span>Cetak / Ekspor PDF</span>
            </button>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 mb-8 print:hidden">
        <form method="GET" action="{{ route('admin.rekap.absen-siswa') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">
            <div class="sm:col-span-5">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pilih Kelas</label>
                <select name="kelas_id" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm focus:outline-hidden focus:ring-2 focus:ring-indigo-500 bg-slate-50 font-semibold text-slate-700">
                    @foreach ($kelasList as $k)
                        <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }} (Tingkat {{ $k->tingkat }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-5">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Bulan Periode</label>
                <input type="month" name="bulan" value="{{ $bulan }}" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm focus:outline-hidden focus:ring-2 focus:ring-indigo-500 bg-slate-50 font-semibold text-slate-700">
            </div>

            <div class="sm:col-span-2 flex gap-2">
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-md shadow-indigo-500/20 transition cursor-pointer">
                    <i class="fa-solid fa-filter"></i>
                    <span>Tampilkan</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Ringkasan Statistik -->
    <div class="grid grid-cols-2 sm:grid-cols-6 gap-3 mb-8">
        <div class="bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Siswa</p>
            <p class="text-2xl font-extrabold text-slate-800 mt-1">{{ $ringkasan['total_siswa'] }}</p>
        </div>
        <div class="bg-emerald-50/70 p-3.5 rounded-2xl border border-emerald-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider">Total Hadir</p>
            <p class="text-2xl font-extrabold text-emerald-700 mt-1">{{ $ringkasan['total_hadir'] }}</p>
        </div>
        <div class="bg-amber-50/70 p-3.5 rounded-2xl border border-amber-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-amber-600 uppercase tracking-wider">Sakit</p>
            <p class="text-2xl font-extrabold text-amber-700 mt-1">{{ $ringkasan['total_sakit'] }}</p>
        </div>
        <div class="bg-sky-50/70 p-3.5 rounded-2xl border border-sky-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-sky-600 uppercase tracking-wider">Izin</p>
            <p class="text-2xl font-extrabold text-sky-700 mt-1">{{ $ringkasan['total_izin'] }}</p>
        </div>
        <div class="bg-rose-50/70 p-3.5 rounded-2xl border border-rose-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-rose-600 uppercase tracking-wider">Alfa</p>
            <p class="text-2xl font-extrabold text-rose-700 mt-1">{{ $ringkasan['total_alfa'] }}</p>
        </div>
        <div class="bg-indigo-50/70 p-3.5 rounded-2xl border border-indigo-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-indigo-600 uppercase tracking-wider">% Kehadiran</p>
            <p class="text-2xl font-extrabold text-indigo-700 mt-1">{{ $ringkasan['persentase_kehadiran'] }}%</p>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-base font-extrabold text-slate-800">
                    Rekapitulasi Presensi — Kelas {{ $kelas ? $kelas->nama_kelas : '-' }}
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">
                    Periode: {{ \Carbon\Carbon::parse($tglMulai)->locale('id')->isoFormat('MMMM Y') }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.absen.index') }}?kelas_id={{ $kelasId }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition print:hidden">
                    <i class="fa-solid fa-calendar-day"></i>
                    <span>Input Harian</span>
                </a>
            </div>
        </div>

        @if ($rekapAbsen->isEmpty())
            <div class="text-center py-16 px-4">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4 text-2xl">
                    <i class="fa-solid fa-users-slash"></i>
                </div>
                <h3 class="text-base font-bold text-slate-700">Tidak Ada Siswa di Kelas Ini</h3>
                <p class="text-sm text-slate-400 mt-1">Pilih kelas yang memiliki siswa aktif.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3.5 w-12 text-center">No</th>
                            <th class="px-6 py-3.5">Nama Siswa</th>
                            <th class="px-6 py-3.5">NISN / NIS</th>
                            <th class="px-6 py-3.5 text-center text-emerald-700">Hadir</th>
                            <th class="px-6 py-3.5 text-center text-amber-700">Sakit</th>
                            <th class="px-6 py-3.5 text-center text-sky-700">Izin</th>
                            <th class="px-6 py-3.5 text-center text-rose-700">Alfa</th>
                            <th class="px-6 py-3.5 text-center">Total Kehadiran</th>
                            <th class="px-6 py-3.5 text-center">% Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($rekapAbsen as $index => $row)
                            @php
                                $s = $row['siswa'];
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-6 py-4 text-center font-bold text-slate-400">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-bold text-slate-900">{{ $s->user->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-xs font-semibold text-slate-500">{{ $s->nisn ?: ($s->nis ?: '-') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-black bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        {{ $row['hadir'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-black bg-amber-50 text-amber-700 border border-amber-200">
                                        {{ $row['sakit'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-black bg-sky-50 text-sky-700 border border-sky-200">
                                        {{ $row['izin'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-black {{ $row['alfa'] > 0 ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'text-slate-400' }}">
                                        {{ $row['alfa'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-slate-700">{{ $row['hadir'] }} / {{ $row['total'] }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-extrabold {{ $row['persentase'] >= 80 ? 'text-emerald-700' : ($row['persentase'] >= 60 ? 'text-amber-700' : 'text-rose-700') }}">
                                        {{ $row['persentase'] }}%
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
