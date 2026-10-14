@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ showModal: false }">
    
    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-1">
        <a href="{{ route('guru.dashboard') }}" class="hover:text-primary-600">Portal Guru</a>
        <span>/</span>
        <span class="text-slate-800">Bank Soal & Ulangan Harian</span>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Modul Ulangan Harian CBT</h1>
            <p class="text-xs sm:text-sm text-slate-500">Buat paket soal ujian yang dapat dikerjakan siswa secara online melalui smartphone / HP</p>
        </div>
        <button @click="showModal = true" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold shadow-md shadow-primary-500/20 transition">
            <i class="fa-solid fa-plus"></i> Buat Paket Ulangan Baru
        </button>
    </div>

    <!-- Exam List Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($ujianList as $u)
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-2xs flex flex-col hover:shadow-md transition">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] font-bold text-primary-700 bg-primary-50 border border-primary-100 px-2.5 py-1 rounded-lg uppercase">
                        {{ $u->mataPelajaran->nama_mapel }}
                    </span>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-md {{ $u->status === 'terbit' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                        {{ ucfirst($u->status) }}
                    </span>
                </div>

                <h3 class="text-base font-bold text-slate-900 mb-2">{{ $u->judul }}</h3>
                <p class="text-xs text-slate-500 line-clamp-2 mb-4 leading-relaxed">{{ $u->deskripsi ?? 'Tidak ada deskripsi.' }}</p>

                <div class="grid grid-cols-2 gap-2 text-xs text-slate-600 bg-slate-50 p-3 rounded-2xl mb-4">
                    <div>
                        <span class="text-slate-400 block text-[10px]">Durasi:</span>
                        <span class="font-bold text-slate-800">{{ $u->durasi_menit }} Menit</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[10px]">Jumlah Soal:</span>
                        <span class="font-bold text-slate-800">{{ $u->soal->count() }} Butir</span>
                    </div>
                    <div class="col-span-2 pt-2 border-t border-slate-200/60">
                        <span class="text-slate-400 block text-[10px]">Kelas Sasaran:</span>
                        <span class="font-semibold text-primary-700">
                            @foreach ($u->kelas as $k)
                                {{ $k->nama_kelas }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </span>
                    </div>
                </div>

                <div class="mt-auto pt-4 border-t border-slate-100 flex flex-col gap-2">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('guru.ujian.detail', $u->id) }}" class="w-full py-2 px-3 text-center rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold transition">
                            <i class="fa-solid fa-list-check mr-1"></i> Kelola Soal ({{ $u->soal->count() }})
                        </a>
                        <a href="{{ route('guru.ujian.rekap', $u->id) }}" class="w-full py-2 px-3 text-center rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold transition">
                            <i class="fa-solid fa-chart-pie mr-1"></i> Nilai ({{ $u->pesertaUjian->count() }})
                        </a>
                    </div>
                    
                    <form action="{{ route('guru.ujian.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket ulangan ini berserta seluruh riwayatnya? Penghapusan ini permanen untuk menghemat penyimpanan server.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-1.5 px-3 text-center rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-700 text-xs font-semibold border border-rose-200/80 transition cursor-pointer flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-trash-can text-[11px]"></i>
                            <span>Hapus Ujian (Hemat Penyimpanan)</span>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12 bg-white rounded-3xl border border-slate-200/80">
                <i class="fa-solid fa-laptop-code text-4xl text-slate-300 mb-3"></i>
                <h3 class="text-base font-bold text-slate-700">Belum Ada Ulangan Harian</h3>
                <p class="text-xs text-slate-400 max-w-sm mx-auto mt-1 mb-4">Klik tombol di bawah untuk membuat ulangan harian online pertama Anda.</p>
                <button @click="showModal = true" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold rounded-xl transition">
                    + Buat Paket Ulangan
                </button>
            </div>
        @endforelse
    </div>

    <!-- Modal Buat Paket Ujian Baru -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-200" @click.outside="showModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-pen-ruler text-primary-600"></i>
                    Buat Paket Ulangan Harian
                </h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form action="{{ route('guru.ujian.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Mata Pelajaran</label>
                    <select name="mata_pelajaran_id" required class="w-full text-xs px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-1 focus:ring-primary-500">
                        @foreach ($mapelList as $m)
                            <option value="{{ $m->id }}">{{ $m->nama_mapel }} ({{ $m->kode_mapel }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Judul Ulangan</label>
                    <input type="text" name="judul" required placeholder="Contoh: Ulangan Harian 2 - Vektor & Kinematika" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi / Petunjuk Pengerjaan</label>
                    <textarea name="deskripsi" rows="2" placeholder="Petunjuk untuk siswa saat membuka ulangan di HP..." class="w-full text-xs px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Durasi Pengerjaan</label>
                        <div class="relative">
                            <input type="number" name="durasi_menit" value="60" min="5" max="180" required class="w-full text-xs px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl">
                            <span class="absolute right-3 top-2.5 text-xs text-slate-400 font-medium">Menit</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Waktu Selesai (Batas)</label>
                        <input type="date" name="waktu_selesai" value="{{ now()->addDays(7)->format('Y-m-d') }}" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Target Kelas Peserta Ujian</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        @foreach ($kelasList as $k)
                            <label class="flex items-center gap-2 p-2 bg-slate-50 rounded-xl border border-slate-200 text-xs cursor-pointer hover:bg-primary-50 hover:border-primary-300">
                                <input type="checkbox" name="kelas_ids[]" value="{{ $k->id }}" class="rounded text-primary-600 focus:ring-primary-500">
                                <span class="font-semibold text-slate-700">{{ $k->nama_kelas }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button" @click="showModal = false" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-xl shadow-sm">Lanjut Input Soal &rarr;</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
