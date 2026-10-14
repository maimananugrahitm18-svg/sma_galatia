@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80 mb-2">
                <i class="fa-solid fa-user-check"></i>
                <span>Kehadiran Mandiri Guru</span>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Presensi Harian Guru</h1>
            <p class="text-sm text-slate-500 mt-0.5">Catat kehadiran harian Anda saat tiba di sekolah serta pantau riwayat kedisiplinan mengajar.</p>
        </div>
        <div class="text-xs font-bold text-slate-500 bg-white px-4 py-2.5 rounded-xl border border-slate-200 shadow-xs flex items-center gap-2">
            <i class="fa-regular fa-clock text-emerald-600 text-sm"></i>
            <span>{{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
        </div>
    </div>

    <!-- Check-In Card & Today's Status -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">
        <!-- Form Check-In -->
        <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
            <h2 class="text-base font-extrabold text-slate-800 mb-1 flex items-center gap-2">
                <i class="fa-solid fa-fingerprint text-emerald-600"></i>
                <span>Formulir Presensi Hari Ini</span>
            </h2>
            <p class="text-xs text-slate-400 mb-6">Pilih status kehadiran Anda untuk hari {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}.</p>

            <form action="{{ route('guru.kehadiran.checkin') }}" method="POST">
                @csrf

                <div class="mb-5">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5">Pilih Status Kehadiran</label>
                    <div class="grid grid-cols-3 gap-3" id="status-group">

                        {{-- HADIR --}}
                        <label id="label-hadir"
                            class="status-label relative flex flex-col items-center justify-center p-4 rounded-xl border-2 cursor-pointer transition-all duration-200 select-none
                                {{ ($presensiHariIni?->status ?? 'hadir') === 'hadir'
                                    ? 'border-emerald-500 bg-emerald-500 text-white shadow-lg shadow-emerald-200 scale-[1.02]'
                                    : 'border-slate-200 bg-white hover:border-emerald-300 hover:bg-emerald-50 text-slate-600' }}">
                            <input type="radio" name="status" value="hadir" class="sr-only status-radio"
                                {{ ($presensiHariIni?->status ?? 'hadir') === 'hadir' ? 'checked' : '' }}>
                            {{-- checkmark badge --}}
                            <span id="check-hadir"
                                class="absolute top-2 right-2 w-5 h-5 rounded-full bg-white/30 text-white flex items-center justify-center text-[10px]
                                    {{ ($presensiHariIni?->status ?? 'hadir') === 'hadir' ? '' : 'hidden' }}">
                                <i class="fa-solid fa-check"></i>
                            </span>
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg mb-2
                                {{ ($presensiHariIni?->status ?? 'hadir') === 'hadir' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-600' }}"
                                id="icon-hadir">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <span class="text-sm font-extrabold">Hadir</span>
                            <span class="text-[10px] font-normal mt-0.5
                                {{ ($presensiHariIni?->status ?? 'hadir') === 'hadir' ? 'text-emerald-100' : 'text-slate-400' }}"
                                id="sub-hadir">Tiba di sekolah</span>
                        </label>

                        {{-- SAKIT --}}
                        <label id="label-sakit"
                            class="status-label relative flex flex-col items-center justify-center p-4 rounded-xl border-2 cursor-pointer transition-all duration-200 select-none
                                {{ ($presensiHariIni?->status ?? '') === 'sakit'
                                    ? 'border-amber-500 bg-amber-500 text-white shadow-lg shadow-amber-200 scale-[1.02]'
                                    : 'border-slate-200 bg-white hover:border-amber-300 hover:bg-amber-50 text-slate-600' }}">
                            <input type="radio" name="status" value="sakit" class="sr-only status-radio"
                                {{ ($presensiHariIni?->status ?? '') === 'sakit' ? 'checked' : '' }}>
                            <span id="check-sakit"
                                class="absolute top-2 right-2 w-5 h-5 rounded-full bg-white/30 text-white flex items-center justify-center text-[10px]
                                    {{ ($presensiHariIni?->status ?? '') === 'sakit' ? '' : 'hidden' }}">
                                <i class="fa-solid fa-check"></i>
                            </span>
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg mb-2
                                {{ ($presensiHariIni?->status ?? '') === 'sakit' ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-600' }}"
                                id="icon-sakit">
                                <i class="fa-solid fa-head-side-cough"></i>
                            </div>
                            <span class="text-sm font-extrabold">Sakit</span>
                            <span class="text-[10px] font-normal mt-0.5
                                {{ ($presensiHariIni?->status ?? '') === 'sakit' ? 'text-amber-100' : 'text-slate-400' }}"
                                id="sub-sakit">Dengan surat</span>
                        </label>

                        {{-- IZIN --}}
                        <label id="label-izin"
                            class="status-label relative flex flex-col items-center justify-center p-4 rounded-xl border-2 cursor-pointer transition-all duration-200 select-none
                                {{ ($presensiHariIni?->status ?? '') === 'izin'
                                    ? 'border-sky-500 bg-sky-500 text-white shadow-lg shadow-sky-200 scale-[1.02]'
                                    : 'border-slate-200 bg-white hover:border-sky-300 hover:bg-sky-50 text-slate-600' }}">
                            <input type="radio" name="status" value="izin" class="sr-only status-radio"
                                {{ ($presensiHariIni?->status ?? '') === 'izin' ? 'checked' : '' }}>
                            <span id="check-izin"
                                class="absolute top-2 right-2 w-5 h-5 rounded-full bg-white/30 text-white flex items-center justify-center text-[10px]
                                    {{ ($presensiHariIni?->status ?? '') === 'izin' ? '' : 'hidden' }}">
                                <i class="fa-solid fa-check"></i>
                            </span>
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg mb-2
                                {{ ($presensiHariIni?->status ?? '') === 'izin' ? 'bg-white/20 text-white' : 'bg-sky-100 text-sky-600' }}"
                                id="icon-izin">
                                <i class="fa-solid fa-envelope-open-text"></i>
                            </div>
                            <span class="text-sm font-extrabold">Izin</span>
                            <span class="text-[10px] font-normal mt-0.5
                                {{ ($presensiHariIni?->status ?? '') === 'izin' ? 'text-sky-100' : 'text-slate-400' }}"
                                id="sub-izin">Dinas / keperluan</span>
                        </label>

                    </div>
                </div>

                <div class="mb-5">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Keterangan Tambahan (Opsional)</label>
                    <input type="text" name="keterangan" value="{{ $presensiHariIni?->keterangan ?? '' }}" placeholder="Contoh: Hadir tepat waktu / ada urusan dinas..." class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-hidden focus:ring-2 focus:ring-emerald-500 bg-slate-50 font-medium text-slate-700">
                </div>

                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-extrabold text-sm shadow-md shadow-emerald-600/25 transition cursor-pointer">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span>{{ $presensiHariIni ? 'Perbarui Presensi Hari Ini' : 'Kirim Presensi Sekarang' }}</span>
                </button>
            </form>
        </div>

        <!-- Kartu Status Kehadiran Hari Ini -->
        <div class="lg:col-span-5 flex flex-col justify-between bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 rounded-2xl p-6 text-white shadow-xl">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-white/10 text-emerald-300 border border-white/10 mb-4">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Status Presensi Hari Ini</span>
                </span>

                @if ($presensiHariIni)
                    <div class="mt-2">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 border-emerald-500/40 text-emerald-300 border flex items-center justify-center text-xl shrink-0">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-300">Status Kehadiran</p>
                                <p class="text-xl font-black text-white capitalize tracking-wide">
                                    {{ $presensiHariIni->status }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 pt-5 border-t border-slate-700/80 space-y-2.5 text-xs text-slate-300">
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400">Keterangan:</span>
                                <span class="font-medium text-slate-200">{{ $presensiHariIni->keterangan ?: '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400">Waktu Simpan:</span>
                                <span class="font-mono text-slate-400">{{ $presensiHariIni->updated_at?->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mt-4 text-center py-6">
                        <div class="w-14 h-14 rounded-2xl bg-amber-500/20 border border-amber-500/30 text-amber-300 flex items-center justify-center text-2xl mx-auto mb-3">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                        <h3 class="text-base font-bold text-white">Belum Melakukan Presensi</h3>
                        <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto">Silakan pilih status kehadiran Anda pada formulir di sebelah kiri untuk mencatat absensi hari ini.</p>
                    </div>
                @endif
            </div>

            <!-- Quick Link to Rekap -->
            <div class="mt-6 pt-4 border-t border-slate-700/80 flex items-center justify-between text-xs">
                <span class="text-slate-400">Butuh laporan bulanan?</span>
                <a href="{{ route('guru.rekap.absen-guru') }}" class="font-bold text-emerald-400 hover:text-emerald-300 inline-flex items-center gap-1 transition">
                    <span>Lihat Rekap Absen</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Monthly Summary Bar -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3.5 mb-8">
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Hari</p>
            <p class="text-2xl font-extrabold text-slate-800 mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-emerald-50/70 p-4 rounded-2xl border border-emerald-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider">Hadir</p>
            <p class="text-2xl font-extrabold text-emerald-700 mt-1">{{ $stats['hadir'] }}</p>
        </div>
        <div class="bg-amber-50/70 p-4 rounded-2xl border border-amber-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-amber-600 uppercase tracking-wider">Sakit</p>
            <p class="text-2xl font-extrabold text-amber-700 mt-1">{{ $stats['sakit'] }}</p>
        </div>
        <div class="bg-sky-50/70 p-4 rounded-2xl border border-sky-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-sky-600 uppercase tracking-wider">Izin</p>
            <p class="text-2xl font-extrabold text-sky-700 mt-1">{{ $stats['izin'] }}</p>
        </div>
        <div class="bg-slate-100 p-4 rounded-2xl border border-slate-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Alfa</p>
            <p class="text-2xl font-extrabold text-slate-700 mt-1">{{ $stats['alfa'] }}</p>
        </div>
    </div>

    <!-- History Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-base font-extrabold text-slate-800">Riwayat Presensi Guru Anda</h2>
            <span class="text-xs font-semibold text-slate-400">{{ $riwayatPresensi->total() }} Data Kehadiran</span>
        </div>

        @if ($riwayatPresensi->isEmpty())
            <div class="text-center py-16 px-4">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4 text-2xl">
                    <i class="fa-solid fa-calendar-xmark"></i>
                </div>
                <h3 class="text-base font-bold text-slate-700">Belum Ada Riwayat Presensi</h3>
                <p class="text-sm text-slate-400 mt-1">Gunakan formulir di atas untuk melakukan presensi kehadiran harian Anda.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3.5">Tanggal</th>
                            <th class="px-6 py-3.5 text-center">Status</th>
                            <th class="px-6 py-3.5">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($riwayatPresensi as $p)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900">
                                        {{ \Carbon\Carbon::parse($p->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                                    </div>
                                    <div class="text-xs text-slate-400 font-mono">{{ $p->tanggal->format('Y-m-d') }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $badgeStyles = [
                                            'hadir' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'sakit' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'izin'  => 'bg-sky-50 text-sky-700 border-sky-200',
                                            'alfa'  => 'bg-rose-50 text-rose-700 border-rose-200',
                                        ];
                                    @endphp
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-black uppercase border {{ $badgeStyles[$p->status] ?? 'bg-slate-100 text-slate-600' }}">
                                        {{ $p->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs font-medium text-slate-600">
                                    {{ $p->keterangan ?: '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $riwayatPresensi->links() }}
            </div>
        @endif
    </div>

</div>

@push('scripts')
<script>
(function () {
    const config = {
        hadir: {
            active:   ['border-emerald-500', 'bg-emerald-500', 'text-white', 'shadow-lg', 'shadow-emerald-200', 'scale-[1.02]'],
            inactive: ['border-slate-200', 'bg-white', 'hover:border-emerald-300', 'hover:bg-emerald-50', 'text-slate-600'],
            iconActive:   ['bg-white/20', 'text-white'],
            iconInactive: ['bg-emerald-100', 'text-emerald-600'],
            subActive:    ['text-emerald-100'],
            subInactive:  ['text-slate-400'],
        },
        sakit: {
            active:   ['border-amber-500', 'bg-amber-500', 'text-white', 'shadow-lg', 'shadow-amber-200', 'scale-[1.02]'],
            inactive: ['border-slate-200', 'bg-white', 'hover:border-amber-300', 'hover:bg-amber-50', 'text-slate-600'],
            iconActive:   ['bg-white/20', 'text-white'],
            iconInactive: ['bg-amber-100', 'text-amber-600'],
            subActive:    ['text-amber-100'],
            subInactive:  ['text-slate-400'],
        },
        izin: {
            active:   ['border-sky-500', 'bg-sky-500', 'text-white', 'shadow-lg', 'shadow-sky-200', 'scale-[1.02]'],
            inactive: ['border-slate-200', 'bg-white', 'hover:border-sky-300', 'hover:bg-sky-50', 'text-slate-600'],
            iconActive:   ['bg-white/20', 'text-white'],
            iconInactive: ['bg-sky-100', 'text-sky-600'],
            subActive:    ['text-sky-100'],
            subInactive:  ['text-slate-400'],
        },
    };

    function applyState(value, isActive) {
        const cfg = config[value];
        if (!cfg) return;

        const label = document.getElementById('label-' + value);
        const icon  = document.getElementById('icon-' + value);
        const sub   = document.getElementById('sub-' + value);
        const check = document.getElementById('check-' + value);

        if (!label) return;

        if (isActive) {
            label.classList.remove(...cfg.inactive);
            label.classList.add(...cfg.active);
            icon.classList.remove(...cfg.iconInactive);
            icon.classList.add(...cfg.iconActive);
            sub.classList.remove(...cfg.subInactive);
            sub.classList.add(...cfg.subActive);
            check.classList.remove('hidden');
        } else {
            label.classList.remove(...cfg.active);
            label.classList.add(...cfg.inactive);
            icon.classList.remove(...cfg.iconActive);
            icon.classList.add(...cfg.iconInactive);
            sub.classList.remove(...cfg.subActive);
            sub.classList.add(...cfg.subInactive);
            check.classList.add('hidden');
        }
    }

    document.querySelectorAll('.status-radio').forEach(function (radio) {
        radio.addEventListener('change', function () {
            Object.keys(config).forEach(function (val) {
                applyState(val, val === radio.value);
            });
        });
    });
})();
</script>
@endpush
@endsection
