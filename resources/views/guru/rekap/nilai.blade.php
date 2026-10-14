@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200/80 mb-2">
                <i class="fa-solid fa-chart-line"></i>
                <span>Laporan Akademik Guru</span>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Rekapitulasi Nilai Siswa</h1>
            <p class="text-sm text-slate-500 mt-0.5">Pantau ringkasan hasil ulangan harian, statistik ketuntasan, dan capaian siswa.</p>
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
        <form method="GET" action="{{ route('guru.rekap.nilai') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">
            <div class="sm:col-span-5">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Filter Kelas</label>
                <select name="kelas_id" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm focus:outline-hidden focus:ring-2 focus:ring-purple-500 bg-slate-50 font-semibold text-slate-700">
                    <option value="">-- Semua Kelas --</option>
                    @foreach ($kelasList as $k)
                        <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }} (Tingkat {{ $k->tingkat }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-5">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Filter Mata Pelajaran</label>
                <select name="mapel_id" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm focus:outline-hidden focus:ring-2 focus:ring-purple-500 bg-slate-50 font-semibold text-slate-700">
                    <option value="">-- Semua Mata Pelajaran --</option>
                    @foreach ($mapelList as $m)
                        <option value="{{ $m->id }}" {{ request('mapel_id') == $m->id ? 'selected' : '' }}>
                            {{ $m->nama_mapel }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-2 flex gap-2">
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-sm shadow-md shadow-purple-500/20 transition cursor-pointer">
                    <i class="fa-solid fa-filter"></i>
                    <span>Terapkan</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider">Total Ujian Dibuat</span>
                <i class="fa-solid fa-file-signature text-purple-500 text-lg"></i>
            </div>
            <p class="text-3xl font-extrabold text-slate-800">{{ $rekapData->count() }}</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider">Total Peserta Selesai</span>
                <i class="fa-solid fa-user-check text-emerald-500 text-lg"></i>
            </div>
            <p class="text-3xl font-extrabold text-slate-800">{{ $rekapData->sum('sudah_selesai') }}</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider">Rata-Rata Nilai</span>
                <i class="fa-solid fa-chart-pie text-sky-500 text-lg"></i>
            </div>
            @php
                $avgAll = $rekapData->where('sudah_selesai', '>', 0)->avg('rata_rata');
            @endphp
            <p class="text-3xl font-extrabold text-slate-800">{{ $avgAll ? round($avgAll, 1) : '-' }}</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider">Tingkat Ketuntasan</span>
                <i class="fa-solid fa-award text-amber-500 text-lg"></i>
            </div>
            @php
                $totalSelesai = $rekapData->sum('sudah_selesai');
                $totalLulus = $rekapData->sum('lulus');
                $persenKetuntasan = $totalSelesai > 0 ? round(($totalLulus / $totalSelesai) * 100, 1) : 0;
            @endphp
            <p class="text-3xl font-extrabold text-emerald-600">{{ $persenKetuntasan }}%</p>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-base font-extrabold text-slate-800">Daftar Rekap Nilai Ulangan</h2>
            <span class="text-xs font-semibold text-slate-400">{{ $rekapData->count() }} Data Ujian</span>
        </div>

        @if ($rekapData->isEmpty())
            <div class="text-center py-16 px-4">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4 text-2xl">
                    <i class="fa-solid fa-clipboard-list"></i>
                </div>
                <h3 class="text-base font-bold text-slate-700">Belum Ada Rekap Nilai</h3>
                <p class="text-sm text-slate-400 mt-1 max-w-md mx-auto">Buat ujian terlebih dahulu atau sesuaikan filter untuk melihat rekapitulasi nilai.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3.5">Judul Ujian & Mapel</th>
                            <th class="px-6 py-3.5">Kelas</th>
                            <th class="px-6 py-3.5 text-center">Partisipasi</th>
                            <th class="px-6 py-3.5 text-center">Rata-Rata</th>
                            <th class="px-6 py-3.5 text-center">Tertinggi</th>
                            <th class="px-6 py-3.5 text-center">Terendah</th>
                            <th class="px-6 py-3.5 text-center">Tuntas (>=70)</th>
                            <th class="px-6 py-3.5 text-right print:hidden">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($rekapData as $row)
                            @php
                                $u = $row['ujian'];
                                $persenSelesai = $row['total_peserta'] > 0 ? round(($row['sudah_selesai'] / $row['total_peserta']) * 100) : 0;
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900">{{ $u->judul }}</div>
                                    <div class="text-xs text-purple-600 font-semibold mt-0.5">{{ $u->mataPelajaran->nama_mapel ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse ($u->kelas as $k)
                                            <span class="inline-flex px-2 py-0.5 rounded-md text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                                {{ $k->nama_kelas }}
                                            </span>
                                        @empty
                                            <span class="text-xs text-slate-400 italic">Semua</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-extrabold text-slate-800">{{ $row['sudah_selesai'] }}</span>
                                    <span class="text-xs text-slate-400">/ {{ $row['total_peserta'] }}</span>
                                    <div class="w-16 bg-slate-200 rounded-full h-1.5 mx-auto mt-1 overflow-hidden">
                                        <div class="bg-purple-600 h-1.5 rounded-full" style="width: {{ $persenSelesai }}%"></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-black {{ $row['rata_rata'] >= 70 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                        {{ $row['rata_rata'] ? round($row['rata_rata'], 1) : '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-slate-800">
                                    {{ $row['nilai_max'] ?: '-' }}
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-slate-800">
                                    {{ $row['nilai_min'] ?: '-' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-700">
                                        <i class="fa-solid fa-check"></i>
                                        <span>{{ $row['lulus'] }} Siswa</span>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right print:hidden">
                                    <a href="{{ route('guru.ujian.rekap', $u->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-purple-50 text-purple-700 hover:bg-purple-100 border border-purple-200 transition">
                                        <i class="fa-solid fa-list-check"></i>
                                        <span>Detail Siswa</span>
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
@endsection
