@extends('layouts.dashboard')

@section('title', 'Manajemen Mata Pelajaran')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ showModal: false, editModal: false, editData: {} }">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-primary-600">Admin</a>
                <span>/</span>
                <span class="text-slate-800">Mata Pelajaran</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Manajemen Mata Pelajaran</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Kelola daftar mata pelajaran untuk mencegah konflik jadwal guru. Total: <strong class="text-slate-800">{{ $mapelList->count() }} mapel</strong>.</p>
        </div>
        <button @click="showModal = true"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold shadow-md shadow-primary-500/20 transition">
            <i class="fa-solid fa-plus"></i> Tambah Mata Pelajaran
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
        $kategoriCount = $mapelList->groupBy('kategori');
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-7">
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-2xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-primary-100 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-book-open text-primary-600"></i>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-900">{{ $mapelList->count() }}</p>
                <p class="text-xs text-slate-500">Total Mapel</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-2xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-globe text-blue-600"></i>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-900">{{ $kategoriCount->get('umum', collect())->count() }}</p>
                <p class="text-xs text-slate-500">Mapel Umum</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-2xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-flask text-purple-600"></i>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-900">{{ $kategoriCount->get('peminatan', collect())->count() }}</p>
                <p class="text-xs text-slate-500">Peminatan</p>
            </div>
        </div>
    </div>

    {{-- MapelList by Kategori --}}
    @php
        $kategoris = [
            'umum'          => ['label' => 'Mata Pelajaran Umum', 'icon' => 'fa-globe', 'cls' => 'bg-blue-100 text-blue-700', 'badge' => 'bg-blue-100 text-blue-700 border-blue-200'],
            'peminatan'     => ['label' => 'Mata Pelajaran Peminatan', 'icon' => 'fa-flask', 'cls' => 'bg-purple-100 text-purple-700', 'badge' => 'bg-purple-100 text-purple-700 border-purple-200'],
            'muatan_lokal'  => ['label' => 'Muatan Lokal', 'icon' => 'fa-leaf', 'cls' => 'bg-emerald-100 text-emerald-700', 'badge' => 'bg-emerald-100 text-emerald-700 border-emerald-200'],
        ];
    @endphp

    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-2">
            <i class="fa-solid fa-list-check text-primary-600"></i>
            <h2 class="font-bold text-slate-900 text-sm">Daftar Mata Pelajaran</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 border-b border-slate-200 text-slate-500 uppercase font-semibold text-[11px]">
                    <tr>
                        <th class="py-3.5 px-4">#</th>
                        <th class="py-3.5 px-4">Kode</th>
                        <th class="py-3.5 px-4">Nama Mata Pelajaran</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4 text-center">Dipakai di Jadwal</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($mapelList as $i => $mapel)
                        @php $kat = $kategoris[$mapel->kategori] ?? ['label' => $mapel->kategori, 'icon' => 'fa-book', 'cls' => 'bg-slate-100 text-slate-600', 'badge' => 'bg-slate-100 text-slate-600 border-slate-200']; @endphp
                        <tr class="hover:bg-slate-50/60 transition group">
                            <td class="py-3.5 px-4 text-slate-400 font-mono">{{ $i + 1 }}</td>
                            <td class="py-3.5 px-4">
                                <span class="font-mono font-bold text-sm text-slate-700 bg-slate-100 px-2 py-0.5 rounded-lg">{{ $mapel->kode_mapel }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-900">{{ $mapel->nama_mapel }}</td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg font-bold text-[11px] border {{ $kat['badge'] }}">
                                    <i class="fa-solid {{ $kat['icon'] }} text-[10px]"></i>
                                    {{ $kat['label'] }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full font-black text-sm
                                    {{ $mapel->jadwal_pelajaran_count > 0 ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-400' }}">
                                    {{ $mapel->jadwal_pelajaran_count }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center justify-center gap-1.5">
                                    {{-- Edit --}}
                                    <button
                                        @click="editModal = true; editData = {
                                            id: {{ $mapel->id }},
                                            kode_mapel: '{{ addslashes($mapel->kode_mapel) }}',
                                            nama_mapel: '{{ addslashes($mapel->nama_mapel) }}',
                                            kategori: '{{ $mapel->kategori }}'
                                        }"
                                        class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Edit Mapel">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </button>
                                    {{-- Delete --}}
                                    <form action="{{ route('admin.mapel.destroy', $mapel->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus mata pelajaran {{ $mapel->nama_mapel }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus Mapel">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-slate-400">
                                <i class="fa-solid fa-book-open text-3xl mb-3 block text-slate-200"></i>
                                Belum ada mata pelajaran yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Info Pencegahan Bentrok --}}
    <div class="mt-5 p-4 bg-amber-50 border border-amber-200 rounded-2xl flex items-start gap-3">
        <i class="fa-solid fa-shield-halved text-amber-500 mt-0.5 shrink-0 text-lg"></i>
        <div class="text-xs text-amber-800">
            <p class="font-bold mb-1">Sistem Pencegahan Bentrok Jadwal Aktif</p>
            <p>Saat admin menambahkan jadwal mengajar, sistem secara otomatis akan memeriksa apakah guru atau kelas sudah memiliki jadwal di jam yang sama. Jika terjadi bentrok, jadwal tidak akan disimpan dan sistem akan menampilkan peringatan.</p>
        </div>
    </div>

    {{-- Modal Tambah Mapel --}}
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-slate-200" @click.outside="showModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-xl bg-primary-100 flex items-center justify-center">
                        <i class="fa-solid fa-plus text-primary-600 text-sm"></i>
                    </span>
                    Tambah Mata Pelajaran
                </h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('admin.mapel.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Kode Mata Pelajaran <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="kode_mapel" required maxlength="20"
                        placeholder="Contoh: MTK, BIN, FIS-1, PKWU"
                        class="w-full text-sm px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary-400 outline-none transition uppercase"
                        value="{{ old('kode_mapel') }}">
                    <p class="text-[11px] text-slate-400 mt-1">Kode unik, maks. 20 karakter. Contoh: MTK, BIN, IPA, PKWU</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Nama Mata Pelajaran <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="nama_mapel" required maxlength="100"
                        placeholder="Contoh: Matematika Wajib, Bahasa Indonesia"
                        class="w-full text-sm px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary-400 outline-none transition"
                        value="{{ old('nama_mapel') }}">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Kategori <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 gap-2">
                        @foreach (['umum' => ['Umum', 'Pelajaran wajib yang harus diikuti semua siswa', 'fa-globe', 'peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700'],
                                   'peminatan' => ['Peminatan', 'Pelajaran sesuai jurusan atau peminatan siswa', 'fa-flask', 'peer-checked:border-purple-500 peer-checked:bg-purple-50 peer-checked:text-purple-700'],
                                   'muatan_lokal' => ['Muatan Lokal', 'Pelajaran khas daerah atau keunggulan sekolah', 'fa-leaf', 'peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-700']] as $val => $info)
                            <label class="relative cursor-pointer">
                                <input type="radio" name="kategori" value="{{ $val }}" class="peer sr-only"
                                    {{ old('kategori', 'umum') == $val ? 'checked' : '' }} required>
                                <div class="flex items-center gap-3 p-3 rounded-xl border-2 border-slate-200 text-slate-500 {{ $info[3] }} transition">
                                    <i class="fa-solid {{ $info[2] }} text-sm w-4"></i>
                                    <div>
                                        <p class="font-bold text-xs">{{ $info[0] }}</p>
                                        <p class="text-[11px] font-normal opacity-70">{{ $info[1] }}</p>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button" @click="showModal = false"
                        class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">Batal</button>
                    <button type="submit"
                        class="px-5 py-2 text-xs font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-xl shadow-sm shadow-primary-500/20 transition">
                        <i class="fa-solid fa-plus mr-1"></i> Simpan Mapel
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Mapel --}}
    <div x-show="editModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-slate-200" @click.outside="editModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-xl bg-indigo-100 flex items-center justify-center">
                        <i class="fa-solid fa-pen-to-square text-indigo-600 text-sm"></i>
                    </span>
                    Edit Mata Pelajaran
                </h3>
                <button @click="editModal = false" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form :action="'/admin/mapel/' + editData.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kode Mata Pelajaran <span class="text-rose-500">*</span></label>
                    <input type="text" name="kode_mapel" required maxlength="20"
                        :value="editData.kode_mapel"
                        class="w-full text-sm px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-400 outline-none transition uppercase">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Mata Pelajaran <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_mapel" required maxlength="100"
                        :value="editData.nama_mapel"
                        class="w-full text-sm px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-400 outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kategori <span class="text-rose-500">*</span></label>
                    <div class="grid grid-cols-1 gap-2">
                        @foreach (['umum' => ['Umum', 'fa-globe', 'peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700'],
                                   'peminatan' => ['Peminatan', 'fa-flask', 'peer-checked:border-purple-500 peer-checked:bg-purple-50 peer-checked:text-purple-700'],
                                   'muatan_lokal' => ['Muatan Lokal', 'fa-leaf', 'peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-700']] as $val => $info)
                            <label class="relative cursor-pointer">
                                <input type="radio" name="kategori" value="{{ $val }}" class="peer sr-only"
                                    x-bind:checked="editData.kategori === '{{ $val }}'">
                                <div class="flex items-center gap-3 p-3 rounded-xl border-2 border-slate-200 text-slate-500 {{ $info[2] }} transition">
                                    <i class="fa-solid {{ $info[1] }} text-sm w-4"></i>
                                    <p class="font-bold text-xs">{{ $info[0] }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
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
