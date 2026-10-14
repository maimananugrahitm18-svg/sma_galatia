@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-sky-50 text-sky-700 border border-sky-200/80 mb-2">
                <i class="fa-solid fa-chalkboard-user"></i>
                <span>Laporan Kedisiplinan & Kehadiran Guru</span>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Rekapitulasi Absensi Guru (Sekolah)</h1>
            <p class="text-sm text-slate-500 mt-0.5">Monitoring rekapitulasi kehadiran dewan guru (Hadir, Sakit, Izin, Alfa) dan pelaksanaan jam mengajar.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.absen-guru.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 font-bold text-sm border border-slate-200 shadow-xs transition print:hidden">
                <i class="fa-solid fa-calendar-day text-sky-600"></i>
                <span>Input Absen Hari Ini</span>
            </a>
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-bold text-sm shadow-md transition cursor-pointer">
                <i class="fa-solid fa-print"></i>
                <span>Cetak / Ekspor PDF</span>
            </button>
        </div>
    </div>

    <!-- Filter Bulan -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 mb-8 print:hidden">
        <form method="GET" action="{{ route('admin.rekap.absen-guru') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">
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

    <!-- Ringkasan Statistik Sekolah -->
    <div class="grid grid-cols-2 sm:grid-cols-6 gap-3 mb-8">
        <div class="bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Guru</p>
            <p class="text-2xl font-extrabold text-slate-800 mt-1">{{ $ringkasan['total_guru'] }}</p>
        </div>
        <div class="bg-emerald-50/70 p-3.5 rounded-2xl border border-emerald-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider">Total Hadir</p>
            <p class="text-2xl font-extrabold text-emerald-700 mt-1">{{ $ringkasan['total_hadir'] }}</p>
        </div>
        <div class="bg-amber-50/70 p-3.5 rounded-2xl border border-amber-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-amber-600 uppercase tracking-wider">Total Sakit</p>
            <p class="text-2xl font-extrabold text-amber-700 mt-1">{{ $ringkasan['total_sakit'] }}</p>
        </div>
        <div class="bg-sky-50/70 p-3.5 rounded-2xl border border-sky-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-sky-600 uppercase tracking-wider">Total Izin</p>
            <p class="text-2xl font-extrabold text-sky-700 mt-1">{{ $ringkasan['total_izin'] }}</p>
        </div>
        <div class="bg-rose-50/70 p-3.5 rounded-2xl border border-rose-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-rose-600 uppercase tracking-wider">Total Alfa</p>
            <p class="text-2xl font-extrabold text-rose-700 mt-1">{{ $ringkasan['total_alfa'] }}</p>
        </div>
        <div class="bg-indigo-50/70 p-3.5 rounded-2xl border border-indigo-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-indigo-600 uppercase tracking-wider">% Kehadiran</p>
            <p class="text-2xl font-extrabold text-indigo-700 mt-1">{{ $ringkasan['persentase'] }}%</p>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-base font-extrabold text-slate-800">Daftar Rekapitulasi Presensi Seluruh Guru</h2>
                <p class="text-xs text-slate-400 mt-0.5">Periode: {{ \Carbon\Carbon::parse($tglMulai)->locale('id')->isoFormat('MMMM Y') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-sky-50 text-sky-700 border border-sky-200">
                    <i class="fa-solid fa-chalkboard-user"></i>
                    <span>Total Sesi Mengajar: {{ $ringkasan['total_sesi'] }}</span>
                </span>
            </div>
        </div>

        @if ($rekapGuru->isEmpty())
            <div class="text-center py-16 px-4">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4 text-2xl">
                    <i class="fa-solid fa-user-slash"></i>
                </div>
                <h3 class="text-base font-bold text-slate-700">Belum Ada Data Guru</h3>
                <p class="text-sm text-slate-400 mt-1">Tambahkan akun guru untuk mulai merekam kehadiran.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3.5 w-12 text-center">No</th>
                            <th class="px-6 py-3.5">Nama Guru</th>
                            <th class="px-6 py-3.5">NIP</th>
                            <th class="px-6 py-3.5 text-center text-emerald-700">Hadir</th>
                            <th class="px-6 py-3.5 text-center text-amber-700">Sakit</th>
                            <th class="px-6 py-3.5 text-center text-sky-700">Izin</th>
                            <th class="px-6 py-3.5 text-center text-rose-700">Alfa</th>
                            <th class="px-6 py-3.5 text-center">% Kehadiran</th>
                            <th class="px-6 py-3.5 text-center">Sesi Mengajar</th>
                            <th class="px-6 py-3.5">Mapel & Kelas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($rekapGuru as $idx => $r)
                            @php
                                $g = $r['guru'];
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-6 py-4 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                                <td class="px-6 py-4 font-bold text-slate-900">{{ $g->user->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-xs font-semibold text-slate-500 font-mono">{{ $g->nip ?: '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-black bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        {{ $r['hadir'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-black bg-amber-50 text-amber-700 border border-amber-200">
                                        {{ $r['sakit'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-black bg-sky-50 text-sky-700 border border-sky-200">
                                        {{ $r['izin'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-black {{ $r['alfa'] > 0 ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'text-slate-400' }}">
                                        {{ $r['alfa'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-extrabold {{ $r['persentase'] >= 80 ? 'text-emerald-700' : ($r['persentase'] >= 60 ? 'text-amber-700' : 'text-rose-700') }}">
                                        {{ $r['persentase'] }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                        <span>{{ $r['total_sesi'] }}</span>
                                        <span class="text-[10px] text-slate-400">Sesi</span>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-semibold text-slate-800">{{ $r['mapel'] }}</div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">{{ $r['kelas'] }}</div>
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
