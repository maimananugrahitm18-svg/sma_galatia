@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Back Button & Breadcrumbs -->
    <div class="mb-6">
        <a href="{{ route('guru.absen.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-primary-600 transition">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Jadwal Presensi</span>
        </a>
    </div>

    <!-- Info Banner Card -->
    <div class="bg-gradient-to-r from-primary-900 to-primary-800 rounded-2xl p-6 text-white mb-8 shadow-md">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <span class="px-2.5 py-1 rounded-md text-xs font-bold bg-white/20 text-white mb-2 inline-block">
                    Kelas {{ $jadwal->kelas->nama_kelas ?? 'Kelas' }} &bull; {{ $jadwal->mataPelajaran->nama_mapel ?? 'Mapel' }}
                </span>
                <h1 class="text-2xl font-extrabold tracking-tight">Presensi Kehadiran Siswa</h1>
                <p class="text-xs text-primary-200 mt-1">
                    Hari {{ ucfirst($jadwal->hari) }}, Jam {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }} &bull; Ruang: {{ $jadwal->ruangan ?? 'Kelas Reguler' }}
                </p>
            </div>

            <!-- Date Selector -->
            <form method="GET" action="{{ route('guru.absen.kelas', $jadwal->id) }}" class="flex items-center gap-2 bg-white/10 p-2 rounded-xl backdrop-blur-xs">
                <input type="date" name="tanggal" value="{{ $tanggal }}" class="bg-white text-slate-800 text-xs font-bold rounded-lg px-3 py-2 border-0 focus:ring-2 focus:ring-amber-400">
                <button type="submit" class="px-3 py-2 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-lg transition cursor-pointer">
                    Ganti
                </button>
            </form>
        </div>
    </div>

    <!-- Attendance Form -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">Daftar Siswa di Kelas</h2>
                <p class="text-xs text-slate-500">Pilih status kehadiran masing-masing siswa untuk tanggal {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</p>
            </div>
            <span class="text-xs font-bold text-slate-600 bg-slate-100 px-3 py-1 rounded-full">
                {{ $siswaList->count() }} Siswa Terdaftar
            </span>
        </div>

        @if ($siswaList->isEmpty())
            <div class="p-12 text-center text-slate-500">
                <i class="fa-solid fa-users-slash text-4xl text-slate-300 mb-3 block"></i>
                <p class="font-bold text-slate-700">Belum ada siswa di kelas ini</p>
                <p class="text-xs text-slate-400 mt-1">Admin sekolah belum mendaftarkan siswa untuk kelas ini.</p>
            </div>
        @else
            <form method="POST" action="{{ route('guru.absen.simpan') }}">
                @csrf
                <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">
                <input type="hidden" name="kelas_id" value="{{ $jadwal->kelas_id }}">
                <input type="hidden" name="tanggal" value="{{ $tanggal }}">

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 text-[11px] font-extrabold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                                <th class="py-3.5 px-4 w-12 text-center">No</th>
                                <th class="py-3.5 px-4">Nama Siswa</th>
                                <th class="py-3.5 px-4 text-center">Status Kehadiran</th>
                                <th class="py-3.5 px-4">Catatan Guru</th>
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
                                                                <i class="fa-solid fa-notes-medical mr-0.5"></i> Diajukan Sakit
                                                            </span>
                                                        @elseif($currentStatus === 'izin')
                                                            <span class="text-[10px] bg-sky-100 text-sky-800 font-extrabold px-1.5 py-0.5 rounded border border-sky-200">
                                                                <i class="fa-solid fa-envelope-open-text mr-0.5"></i> Diajukan Izin
                                                            </span>
                                                        @elseif($currentStatus === 'hadir')
                                                            <span class="text-[10px] bg-emerald-100 text-emerald-800 font-extrabold px-1.5 py-0.5 rounded border border-emerald-200">
                                                                <i class="fa-solid fa-check mr-0.5"></i> Mandiri Hadir
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="text-[10px] bg-slate-100 text-slate-500 font-semibold px-1.5 py-0.5 rounded">
                                                            Belum Absen
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-xs text-slate-400 mt-0.5">NIS: {{ $s->nis ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="flex items-center justify-center gap-2 sm:gap-3">
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
