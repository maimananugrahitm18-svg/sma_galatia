@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ showModal: false, selectedRole: 'siswa' }">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-primary-600">Admin</a>
                <span>/</span>
                <span class="text-slate-800">Manajemen Akun Pengguna</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Manajemen Akun Pengguna</h1>
            <p class="text-xs sm:text-sm text-slate-500">Hanya Admin & Super Admin yang dapat membuat, melihat, dan menghapus akun pengguna.</p>
        </div>
        <button @click="showModal = true" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold shadow-md shadow-primary-500/20 transition">
            <i class="fa-solid fa-user-plus"></i> Tambah Akun Baru
        </button>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs mb-6">
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col sm:flex-row items-end gap-3">
            <div class="grow">
                <label class="block text-xs font-semibold text-slate-700 mb-1">Cari Nama / Email</label>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama atau email pengguna..."
                        class="w-full pl-9 pr-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-1 focus:ring-primary-500">
                </div>
            </div>
            <div class="w-full sm:w-48">
                <label class="block text-xs font-semibold text-slate-700 mb-1">Filter Berdasarkan Role</label>
                <select name="role" class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-1 focus:ring-primary-500">
                    <option value="">Semua Role</option>
                    <option value="super-admin" {{ request('role') == 'super-admin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin TU</option>
                    <option value="guru" {{ request('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                    <option value="siswa" {{ request('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                </select>
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition">
                    <i class="fa-solid fa-filter mr-1"></i> Cari
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">Reset</a>
            </div>
        </form>
    </div>

    <!-- Role Summary Badges -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
        @php
            $roleCounts = [
                'super-admin' => ['label' => 'Super Admin', 'icon' => 'fa-shield-halved', 'color' => 'purple'],
                'admin' => ['label' => 'Admin TU', 'icon' => 'fa-building-user', 'color' => 'indigo'],
                'guru' => ['label' => 'Guru', 'icon' => 'fa-chalkboard-user', 'color' => 'blue'],
                'siswa' => ['label' => 'Siswa', 'icon' => 'fa-graduation-cap', 'color' => 'emerald'],
            ];
            $allUsers = \App\Models\User::with('roles')->get();
        @endphp
        @foreach ($roleCounts as $roleKey => $info)
            @php
                $count = $allUsers->filter(fn($u) => $u->hasRole($roleKey))->count();
                $colorMap = [
                    'purple' => 'bg-purple-50 border-purple-200 text-purple-700',
                    'indigo' => 'bg-indigo-50 border-indigo-200 text-indigo-700',
                    'blue' => 'bg-blue-50 border-blue-200 text-blue-700',
                    'emerald' => 'bg-emerald-50 border-emerald-200 text-emerald-700',
                ];
            @endphp
            <div class="p-4 bg-white rounded-2xl border border-slate-200/80 shadow-2xs flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl {{ $colorMap[$info['color']] }} border flex items-center justify-center text-sm">
                    <i class="fa-solid {{ $info['icon'] }}"></i>
                </div>
                <div>
                    <p class="text-lg font-extrabold text-slate-900">{{ $count }}</p>
                    <p class="text-[11px] font-semibold text-slate-500">{{ $info['label'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-sm text-slate-900">Daftar Seluruh Pengguna ({{ $users->total() }})</h3>
            <span class="text-xs text-slate-400">Halaman {{ $users->currentPage() }} dari {{ $users->lastPage() }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-semibold text-[11px]">
                    <tr>
                        <th class="py-3.5 px-4">Nama & Email</th>
                        <th class="py-3.5 px-4">Role</th>
                        <th class="py-3.5 px-4">Info Tambahan</th>
                        <th class="py-3.5 px-4">Bergabung</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($users as $user)
                        @php
                            $role = $user->getRoleNames()->first() ?? '-';
                            $roleColors = [
                                'super-admin' => 'bg-purple-100 text-purple-800 border-purple-200',
                                'admin' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                'guru' => 'bg-blue-100 text-blue-800 border-blue-200',
                                'siswa' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                            ];
                            $badgeClass = $roleColors[$role] ?? 'bg-slate-100 text-slate-700';
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-slate-200 text-slate-600 flex items-center justify-center font-bold text-xs shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900">{{ $user->name }}</p>
                                        <p class="text-slate-400 font-mono text-[10px]">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded-md font-bold uppercase text-[10px] border {{ $badgeClass }}">
                                    {{ $role }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-600">
                                @if ($role === 'guru' && $user->guruProfile)
                                    <span>NIP: {{ $user->guruProfile->nip ?? '-' }}</span>
                                @elseif ($role === 'siswa' && $user->siswaProfile)
                                    <span>NIS: {{ $user->siswaProfile->nis ?? '-' }}</span>
                                    @if ($user->siswaProfile->kelas)
                                        <span class="ml-1 px-1.5 py-0.5 rounded-md bg-primary-50 text-primary-700 font-semibold text-[10px]">
                                            {{ $user->siswaProfile->kelas->nama_kelas }}
                                        </span>
                                    @endif
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 font-mono">
                                {{ $user->created_at->format('d/m/Y') }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if ($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                          onsubmit="return confirm('Hapus akun {{ addslashes($user->name) }}? Data profil terkait juga akan ikut terhapus.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Hapus Akun">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[10px] text-slate-400 italic">Akun Anda</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-10 text-slate-400">Tidak ada pengguna yang sesuai filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($users->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $users->withQueryString()->links() }}
            </div>
        @endif
    </div>

    <!-- =====================================================
         MODAL TAMBAH AKUN BARU (Hanya Admin)
         ===================================================== -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-200 max-h-[90vh] overflow-y-auto" @click.outside="showModal = false">

            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-user-shield text-primary-600"></i>
                    Buat Akun Pengguna Baru
                </h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="mb-4 p-3 bg-amber-50 border border-amber-200 rounded-2xl text-xs text-amber-800 flex items-start gap-2">
                <i class="fa-solid fa-lock mt-0.5 shrink-0"></i>
                <span>Fitur ini hanya dapat diakses oleh <strong>Admin & Super Admin</strong>. Pengguna tidak dapat mendaftar sendiri.</span>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Pilih Role -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Role Pengguna</label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach (['siswa' => ['label' => 'Siswa', 'icon' => 'fa-graduation-cap', 'color' => 'emerald'], 'guru' => ['label' => 'Guru', 'icon' => 'fa-chalkboard-user', 'color' => 'blue'], 'admin' => ['label' => 'Admin TU', 'icon' => 'fa-building-user', 'color' => 'indigo'], 'super-admin' => ['label' => 'Super Admin', 'icon' => 'fa-shield-halved', 'color' => 'purple']] as $r => $ri)
                            <label class="flex items-center gap-2 px-3 py-2.5 rounded-xl border cursor-pointer transition text-xs font-semibold"
                                   :class="selectedRole === '{{ $r }}' ? 'border-primary-600 bg-primary-50 text-primary-700' : 'border-slate-200 bg-slate-50 text-slate-600 hover:border-slate-300'">
                                <input type="radio" name="role" value="{{ $r }}" x-model="selectedRole" class="sr-only">
                                <i class="fa-solid {{ $ri['icon'] }} text-xs"></i>
                                <span>{{ $ri['label'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Nama & Email -->
                <div class="grid grid-cols-1 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required placeholder="Nama lengkap pengguna"
                            class="w-full text-xs px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-1 focus:ring-primary-500">
                        @error('name') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Email <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" required placeholder="email@galatia.sch.id"
                            class="w-full text-xs px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-1 focus:ring-primary-500">
                        @error('email') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kata Sandi Awal <span class="text-rose-500">*</span></label>
                        <input type="text" name="password" required placeholder="Minimal 6 karakter"
                            class="w-full text-xs px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-1 focus:ring-primary-500">
                        <p class="text-[10px] text-slate-400 mt-1">Informasikan kata sandi ini ke pengguna yang bersangkutan.</p>
                        @error('password') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- No. HP -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">No. HP (WhatsApp)</label>
                    <input type="text" name="no_hp" placeholder="Contoh: 081234567890"
                        class="w-full text-xs px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white">
                </div>

                <!-- Fields Khusus Guru -->
                <div x-show="selectedRole === 'guru'" class="p-4 bg-blue-50/60 border border-blue-200 rounded-2xl space-y-3">
                    <p class="text-xs font-bold text-blue-800 flex items-center gap-1.5">
                        <i class="fa-solid fa-chalkboard-user"></i> Data Profil Guru
                    </p>
                    <div class="grid grid-cols-3 gap-2">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 mb-1">Gelar Depan</label>
                            <input type="text" name="gelar_depan" placeholder="Drs." class="w-full text-xs px-2.5 py-2 bg-white border border-slate-200 rounded-xl">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 mb-1">Gelar Belakang</label>
                            <input type="text" name="gelar_belakang" placeholder="M.Pd" class="w-full text-xs px-2.5 py-2 bg-white border border-slate-200 rounded-xl">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 mb-1">NIP</label>
                            <input type="text" name="nip" placeholder="19xxxxxx..." class="w-full text-xs px-2.5 py-2 bg-white border border-slate-200 rounded-xl">
                            @error('nip') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Fields Khusus Siswa -->
                <div x-show="selectedRole === 'siswa'" class="p-4 bg-emerald-50/60 border border-emerald-200 rounded-2xl space-y-3">
                    <p class="text-xs font-bold text-emerald-800 flex items-center gap-1.5">
                        <i class="fa-solid fa-graduation-cap"></i> Data Profil Siswa
                    </p>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 mb-1">NIS (Nomor Induk Siswa)</label>
                            <input type="text" name="nis" placeholder="20261001" class="w-full text-xs px-2.5 py-2 bg-white border border-slate-200 rounded-xl">
                            @error('nis') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 mb-1">NISN</label>
                            <input type="text" name="nisn" placeholder="006xxxxxxx" class="w-full text-xs px-2.5 py-2 bg-white border border-slate-200 rounded-xl">
                            @error('nisn') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Kelas</label>
                        <select name="kelas_id" class="w-full text-xs px-2.5 py-2 bg-white border border-slate-200 rounded-xl">
                            <option value="">Pilih Kelas...</option>
                            @foreach ($kelasList as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kelas }} (Tingkat {{ $k->tingkat }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Submit -->
                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button" @click="showModal = false" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-xl shadow-sm transition">
                        <i class="fa-solid fa-user-plus mr-1"></i> Buat Akun
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection
