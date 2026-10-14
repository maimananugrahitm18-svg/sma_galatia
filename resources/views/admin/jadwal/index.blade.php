@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ showModal: false }">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-primary-600">Admin</a>
                <span>/</span>
                <span class="text-slate-800">Jadwal Mengajar</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Manajemen Jadwal Mengajar Guru</h1>
            <p class="text-xs sm:text-sm text-slate-500">Tahun Ajaran: {{ $tahunAjaran->tahun ?? '-' }} (Semester {{ ucfirst($tahunAjaran->semester ?? '-') }})</p>
        </div>
        <div>
            <button @click="showModal = true" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold shadow-md shadow-primary-500/20 transition">
                <i class="fa-solid fa-plus"></i> Tambah Jadwal Mengajar
            </button>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs mb-6">
        <form action="{{ route('admin.jadwal.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Filter Kelas</label>
                <select name="kelas_id" class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-1 focus:ring-primary-500">
                    <option value="">Semua Kelas</option>
                    @foreach ($kelasList as $k)
                        <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Filter Guru</label>
                <select name="guru_id" class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-1 focus:ring-primary-500">
                    <option value="">Semua Guru</option>
                    @foreach ($guruList as $g)
                        <option value="{{ $g->id }}" {{ request('guru_id') == $g->id ? 'selected' : '' }}>{{ $g->nama_lengkap }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Filter Hari</label>
                <select name="hari" class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-1 focus:ring-primary-500 capitalize">
                    <option value="">Semua Hari</option>
                    @foreach (['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'] as $h)
                        <option value="{{ $h }}" {{ request('hari') == $h ? 'selected' : '' }}>{{ ucfirst($h) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="w-full py-2 px-3 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition">
                    <i class="fa-solid fa-filter mr-1"></i> Terapkan
                </button>
                <a href="{{ route('admin.jadwal.index') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table of Schedules -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 border-b border-slate-200 text-slate-500 uppercase font-semibold text-[11px]">
                    <tr>
                        <th class="py-3.5 px-4">Hari & Jam</th>
                        <th class="py-3.5 px-4">Kelas</th>
                        <th class="py-3.5 px-4">Mata Pelajaran</th>
                        <th class="py-3.5 px-4">Guru Pengampu</th>
                        <th class="py-3.5 px-4">Ruangan</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($jadwalList as $j)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-900 capitalize block">{{ $j->hari }}</span>
                                <span class="font-mono text-slate-500 text-[11px]">{{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded-md font-bold bg-primary-50 text-primary-700 border border-primary-100">
                                    {{ $j->kelas->nama_kelas }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-800">
                                {{ $j->mataPelajaran->nama_mapel }}
                                <span class="text-[10px] text-slate-400 block font-normal">{{ $j->mataPelajaran->kode_mapel }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-700">
                                {{ $j->guru->nama_lengkap }}
                                <span class="text-[10px] text-slate-400 block">NIP: {{ $j->guru->nip ?? '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 font-mono">{{ $j->ruangan ?? 'R-Umum' }}</td>
                            <td class="py-3.5 px-4 text-center">
                                <form action="{{ route('admin.jadwal.destroy', $j->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus Jadwal">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-slate-400">Belum ada jadwal yang cocok dengan filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Jadwal -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-200" @click.outside="showModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-calendar-plus text-primary-600"></i>
                    Tambah Jadwal Mengajar Baru
                </h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form action="{{ route('admin.jadwal.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAjaran->id }}">

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Pilih Kelas</label>
                        <select name="kelas_id" required class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-1 focus:ring-primary-500">
                            @foreach ($kelasList as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Mata Pelajaran</label>
                        <select name="mata_pelajaran_id" required class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-1 focus:ring-primary-500">
                            @foreach ($mapelList as $m)
                                <option value="{{ $m->id }}">{{ $m->nama_mapel }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Guru Pengampu</label>
                    <select name="guru_id" required class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-1 focus:ring-primary-500">
                        @foreach ($guruList as $g)
                            <option value="{{ $g->id }}">{{ $g->nama_lengkap }} ({{ $g->user->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Hari</label>
                        <select name="hari" required class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white capitalize">
                            @foreach (['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'] as $h)
                                <option value="{{ $h }}">{{ ucfirst($h) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jam Mulai</label>
                        <input type="time" name="jam_mulai" required value="07:30" class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jam Selesai</label>
                        <input type="time" name="jam_selesai" required value="09:00" class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Ruangan / Lab (Opsional)</label>
                    <input type="text" name="ruangan" placeholder="Contoh: R-101, Lab Fisika" class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl">
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button" @click="showModal = false" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-xl shadow-sm">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
