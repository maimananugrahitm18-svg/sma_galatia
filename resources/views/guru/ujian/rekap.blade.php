@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-1">
        <a href="{{ route('guru.dashboard') }}" class="hover:text-primary-600">Portal Guru</a>
        <span>/</span>
        <a href="{{ route('guru.ujian.index') }}" class="hover:text-primary-600">Ulangan Harian</a>
        <span>/</span>
        <span class="text-slate-800">Rekap Nilai Siswa</span>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Rekap Hasil Ulangan Harian</h1>
            <p class="text-xs sm:text-sm text-slate-500">{{ $ujian->judul }} • Mata Pelajaran: {{ $ujian->mataPelajaran->nama_mapel }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('guru.ujian.detail', $ujian->id) }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Soal
            </a>
            <button onclick="window.print()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                <i class="fa-solid fa-print mr-1"></i> Cetak / Ekspor PDF
            </button>
        </div>
    </div>

    <!-- Table of Results -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-900 text-sm">Peserta yang Telah Mengumpulkan ({{ $ujian->pesertaUjian->count() }})</h3>
            <span class="text-xs text-slate-500">Nilai otomatis dihitung oleh sistem CBT</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-semibold text-[11px]">
                    <tr>
                        <th class="py-3.5 px-4">No</th>
                        <th class="py-3.5 px-4">Nama Siswa</th>
                        <th class="py-3.5 px-4">Kelas</th>
                        <th class="py-3.5 px-4">Waktu Mulai</th>
                        <th class="py-3.5 px-4">Waktu Selesai</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Nilai Akhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($ujian->pesertaUjian as $index => $pu)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3.5 px-4 font-mono text-slate-400">{{ $index + 1 }}</td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-900 block">{{ $pu->siswa->user->name }}</span>
                                <span class="text-[10px] text-slate-400 font-mono">NIS: {{ $pu->siswa->nis ?? '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded-md font-bold bg-primary-50 text-primary-700">
                                    {{ $pu->siswa->kelas->nama_kelas ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 font-mono">{{ $pu->waktu_mulai->format('d/m/Y H:i') }}</td>
                            <td class="py-3.5 px-4 text-slate-600 font-mono">{{ $pu->waktu_selesai ? $pu->waktu_selesai->format('d/m/Y H:i') : '-' }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded-md font-semibold text-[10px] uppercase {{ $pu->status === 'selesai' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                    {{ str_replace('_', ' ', $pu->status) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <span class="text-base font-extrabold {{ $pu->total_nilai >= 75 ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ number_format($pu->total_nilai, 1) }}
                                </span>
                                <span class="text-[10px] text-slate-400 block">/ 100</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-10 text-slate-400">Belum ada siswa yang mengerjakan atau mengumpulkan ulangan ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
