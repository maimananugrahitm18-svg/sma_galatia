@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ tipeSoal: 'pilihan_ganda' }">
    
    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-1">
        <a href="{{ route('guru.dashboard') }}" class="hover:text-primary-600">Portal Guru</a>
        <span>/</span>
        <a href="{{ route('guru.ujian.index') }}" class="hover:text-primary-600">Ulangan Harian</a>
        <span>/</span>
        <span class="text-slate-800">Detail & Butir Soal</span>
    </div>

    <!-- Exam Overview Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-2xs mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-slate-100">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg uppercase bg-primary-100 text-primary-800">{{ $ujian->mataPelajaran->nama_mapel }}</span>
                    <span class="text-xs text-slate-500">• Durasi: {{ $ujian->durasi_menit }} Menit</span>
                    <span class="text-xs text-slate-500">• Total Soal: {{ $ujian->soal->count() }} Butir</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ $ujian->judul }}</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">{{ $ujian->deskripsi ?? 'Tidak ada petunjuk tambahan.' }}</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('guru.ujian.rekap', $ujian->id) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-sm transition">
                    <i class="fa-solid fa-chart-line"></i> Rekap Nilai Siswa ({{ $ujian->pesertaUjian->count() }})
                </a>
                <form action="{{ route('guru.ujian.destroy', $ujian->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket ulangan ini berserta seluruh soal dan riwayatnya untuk menghemat penyimpanan?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold text-xs border border-rose-200 transition cursor-pointer">
                        <i class="fa-solid fa-trash-can"></i> Hapus Paket Ujian
                    </button>
                </form>
            </div>
        </div>

        <div class="pt-4 flex flex-wrap items-center gap-2 text-xs text-slate-600">
            <span class="font-semibold text-slate-800">Target Kelas:</span>
            @foreach ($ujian->kelas as $k)
                <span class="px-2.5 py-0.5 rounded-md font-bold bg-slate-100 text-slate-700 border border-slate-200">{{ $k->nama_kelas }}</span>
            @endforeach
        </div>
    </div>

    <!-- 2 Columns: Form Input Soal & Daftar Soal -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left: Form Tambah Butir Soal -->
        <div class="lg:col-span-5">
            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-2xs sticky top-24">
                <h3 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-plus-circle text-primary-600"></i>
                    Tambah Butir Soal Baru
                </h3>

                <form action="{{ route('guru.ujian.soal.store', $ujian->id) }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tipe Soal</label>
                        <select name="tipe_soal" x-model="tipeSoal" class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl">
                            <option value="pilihan_ganda">Pilihan Ganda (A - E)</option>
                            <option value="esai">Esai / Uraian</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Isi Pertanyaan</label>
                        <textarea name="pertanyaan" rows="3" required placeholder="Tuliskan pertanyaan soal di sini..." class="w-full text-xs p-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Bobot Nilai</label>
                        <input type="number" step="0.5" name="bobot_nilai" value="20" required class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl">
                    </div>

                    <!-- Input Pilihan Ganda -->
                    <div x-show="tipeSoal === 'pilihan_ganda'" class="space-y-3 pt-2 border-t border-slate-100">
                        <label class="block text-xs font-semibold text-slate-700">Pilihan Jawaban (Pilih radio untuk Kunci Benar):</label>
                        
                        @foreach(['A', 'B', 'C', 'D', 'E'] as $label)
                            <div class="flex items-center gap-2">
                                <label class="flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 font-bold text-xs text-slate-700 border border-slate-200 cursor-pointer hover:bg-primary-50">
                                    <input type="radio" name="jawaban_benar" value="{{ $label }}" {{ $label === 'A' ? 'checked' : '' }} class="mr-1 hidden peer">
                                    <span class="peer-checked:text-emerald-600 peer-checked:font-extrabold">{{ $label }}</span>
                                </label>
                                <input type="text" name="opsi[{{ $label }}]" placeholder="Teks pilihan {{ $label }}" class="w-full text-xs px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl">
                            </div>
                        @endforeach
                        <p class="text-[10px] text-slate-400 italic">* Klik label A-E untuk menandai kunci jawaban yang benar.</p>
                    </div>

                    <button type="submit" class="w-full py-2.5 px-4 bg-primary-600 hover:bg-primary-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                        Simpan Butir Soal
                    </button>
                </form>
            </div>
        </div>

        <!-- Right: Daftar Soal Terdaftar -->
        <div class="lg:col-span-7 space-y-4">
            <h3 class="text-base font-bold text-slate-900 flex items-center justify-between">
                <span>Daftar Butir Soal ({{ $ujian->soal->count() }})</span>
                <span class="text-xs text-slate-500 font-normal">Soal akan di-render responsif di HP siswa</span>
            </h3>

            @forelse ($ujian->soal as $index => $s)
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-2xs">
                    <div class="flex items-center justify-between mb-3 pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-primary-100 text-primary-800 font-bold text-xs flex items-center justify-center">
                                {{ $index + 1 }}
                            </span>
                            <span class="text-xs font-semibold text-slate-500 uppercase">{{ str_replace('_', ' ', $s->tipe_soal) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-md border border-emerald-200">
                                Bobot: {{ $s->bobot_nilai }} Poin
                            </span>
                            <form action="{{ route('guru.ujian.soal.destroy', [$ujian->id, $s->id]) }}" method="POST" onsubmit="return confirm('Hapus butir soal #{{ $index + 1 }} ini untuk menghemat penyimpanan?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-7 h-7 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 flex items-center justify-center text-xs transition cursor-pointer border border-rose-200/60" title="Hapus Soal">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <p class="text-sm font-semibold text-slate-900 mb-4 leading-relaxed">{{ $s->pertanyaan }}</p>

                    @if ($s->tipe_soal === 'pilihan_ganda')
                        <div class="space-y-2">
                            @foreach ($s->opsi as $op)
                                <div class="p-2.5 rounded-xl border text-xs flex items-center gap-3 {{ $op->is_benar ? 'bg-emerald-50/70 border-emerald-300 text-emerald-900' : 'bg-slate-50 border-slate-200 text-slate-700' }}">
                                    <span class="w-6 h-6 rounded-md flex items-center justify-center font-bold text-[11px] {{ $op->is_benar ? 'bg-emerald-600 text-white' : 'bg-white border border-slate-300 text-slate-600' }}">
                                        {{ $op->label }}
                                    </span>
                                    <span class="grow">{{ $op->teks_opsi }}</span>
                                    @if ($op->is_benar)
                                        <span class="text-[10px] font-bold text-emerald-700 uppercase bg-emerald-100 px-1.5 py-0.5 rounded">Kunci Benar</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-3xl p-10 text-center border border-slate-200/80">
                    <i class="fa-solid fa-list-ol text-4xl text-slate-300 mb-2"></i>
                    <p class="text-sm font-semibold text-slate-600">Belum ada butir soal pada ulangan ini.</p>
                    <p class="text-xs text-slate-400 mt-1">Gunakan formulir di sebelah kiri untuk mulai menambahkan pertanyaan.</p>
                </div>
            @endforelse
        </div>

    </div>

</div>
@endsection
