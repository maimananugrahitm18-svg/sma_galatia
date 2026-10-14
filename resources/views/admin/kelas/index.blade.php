@extends('layouts.dashboard')

@section('title', 'Manajemen Kelas')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ showModal: false, editModal: false, editData: {} }">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-primary-600">Admin</a>
                <span>/</span>
                <span class="text-slate-800">Manajemen Kelas</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Manajemen Kelas</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Kelola data kelas, tingkat, dan wali kelas. Total: <strong class="text-slate-800">{{ $kelasList->count() }} kelas</strong> terdaftar.</p>
        </div>
        <button @click="showModal = true"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold shadow-md shadow-primary-500/20 transition">
            <i class="fa-solid fa-plus"></i> Tambah Kelas Baru
        </button>
    </div>

    {{-- Alert Messages --}}
    @if (session('success'))
        <div class="mb-5 flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-sm text-emerald-800">
            <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="mb-5 flex items-start gap-3 p-4 bg-rose-50 border border-rose-200 rounded-2xl text-sm text-rose-800">
            <i class="fa-solid fa-circle-exclamation text-rose-500 mt-0.5 shrink-0"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif
    @if ($errors->any())
        <div class="mb-5 p-4 bg-rose-50 border border-rose-200 rounded-2xl text-sm text-rose-800">
            <p class="font-bold mb-1 flex items-center gap-2"><i class="fa-solid fa-triangle-exclamation"></i> Terdapat kesalahan input:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Summary Cards --}}
    @php
        $by10 = $kelasList->where('tingkat', '10')->count();
        $by11 = $kelasList->where('tingkat', '11')->count();
        $by12 = $kelasList->where('tingkat', '12')->count();
        $totalSiswa = $kelasList->sum('siswa_count');
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-7">
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-2xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-primary-100 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-school text-primary-600"></i>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-900">{{ $kelasList->count() }}</p>
                <p class="text-xs text-slate-500">Total Kelas</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-2xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-sky-100 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-1 text-sky-600 font-black"></i>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-900">{{ $by10 }}</p>
                <p class="text-xs text-slate-500">Kelas X</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-2xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-2 text-violet-600 font-black"></i>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-900">{{ $by11 }}</p>
                <p class="text-xs text-slate-500">Kelas XI</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-2xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-3 text-amber-600 font-black"></i>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-900">{{ $by12 }}</p>
                <p class="text-xs text-slate-500">Kelas XII</p>
            </div>
        </div>
    </div>

    {{-- Kelas Table --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-2">
            <i class="fa-solid fa-layer-group text-primary-600"></i>
            <h2 class="font-bold text-slate-900 text-sm">Daftar Semua Kelas</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 border-b border-slate-200 text-slate-500 uppercase font-semibold text-[11px]">
                    <tr>
                        <th class="py-3.5 px-4">#</th>
                        <th class="py-3.5 px-4">Nama Kelas</th>
                        <th class="py-3.5 px-4">Tingkat</th>
                        <th class="py-3.5 px-4">Wali Kelas</th>
                        <th class="py-3.5 px-4 text-center">Jumlah Siswa</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($kelasList as $i => $kelas)
                        <tr class="hover:bg-slate-50/60 transition group">
                            <td class="py-3.5 px-4 text-slate-400 font-mono">{{ $i + 1 }}</td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-900 text-sm">{{ $kelas->nama_kelas }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                @php
                                    $tk = ['10' => ['label' => 'Kelas X', 'cls' => 'bg-sky-100 text-sky-700'],
                                           '11' => ['label' => 'Kelas XI', 'cls' => 'bg-violet-100 text-violet-700'],
                                           '12' => ['label' => 'Kelas XII', 'cls' => 'bg-amber-100 text-amber-700']];
                                    $t = $tk[$kelas->tingkat] ?? ['label' => $kelas->tingkat, 'cls' => 'bg-slate-100 text-slate-600'];
                                @endphp
                                <span class="px-2.5 py-1 rounded-lg font-bold text-[11px] {{ $t['cls'] }}">{{ $t['label'] }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                @if ($kelas->waliKelas && $kelas->waliKelas->user)
                                    <span class="font-semibold text-slate-800">{{ $kelas->waliKelas->user->name }}</span>
                                    <span class="text-slate-400 text-[10px] block">{{ $kelas->waliKelas->nip ?? 'NIP: -' }}</span>
                                @else
                                    <span class="text-slate-400 italic">Belum ditentukan</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full font-black text-sm
                                    {{ $kelas->siswa_count > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-400' }}">
                                    {{ $kelas->siswa_count }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center justify-center gap-1.5">
                                    {{-- Edit Button --}}
                                    <button
                                        @click="editModal = true; editData = {
                                            id: {{ $kelas->id }},
                                            nama_kelas: '{{ addslashes($kelas->nama_kelas) }}',
                                            tingkat: '{{ $kelas->tingkat }}',
                                            wali_guru_id: '{{ $kelas->wali_guru_id ?? '' }}'
                                        }"
                                        class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Edit Kelas">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </button>
                                    {{-- Delete Button --}}
                                    <form action="{{ route('admin.kelas.destroy', $kelas->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus kelas {{ $kelas->nama_kelas }}? Pastikan tidak ada siswa atau jadwal di kelas ini.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus Kelas">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-slate-400">
                                <i class="fa-solid fa-school text-3xl mb-3 block text-slate-200"></i>
                                Belum ada kelas yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Tambah Kelas --}}
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-slate-200" @click.outside="showModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-xl bg-primary-100 flex items-center justify-center">
                        <i class="fa-solid fa-plus text-primary-600 text-sm"></i>
                    </span>
                    Tambah Kelas Baru
                </h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('admin.kelas.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Nama Kelas <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="nama_kelas" required
                        placeholder="Contoh: X-MIPA-1, XI-IPS-2, XII-A"
                        class="w-full text-sm px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition"
                        value="{{ old('nama_kelas') }}">
                    <p class="text-[11px] text-slate-400 mt-1">Gunakan format yang konsisten, misal: X-MIPA-1</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Tingkat / Jenjang <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach (['10' => 'Kelas X', '11' => 'Kelas XI', '12' => 'Kelas XII'] as $val => $label)
                            <label class="relative cursor-pointer">
                                <input type="radio" name="tingkat" value="{{ $val }}" class="peer sr-only"
                                    {{ old('tingkat', '10') == $val ? 'checked' : '' }} required>
                                <div class="text-center text-xs font-bold py-2.5 rounded-xl border-2 border-slate-200 text-slate-500
                                    peer-checked:border-primary-500 peer-checked:bg-primary-50 peer-checked:text-primary-700 transition">
                                    {{ $label }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Wali Kelas (Opsional)</label>
                    <select name="wali_guru_id" class="w-full text-sm px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary-400 outline-none transition">
                        <option value="">— Pilih Wali Kelas —</option>
                        @foreach ($guruList as $g)
                            <option value="{{ $g->id }}" {{ old('wali_guru_id') == $g->id ? 'selected' : '' }}>
                                {{ $g->user->name }} ({{ $g->nip ?? 'NIP: -' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button" @click="showModal = false"
                        class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2 text-xs font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-xl shadow-sm shadow-primary-500/20 transition">
                        <i class="fa-solid fa-plus mr-1"></i> Simpan Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Kelas --}}
    <div x-show="editModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-slate-200" @click.outside="editModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-xl bg-indigo-100 flex items-center justify-center">
                        <i class="fa-solid fa-pen-to-square text-indigo-600 text-sm"></i>
                    </span>
                    Edit Data Kelas
                </h3>
                <button @click="editModal = false" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form :action="'/admin/kelas/' + editData.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Kelas <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_kelas" required
                        :value="editData.nama_kelas"
                        class="w-full text-sm px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-400 outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tingkat / Jenjang <span class="text-rose-500">*</span></label>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach (['10' => 'Kelas X', '11' => 'Kelas XI', '12' => 'Kelas XII'] as $val => $label)
                            <label class="relative cursor-pointer">
                                <input type="radio" name="tingkat" value="{{ $val }}" class="peer sr-only"
                                    x-bind:checked="editData.tingkat === '{{ $val }}'">
                                <div class="text-center text-xs font-bold py-2.5 rounded-xl border-2 border-slate-200 text-slate-500
                                    peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 transition">
                                    {{ $label }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Wali Kelas (Opsional)</label>
                    <select name="wali_guru_id" class="w-full text-sm px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-400 outline-none transition">
                        <option value="">— Pilih Wali Kelas —</option>
                        @foreach ($guruList as $g)
                            <option value="{{ $g->id }}" x-bind:selected="editData.wali_guru_id == '{{ $g->id }}'">
                                {{ $g->user->name }} ({{ $g->nip ?? 'NIP: -' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button" @click="editModal = false"
                        class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">Batal</button>
                    <button type="submit"
                        class="px-5 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition">
                        <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
