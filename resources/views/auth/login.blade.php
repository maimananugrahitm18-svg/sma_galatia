@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 sm:p-10 rounded-3xl shadow-xl border border-slate-200/80">
        
        <div class="text-center">
            <div class="w-14 h-14 mx-auto rounded-2xl bg-gradient-to-tr from-primary-700 to-accent-500 flex items-center justify-center text-white shadow-lg shadow-primary-500/25 mb-4">
                <i class="fa-solid fa-graduation-cap text-2xl"></i>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-900">Masuk ke Portal Sekolah</h2>
            <p class="mt-2 text-xs text-slate-500">Satu akun untuk Super Admin, Admin TU, Guru, dan Siswa</p>
        </div>

        <!-- Quick Demo Switcher -->
        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200" x-data="{
            fill(email) {
                document.getElementById('email').value = email;
                document.getElementById('password').value = 'password';
            }
        }">
            <p class="text-[11px] font-semibold text-slate-600 mb-2 flex items-center gap-1.5">
                <i class="fa-solid fa-bolt text-amber-500"></i> Akun Cepat Pengujian (Klik untuk Isi):
            </p>
            <div class="grid grid-cols-2 gap-2 text-xs">
                <button type="button" @click="fill('superadmin@galatia.sch.id')" class="px-2.5 py-1.5 bg-white rounded-lg border border-slate-200 text-slate-700 hover:border-primary-500 hover:text-primary-600 font-medium transition text-left truncate">
                    👑 Super Admin
                </button>
                <button type="button" @click="fill('admin@galatia.sch.id')" class="px-2.5 py-1.5 bg-white rounded-lg border border-slate-200 text-slate-700 hover:border-primary-500 hover:text-primary-600 font-medium transition text-left truncate">
                    🏢 Admin TU
                </button>
                <button type="button" @click="fill('guru@galatia.sch.id')" class="px-2.5 py-1.5 bg-white rounded-lg border border-slate-200 text-slate-700 hover:border-primary-500 hover:text-primary-600 font-medium transition text-left truncate">
                    👨‍🏫 Guru Pengampu
                </button>
                <button type="button" @click="fill('siswa@galatia.sch.id')" class="px-2.5 py-1.5 bg-white rounded-lg border border-slate-200 text-slate-700 hover:border-primary-500 hover:text-primary-600 font-medium transition text-left truncate">
                    📱 Siswa CBT
                </button>
            </div>
        </div>

        <form class="mt-8 space-y-5" action="{{ route('login') }}" method="POST">
            @csrf

            <div>
                <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                        <i class="fa-regular fa-envelope text-sm"></i>
                    </span>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                        class="block w-full pl-10 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                        placeholder="nama@galatia.sch.id">
                </div>
                @error('email')
                    <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">Kata Sandi</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                        <i class="fa-solid fa-lock text-sm"></i>
                    </span>
                    <input id="password" name="password" type="password" required
                        class="block w-full pl-10 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                        placeholder="••••••••">
                </div>
                @error('password')
                    <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded-md border-slate-300 text-primary-600 focus:ring-primary-500">
                    <span class="text-slate-600">Ingat Saya</span>
                </label>
                <a href="#" class="font-semibold text-primary-600 hover:text-primary-700">Lupa kata sandi?</a>
            </div>

            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-md text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all">
                Masuk Sekarang
            </button>
        </form>

        <div class="text-center text-xs text-slate-400 pt-2 border-t border-slate-100">
            <i class="fa-solid fa-circle-info mr-1"></i>
            Belum punya akun? Hubungi <strong class="text-slate-600">Admin / Tata Usaha</strong> sekolah untuk pembuatan akun.
        </div>

    </div>
</div>
@endsection
