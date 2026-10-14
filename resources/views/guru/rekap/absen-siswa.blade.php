@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/80 mb-2">
                <i class="fa-solid fa-calendar-check"></i>
                <span>Laporan Kehadiran</span>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Rekap Presensi Siswa</h1>
            <p class="text-sm text-slate-500 mt-0.5">Laporan rekapitulasi presensi siswa per kelas dan periode bulanan.</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-bold text-sm shadow-md transition cursor-pointer">
                <i class="fa-solid fa-print"></i>
                <span>Cetak / PDF</span>
            </button>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 mb-8 print:hidden">
        <form method="GET" action="{{ route('guru.rekap.absen-siswa') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">
            <div class="sm:col-span-5">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pilih Kelas</label>
                <select name="kelas_id" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm focus:outline-hidden focus:ring-2 focus:ring-amber-500 bg-slate-50 font-semibold text-slate-700">
                    @forelse ($kelasYangDiajar as $k)
                        <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }} (Tingkat {{ $k->tingkat }})
                        </option>
                    @empty
                        <option value="">Belum ada jadwal kelas yang diajar</option>
                    @endforelse
                </select>
            </div>

            <div class="sm:col-span-5">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Bulan Periode</label>
                <input type="month" name="bulan" value="{{ $bulan }}" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm focus:outline-hidden focus:ring-2 focus:ring-amber-500 bg-slate-50 font-semibold text-slate-700">
            </div>

            <div class="sm:col-span-2 flex gap-2">
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-sm shadow-md shadow-amber-500/20 transition cursor-pointer">
                    <i class="fa-solid fa-filter"></i>
                    <span>Tampilkan</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Stats Bar -->
    @php
        $sumHadir     = $rekapAbsen->sum('hadir');
        $sumSakit     = $rekapAbsen->sum('sakit');
        $sumIzin      = $rekapAbsen->sum('izin');
        $sumAlfa      = $rekapAbsen->sum('alfa');
        $totalRecord  = $sumHadir + $sumSakit + $sumIzin + $sumAlfa;
        $overallPersen = $totalRecord > 0 ? round(($sumHadir / $totalRecord) * 100, 1) : 0;
    @endphp

    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3.5 mb-8">
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Siswa</p>
            <p class="text-2xl font-extrabold text-slate-800 mt-1">{{ $siswaList->count() }}</p>
        </div>
        <div class="bg-emerald-50/70 p-4 rounded-2xl border border-emerald-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider">Total Hadir</p>
            <p class="text-2xl font-extrabold text-emerald-700 mt-1">{{ $sumHadir }}</p>
        </div>
        <div class="bg-amber-50/70 p-4 rounded-2xl border border-amber-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-amber-600 uppercase tracking-wider">Total Sakit</p>
            <p class="text-2xl font-extrabold text-amber-700 mt-1">{{ $sumSakit }}</p>
        </div>
        <div class="bg-sky-50/70 p-4 rounded-2xl border border-sky-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-sky-600 uppercase tracking-wider">Total Izin</p>
            <p class="text-2xl font-extrabold text-sky-700 mt-1">{{ $sumIzin }}</p>
        </div>
        <div class="bg-rose-50/70 p-4 rounded-2xl border border-rose-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-rose-600 uppercase tracking-wider">Total Alfa</p>
            <p class="text-2xl font-extrabold text-rose-700 mt-1">{{ $sumAlfa }}</p>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-base font-extrabold text-slate-800">
                    Rekap Kehadiran Kelas {{ $kelas ? $kelas->nama_kelas : '-' }}
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">
                    Periode: {{ \Carbon\Carbon::parse($tglMulai)->locale('id')->isoFormat('MMMM Y') }}
                </p>
            </div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                <i class="fa-solid fa-chart-pie"></i>
                <span>Kehadiran Kelas: {{ $overallPersen }}%</span>
            </div>
        </div>

        @if ($rekapAbsen->isEmpty())
            <div class="text-center py-16 px-4">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4 text-2xl">
                    <i class="fa-solid fa-users-slash"></i>
                </div>
                <h3 class="text-base font-bold text-slate-700">Tidak Ada Data Siswa</h3>
                <p class="text-sm text-slate-400 mt-1">Pilih kelas yang valid untuk melihat rekap presensi.</p>
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
                                $totMasuk = $row['hadir'];
                                $totSemua = $totMasuk + $row['sakit'] + $row['izin'] + $row['alfa'];
                                $persen = $totSemua > 0 ? round(($totMasuk / $totSemua) * 100, 1) : 0;
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
                                <td class="px-6 py-4 text-center font-bold text-slate-700">{{ $totMasuk }} / {{ $totSemua }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        <span class="font-extrabold {{ $persen >= 80 ? 'text-emerald-700' : ($persen >= 60 ? 'text-amber-700' : 'text-rose-700') }}">
                                            {{ $persen }}%
                                        </span>
                                    </div>
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
