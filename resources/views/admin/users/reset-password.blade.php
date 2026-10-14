@extends('layouts.dashboard')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10"
     x-data="{
        search: '',
        resetModal: false,
        selectedUser: null,
        openReset(user) { this.selectedUser = user; this.resetModal = true; }
     }">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-primary-600">Admin</a>
                <i class="fa-solid fa-chevron-right text-[9px]"></i>
                <span class="text-slate-800">Reset Password</span>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900">Reset Kata Sandi Pengguna</h1>
            <p class="text-sm text-slate-500 mt-1">Hanya Admin & Super Admin yang dapat mereset password pengguna lain.</p>
        </div>
        <a href="{{ route('admin.users.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm transition">
            <i class="fa-solid fa-arrow-left text-xs"></i> Kembali ke Daftar Akun
        </a>
    </div>

    {{-- Search --}}
    <div class="relative mb-5">
        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
        <input x-model="search" type="text" placeholder="Cari nama atau email pengguna..."
               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 shadow-xs">
    </div>

    {{-- Tabel Daftar User --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider">Pengguna</th>
                    <th class="text-left px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider hidden sm:table-cell">Email</th>
                    <th class="text-left px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider">Peran</th>
                    <th class="text-right px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($users as $u)
                    @php
                        $roleName = $u->roles->first()?->name ?? 'N/A';
                        $roleColor = match($roleName) {
                            'super-admin' => 'bg-violet-100 text-violet-700',
                            'admin'       => 'bg-blue-100 text-blue-700',
                            'guru'        => 'bg-emerald-100 text-emerald-700',
                            'siswa'       => 'bg-amber-100 text-amber-700',
                            default       => 'bg-slate-100 text-slate-600',
                        };
                    @endphp
                    <tr x-show="search === '' || '{{ strtolower($u->name) }}'.includes(search.toLowerCase()) || '{{ strtolower($u->email) }}'.includes(search.toLowerCase())"
                        class="hover:bg-slate-50/70 transition">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-500 to-primary-600 text-white flex items-center justify-center font-extrabold text-xs shrink-0">
                                    {{ strtoupper(substr($u->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-900 text-sm">{{ $u->name }}</p>
                                    <p class="text-xs text-slate-400 sm:hidden">{{ $u->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-slate-500 text-xs hidden sm:table-cell">{{ $u->email }}</td>
                        <td class="px-5 py-3.5">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold {{ $roleColor }} capitalize">
                                {{ $roleName }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            @if ($roleName === 'super-admin' && !auth()->user()->hasRole('super-admin'))
                                <span class="text-xs text-slate-400 italic">Tidak bisa direset</span>
                            @else
                                <button @click="openReset({id: {{ $u->id }}, name: '{{ addslashes($u->name) }}', role: '{{ $roleName }}'})"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs border border-rose-200 transition">
                                    <i class="fa-solid fa-key text-xs"></i>
                                    Reset Password
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($users->isEmpty())
            <div class="text-center py-12 text-slate-400">
                <i class="fa-solid fa-users-slash text-3xl mb-3"></i>
                <p class="text-sm font-medium">Tidak ada pengguna lain.</p>
            </div>
        @endif
    </div>

    {{-- Modal Reset Password --}}
    <div x-show="resetModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl w-full max-w-sm p-6 shadow-2xl border border-slate-200 animate-fade-in"
             @click.outside="resetModal = false">

            <div class="flex items-center gap-3 mb-5">
                <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-key"></i>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Reset Kata Sandi</h3>
                    <p class="text-xs text-slate-500" x-text="'Untuk akun: ' + (selectedUser?.name ?? '')"></p>
                </div>
            </div>

            <form method="POST" :action="`/admin/users/${selectedUser?.id}/reset-password`">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Password Baru</label>
                        <input type="password" name="new_password" required minlength="6"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
                               placeholder="Minimal 6 karakter">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" required
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
                               placeholder="Ulangi password baru">
                    </div>
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 text-xs text-amber-800">
                        <i class="fa-solid fa-triangle-exclamation mr-1 text-amber-500"></i>
                        Pengguna akan perlu login ulang setelah password direset.
                    </div>
                </div>

                <div class="flex gap-2.5 mt-5">
                    <button type="button" @click="resetModal = false"
                            class="flex-1 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm transition">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm transition shadow-sm">
                        <i class="fa-solid fa-rotate-right mr-1"></i>Reset Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
