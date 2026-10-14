@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-1">
        <a href="{{ route('siswa.dashboard') }}" class="hover:text-primary-600">Portal Siswa</a>
        <span>/</span>
        <span class="text-slate-800">Daftar Ulangan Harian</span>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Ulangan Harian & Kuis Online</h1>
            <p class="text-xs sm:text-sm text-slate-500">
                Pilih ulangan harian yang ingin dikerjakan. Anda dapat membukanya langsung melalui Smartphone atau Komputer.
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($ujianList as $u)
            @php
                $sesi = $u->pesertaUjian->first();
                $isSelesai = $sesi && $sesi->status === 'selesai';
            @endphp
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-2xs flex flex-col justify-between hover:shadow-md transition">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[10px] font-bold text-primary-700 bg-primary-50 border border-primary-100 px-2.5 py-1 rounded-lg uppercase">
                            {{ $u->mataPelajaran->nama_mapel }}
                        </span>
                        @if ($isSelesai)
                            <span class="text-xs font-bold px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Sudah Dikerjakan
                            </span>
                        @else
                            <span class="text-xs font-bold px-2 py-0.5 rounded-md bg-amber-50 text-amber-700 border border-amber-200">
                                Belum Selesai
                            </span>
                        @endif
                    </div>

                    <h3 class="text-base font-bold text-slate-900 mb-1.5">{{ $u->judul }}</h3>
                    <p class="text-xs text-slate-500 line-clamp-2 mb-4 leading-relaxed">{{ $u->deskripsi ?? 'Tidak ada petunjuk khusus.' }}</p>

                    <div class="space-y-1.5 text-xs text-slate-600 bg-slate-50 p-3 rounded-2xl mb-4">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400">Guru Pengampu:</span>
                            <span class="font-semibold text-slate-800">{{ $u->guru->user->name }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400">Durasi Pengerjaan:</span>
                            <span class="font-bold text-slate-800">{{ $u->durasi_menit }} Menit</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400">Batas Waktu:</span>
                            <span class="font-mono text-slate-700">{{ $u->waktu_selesai ? $u->waktu_selesai->format('d/m/Y') : 'Tanpa batas' }}</span>
                        </div>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100">
                    @if ($isSelesai)
                        <div class="flex items-center justify-between">
                            <div class="text-xs">
                                <span class="text-slate-400 block text-[10px]">Nilai Anda:</span>
                                <span class="text-lg font-extrabold text-emerald-600">{{ number_format($sesi->total_nilai, 1) }}</span>
                            </div>
                            <a href="{{ route('siswa.ujian.hasil', $u->id) }}" class="px-4 py-2 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                                <i class="fa-solid fa-square-poll-vertical mr-1"></i> Lihat Hasil
                            </a>
                        </div>
                    @else
                        <a href="{{ route('siswa.ujian.kerjakan', $u->id) }}" class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-bold text-xs shadow-md shadow-primary-500/20 transition">
                            <i class="fa-solid fa-mobile-screen"></i> Buka Soal & Kerjakan
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12 bg-white rounded-3xl border border-slate-200/80">
                <i class="fa-solid fa-clipboard-check text-4xl text-slate-300 mb-2"></i>
                <p class="text-sm font-semibold text-slate-600">Tidak ada ulangan harian yang sedang aktif.</p>
                <p class="text-xs text-slate-400 mt-1">Silakan cek kembali secara berkala saat guru memberikan jadwal ujian.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
