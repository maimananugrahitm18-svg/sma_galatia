@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-sky-50 text-sky-700 border border-sky-200/80 mb-2">
                <i class="fa-solid fa-user-clock"></i>
                <span>Manajemen Presensi Harian Guru</span>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Presensi Harian Seluruh Guru</h1>
            <p class="text-sm text-slate-500 mt-0.5">Kelola dan rekam kehadiran seluruh dewan guru dan tenaga pendidik harian.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.rekap.absen-guru') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 font-bold text-sm border border-slate-200 shadow-xs transition">
                <i class="fa-solid fa-chart-pie text-sky-600"></i>
                <span>Lihat Rekap Bulanan Guru</span>
            </a>
        </div>
    </div>

    <!-- Filter Tanggal -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 mb-8">
        <form method="GET" action="{{ route('admin.absen-guru.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">
            <div class="sm:col-span-9">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pilih Tanggal Presensi</label>
                <input type="date" name="tanggal" value="{{ $tanggal }}" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm focus:outline-hidden focus:ring-2 focus:ring-sky-500 bg-slate-50 font-semibold text-slate-700">
            </div>

            <div class="sm:col-span-3 flex gap-2">
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm shadow-md shadow-sky-500/20 transition cursor-pointer">
                    <i class="fa-solid fa-filter"></i>
                    <span>Tampilkan Tanggal</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Stats Bar -->
    <div class="grid grid-cols-2 sm:grid-cols-6 gap-3.5 mb-8">
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Guru</p>
            <p class="text-2xl font-extrabold text-slate-800 mt-1">{{ $stats['total_guru'] }}</p>
        </div>
        <div class="bg-emerald-50/70 p-4 rounded-2xl border border-emerald-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider">Hadir</p>
            <p class="text-2xl font-extrabold text-emerald-700 mt-1">{{ $stats['hadir'] }}</p>
        </div>
        <div class="bg-amber-50/70 p-4 rounded-2xl border border-amber-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-amber-600 uppercase tracking-wider">Sakit</p>
            <p class="text-2xl font-extrabold text-amber-700 mt-1">{{ $stats['sakit'] }}</p>
        </div>
        <div class="bg-sky-50/70 p-4 rounded-2xl border border-sky-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-sky-600 uppercase tracking-wider">Izin</p>
            <p class="text-2xl font-extrabold text-sky-700 mt-1">{{ $stats['izin'] }}</p>
        </div>
        <div class="bg-rose-50/70 p-4 rounded-2xl border border-rose-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-rose-600 uppercase tracking-wider">Alfa</p>
            <p class="text-2xl font-extrabold text-rose-700 mt-1">{{ $stats['alfa'] }}</p>
        </div>
        <div class="bg-slate-100 p-4 rounded-2xl border border-slate-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Belum Absen</p>
            <p class="text-2xl font-extrabold text-slate-700 mt-1">{{ $stats['belum_absen'] }}</p>
        </div>
    </div>

    <!-- Form Input Presensi Guru -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <form action="{{ route('admin.absen-guru.store') }}" method="POST">
            @csrf
            <input type="hidden" name="tanggal" value="{{ $tanggal }}">

            <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-base font-extrabold text-slate-800">Daftar Kehadiran Dewan Guru</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Tanggal: {{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-extrabold text-sm shadow-md shadow-sky-600/20 transition cursor-pointer">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Simpan Presensi Guru</span>
                    </button>
                </div>
            </div>

            @if ($guruList->isEmpty())
                <div class="text-center py-16 px-4">
                    <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4 text-2xl">
                        <i class="fa-solid fa-users-slash"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-700">Belum Ada Akun Guru</h3>
                    <p class="text-sm text-slate-400 mt-1">Tambahkan akun guru terlebih dahulu untuk menginput absensi.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3.5 w-12 text-center">No</th>
                                <th class="px-6 py-3.5">Nama Guru & NIP</th>
                                <th class="px-6 py-3.5 text-center">Status Kehadiran</th>
                                <th class="px-6 py-3.5">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($guruList as $idx => $g)
                                @php
                                    $p = $presensiMap->get($g->id);
                                    $currentStatus = $p?->status ?? 'hadir';
                                @endphp
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="px-6 py-4 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-900">{{ $g->user->name ?? '-' }}</div>
                                        <div class="text-xs text-slate-400 font-mono">{{ $g->nip ?: 'NIP: -' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-1.5 sm:gap-2">
                                            <label class="cursor-pointer select-none">
                                                <input type="radio" name="presensi[{{ $g->id }}]" value="hadir" class="sr-only peer" {{ $currentStatus === 'hadir' ? 'checked' : '' }}>
                                                <span class="inline-flex px-3 py-1.5 rounded-lg text-xs font-bold transition border border-slate-200 peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:border-emerald-600 text-slate-600 hover:bg-slate-100">
                                                    Hadir
                                                </span>
                                            </label>

                                            <label class="cursor-pointer select-none">
                                                <input type="radio" name="presensi[{{ $g->id }}]" value="sakit" class="sr-only peer" {{ $currentStatus === 'sakit' ? 'checked' : '' }}>
                                                <span class="inline-flex px-3 py-1.5 rounded-lg text-xs font-bold transition border border-slate-200 peer-checked:bg-amber-500 peer-checked:text-white peer-checked:border-amber-500 text-slate-600 hover:bg-slate-100">
                                                    Sakit
                                                </span>
                                            </label>

                                            <label class="cursor-pointer select-none">
                                                <input type="radio" name="presensi[{{ $g->id }}]" value="izin" class="sr-only peer" {{ $currentStatus === 'izin' ? 'checked' : '' }}>
                                                <span class="inline-flex px-3 py-1.5 rounded-lg text-xs font-bold transition border border-slate-200 peer-checked:bg-sky-600 peer-checked:text-white peer-checked:border-sky-600 text-slate-600 hover:bg-slate-100">
                                                    Izin
                                                </span>
                                            </label>

                                            <label class="cursor-pointer select-none">
                                                <input type="radio" name="presensi[{{ $g->id }}]" value="alfa" class="sr-only peer" {{ $currentStatus === 'alfa' ? 'checked' : '' }}>
                                                <span class="inline-flex px-3 py-1.5 rounded-lg text-xs font-bold transition border border-slate-200 peer-checked:bg-rose-600 peer-checked:text-white peer-checked:border-rose-600 text-slate-600 hover:bg-slate-100">
                                                    Alfa
                                                </span>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="text" name="keterangan[{{ $g->id }}]" value="{{ $p?->keterangan ?? '' }}" placeholder="Catatan / keterangan..." class="w-full rounded-lg border border-slate-200 px-3 py-1.5 text-xs focus:ring-1 focus:ring-sky-500 bg-slate-50">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-100 bg-slate-50 flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-extrabold text-sm shadow-md shadow-sky-600/20 transition cursor-pointer">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Simpan Presensi Guru</span>
                    </button>
                </div>
            @endif
        </form>
    </div>

</div>
@endsection
