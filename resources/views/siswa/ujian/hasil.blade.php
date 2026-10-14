@extends('layouts.dashboard')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <!-- Top Result Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200/80 shadow-md text-center mb-8 relative overflow-hidden">
        <div class="w-20 h-20 mx-auto rounded-3xl {{ $siswaUjian->total_nilai >= 75 ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600' }} flex items-center justify-center text-3xl shadow-inner mb-4">
            <i class="fa-solid {{ $siswaUjian->total_nilai >= 75 ? 'fa-award' : 'fa-clipboard-check' }}"></i>
        </div>

        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $siswaUjian->total_nilai >= 75 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }} mb-2">
            {{ $siswaUjian->total_nilai >= 75 ? 'Tuntas (Di Atas KKM)' : 'Perlu Remedial / Pengayaan' }}
        </span>

        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mb-1">{{ $ujian->judul }}</h1>
        <p class="text-xs sm:text-sm text-slate-500 mb-6">{{ $ujian->mataPelajaran->nama_mapel }} • Dikumpulkan pada {{ $siswaUjian->waktu_selesai?->format('d F Y, H:i') }} WIB</p>

        <!-- Big Score Display -->
        <div class="inline-block bg-slate-50 p-6 rounded-3xl border border-slate-200/80 mb-6">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-1">Nilai Akhir Anda</span>
            <div class="text-5xl sm:text-6xl font-black font-mono {{ $siswaUjian->total_nilai >= 75 ? 'text-emerald-600' : 'text-slate-900' }}">
                {{ number_format($siswaUjian->total_nilai, 1) }}
            </div>
            <span class="text-xs text-slate-400 font-medium">Skala 0 - 100</span>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-3">
            <a href="{{ route('siswa.dashboard') }}" class="px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                <i class="fa-solid fa-house mr-1"></i> Kembali ke Dashboard
            </a>
            <a href="{{ route('siswa.ujian.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
                Daftar Ulangan Lainnya
            </a>
        </div>
    </div>

    <!-- Review Jawaban Butir Soal -->
    <div class="space-y-4">
        <h3 class="text-base font-bold text-slate-900 flex items-center justify-between">
            <span>Rincian Pembahasan Jawaban</span>
            <span class="text-xs text-slate-500 font-normal">Hasil koreksi otomatis</span>
        </h3>

        @foreach ($siswaUjian->jawaban as $index => $jawaban)
            @php
                $soal = $jawaban->soal;
                $isBenar = $jawaban->nilai_didapat > 0;
            @endphp
            <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-2xs">
                <div class="flex items-center justify-between mb-3 pb-3 border-b border-slate-100">
                    <span class="w-7 h-7 rounded-lg bg-slate-100 text-slate-700 font-bold text-xs flex items-center justify-center">
                        {{ $index + 1 }}
                    </span>
                    <span class="text-xs font-bold px-2 py-0.5 rounded-md {{ $isBenar ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                        {{ $isBenar ? 'Benar (+'.$jawaban->nilai_didapat.')' : 'Salah (0)' }}
                    </span>
                </div>

                <p class="text-sm font-semibold text-slate-900 mb-3">{{ $soal->pertanyaan }}</p>

                <div class="text-xs space-y-1.5 bg-slate-50 p-3 rounded-2xl">
                    <p class="text-slate-700">
                        <strong>Jawaban Anda:</strong> 
                        @if ($jawaban->opsiTerpilih)
                            <span class="font-bold text-slate-900">({{ $jawaban->opsiTerpilih->label }}) {{ $jawaban->opsiTerpilih->teks_opsi }}</span>
                        @else
                            <span class="text-rose-600 italic">Tidak dijawab</span>
                        @endif
                    </p>

                    @php
                        $kunci = $soal->opsi->firstWhere('is_benar', true);
                    @endphp
                    @if ($kunci && !$isBenar)
                        <p class="text-emerald-700">
                            <strong>Kunci Jawaban yang Benar:</strong> 
                            <span class="font-bold">({{ $kunci->label }}) {{ $kunci->teks_opsi }}</span>
                        </p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
