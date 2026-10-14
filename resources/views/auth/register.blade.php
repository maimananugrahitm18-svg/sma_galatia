@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-lg w-full space-y-6 bg-white p-8 sm:p-10 rounded-3xl shadow-xl border border-slate-200/80" x-data="{ selectedRole: '{{ old('role', 'siswa') }}' }">
        
        <div class="text-center">
            <h2 class="text-2xl font-extrabold text-slate-900">Registrasi Akun Baru</h2>
            <p class="mt-2 text-xs text-slate-500">Pendaftaran mandiri untuk Siswa atau Pengajar SMA Galatia</p>
        </div>

        <form class="space-y-4" action="{{ route('register') }}" method="POST">
            @csrf

            <!-- Pilih Role -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Mendaftar Sebagai:</label>
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" @click="selectedRole = 'siswa'" :class="selectedRole === 'siswa' ? 'border-primary-600 bg-primary-50/70 text-primary-700' : 'border-slate-200 bg-slate-50 text-slate-600'" class="py-2.5 px-4 rounded-xl border text-xs font-bold flex items-center justify-center gap-2 transition">
                        <i class="fa-solid fa-user-graduate"></i> Siswa
                    </button>
                    <button type="button" @click="selectedRole = 'guru'" :class="selectedRole === 'guru' ? 'border-primary-600 bg-primary-50/70 text-primary-700' : 'border-slate-200 bg-slate-50 text-slate-600'" class="py-2.5 px-4 rounded-xl border text-xs font-bold flex items-center justify-center gap-2 transition">
                        <i class="fa-solid fa-chalkboard-user"></i> Guru
                    </button>
                </div>
                <input type="hidden" name="role" :value="selectedRole">
            </div>

            <!-- Nama Lengkap -->
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required
                    class="block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-primary-500 transition"
                    placeholder="Contoh: Kevin Pratama">
                @error('name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">Alamat Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                    class="block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-primary-500 transition"
                    placeholder="email@galatia.sch.id">
                @error('email') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Bagian Khusus Siswa -->
            <div x-show="selectedRole === 'siswa'" class="grid grid-cols-2 gap-3">
                <div>
                    <label for="nis" class="block text-xs font-semibold text-slate-700 mb-1">Nomor Induk Siswa (NIS)</label>
                    <input id="nis" name="nis" type="text" value="{{ old('nis') }}"
                        class="block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-primary-500 transition"
                        placeholder="20261001">
                </div>
                <div>
                    <label for="kelas_id" class="block text-xs font-semibold text-slate-700 mb-1">Kelas</label>
                    <select id="kelas_id" name="kelas_id" class="block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-primary-500 transition">
                        <option value="">Pilih Kelas...</option>
                        @foreach ($kelasList as $k)
                            <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Bagian Khusus Guru -->
            <div x-show="selectedRole === 'guru'">
                <label for="nip" class="block text-xs font-semibold text-slate-700 mb-1">Nomor Induk Pegawai (NIP)</label>
                <input id="nip" name="nip" type="text" value="{{ old('nip') }}"
                    class="block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-primary-500 transition"
                    placeholder="19850115...">
            </div>

            <!-- Password -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">Kata Sandi</label>
                    <input id="password" name="password" type="password" required
                        class="block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-primary-500 transition"
                        placeholder="Minimal 6 karakter">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 mb-1">Ulangi Sandi</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                        class="block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-primary-500 transition"
                        placeholder="••••••••">
                </div>
            </div>
            @error('password') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror

            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-md text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 transition mt-4">
                Daftar & Masuk
            </button>
        </form>

        <div class="text-center text-xs text-slate-500 pt-2 border-t border-slate-100">
            Sudah memiliki akun? 
            <a href="{{ route('login') }}" class="font-bold text-primary-600 hover:text-primary-700 ml-1">
                Masuk di sini
            </a>
        </div>

    </div>
</div>
@endsection
