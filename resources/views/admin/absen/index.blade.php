@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/80 mb-2">
                <i class="fa-solid fa-clipboard-user"></i>
                <span>Manajemen Presensi Harian</span>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Rekap & Input Presensi Siswa</h1>
            <p class="text-sm text-slate-500 mt-0.5">Kelola data kehadiran seluruh siswa berdasarkan kelas dan tanggal pembelajaran.</p>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 mb-8">
        <form method="GET" action="{{ route('admin.absen.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">
            <div class="sm:col-span-5">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pilih Kelas</label>
                <select name="kelas_id" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm focus:outline-hidden focus:ring-2 focus:ring-amber-500 bg-slate-50 font-semibold text-slate-700">
                    @foreach ($kelasList as $k)
                        <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }} (Tingkat {{ $k->tingkat }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-4">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tanggal Presensi</label>
                <input type="date" name="tanggal" value="{{ $tanggal }}" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm focus:outline-hidden focus:ring-2 focus:ring-amber-500 bg-slate-50 font-semibold text-slate-700">
            </div>

            <div class="sm:col-span-3 flex gap-2">
                <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-sm shadow-md shadow-amber-500/20 transition cursor-pointer">
                    <i class="fa-solid fa-filter"></i>
                    <span>Tampilkan</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Stats Bar -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3.5 mb-8">
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Siswa</p>
            <p class="text-2xl font-extrabold text-slate-800 mt-1">{{ $stats['total_siswa'] }}</p>
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
    </div>

    <!-- Student Attendance Form -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">Daftar Kehadiran Siswa</h2>
                <p class="text-xs text-slate-500">Pilih status presensi untuk setiap siswa lalu klik Simpan Presensi.</p>
            </div>
            <span class="text-xs font-semibold px-3 py-1 rounded-full bg-slate-100 text-slate-600">
                <i class="fa-regular fa-calendar mr-1"></i>
                {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}
            </span>
        </div>

        @if ($siswaList->isEmpty())
            <div class="p-12 text-center text-slate-500">
                <i class="fa-solid fa-users-slash text-4xl text-slate-300 mb-3 block"></i>
                <p class="font-bold text-slate-700">Belum ada siswa di kelas ini</p>
                <p class="text-xs text-slate-400 mt-1">Silakan tambahkan siswa melalui menu Data Siswa.</p>
            </div>
        @else
            <form method="POST" action="{{ route('admin.absen.store') }}">
                @csrf
                <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                <input type="hidden" name="kelas_id" value="{{ $kelasId }}">

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 text-[11px] font-extrabold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                                <th class="py-3.5 px-4 w-12 text-center">No</th>
                                <th class="py-3.5 px-4">Nama Siswa / NIS</th>
                                <th class="py-3.5 px-4 text-center">Status Kehadiran</th>
                                <th class="py-3.5 px-4">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @foreach ($siswaList as $idx => $s)
                                @php
                                    $currentStatus = $presensiMap->get($s->id)->status ?? 'hadir';
                                    $currentKet = $presensiMap->get($s->id)->keterangan ?? '';
                                @endphp
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="py-3.5 px-4 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                                    <td class="py-3.5 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-xs">
                                                {{ strtoupper(substr($s->user->name ?? 'S', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <p class="font-bold text-slate-900 leading-tight">{{ $s->user->name ?? 'Siswa' }}</p>
                                                    @if($presensiMap->has($s->id))
                                                        @if($currentStatus === 'sakit')
                                                            <span class="text-[10px] bg-amber-100 text-amber-800 font-extrabold px-1.5 py-0.5 rounded border border-amber-200">
                                                                <i class="fa-solid fa-notes-medical mr-0.5"></i> Sakit
                                                            </span>
                                                        @elseif($currentStatus === 'izin')
                                                            <span class="text-[10px] bg-sky-100 text-sky-800 font-extrabold px-1.5 py-0.5 rounded border border-sky-200">
                                                                <i class="fa-solid fa-envelope-open-text mr-0.5"></i> Izin
                                                            </span>
                                                        @elseif($currentStatus === 'hadir')
                                                            <span class="text-[10px] bg-emerald-100 text-emerald-800 font-extrabold px-1.5 py-0.5 rounded border border-emerald-200">
                                                                <i class="fa-solid fa-check mr-0.5"></i> Hadir
                                                            </span>
                                                        @endif
                                                    @endif
                                                </div>
                                                <p class="text-xs text-slate-400 mt-0.5">NIS: {{ $s->nis ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="flex items-center justify-center gap-3">
                                            <label class="flex items-center gap-1.5 cursor-pointer text-xs font-semibold text-emerald-700 bg-emerald-50/60 hover:bg-emerald-100/70 px-2.5 py-1.5 rounded-lg border border-emerald-200 transition">
                                                <input type="radio" name="presensi[{{ $s->id }}]" value="hadir" {{ $currentStatus === 'hadir' ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500">
                                                <span>Hadir</span>
                                            </label>
                                            <label class="flex items-center gap-1.5 cursor-pointer text-xs font-semibold text-amber-700 bg-amber-50/60 hover:bg-amber-100/70 px-2.5 py-1.5 rounded-lg border border-amber-200 transition">
                                                <input type="radio" name="presensi[{{ $s->id }}]" value="sakit" {{ $currentStatus === 'sakit' ? 'checked' : '' }} class="text-amber-600 focus:ring-amber-500">
                                                <span>Sakit</span>
                                            </label>
                                            <label class="flex items-center gap-1.5 cursor-pointer text-xs font-semibold text-sky-700 bg-sky-50/60 hover:bg-sky-100/70 px-2.5 py-1.5 rounded-lg border border-sky-200 transition">
                                                <input type="radio" name="presensi[{{ $s->id }}]" value="izin" {{ $currentStatus === 'izin' ? 'checked' : '' }} class="text-sky-600 focus:ring-sky-500">
                                                <span>Izin</span>
                                            </label>
                                            <label class="flex items-center gap-1.5 cursor-pointer text-xs font-semibold text-rose-700 bg-rose-50/60 hover:bg-rose-100/70 px-2.5 py-1.5 rounded-lg border border-rose-200 transition">
                                                <input type="radio" name="presensi[{{ $s->id }}]" value="alfa" {{ $currentStatus === 'alfa' ? 'checked' : '' }} class="text-rose-600 focus:ring-rose-500">
                                                <span>Alfa</span>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <input type="text" name="keterangan[{{ $s->id }}]" value="{{ $currentKet }}" placeholder="Catatan (opsional)" class="w-full text-xs rounded-lg border border-slate-200 px-3 py-1.5 focus:outline-hidden focus:ring-2 focus:ring-amber-500 bg-slate-50">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 bg-slate-50/80 border-t border-slate-200 flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-sm shadow-md shadow-amber-500/25 transition cursor-pointer">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Simpan Presensi Kelas</span>
                    </button>
                </div>
            </form>
        @endif
    </div>

</div>
@endsection
