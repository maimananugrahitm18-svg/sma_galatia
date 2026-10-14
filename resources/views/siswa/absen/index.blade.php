@extends('layouts.dashboard')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{
    statusPilihan: '{{ $presensiHariIni->status ?? 'hadir' }}',
    keterangan: '{{ $presensiHariIni->keterangan ?? '' }}'
}">

    <!-- Header Section -->
    <div class="mb-8">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/80 mb-2">
            <i class="fa-solid fa-clipboard-check"></i>
            <span>Portal Presensi Siswa</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Presensi Kehadiran & Izin Sakit</h1>
        <p class="text-sm text-slate-500 mt-0.5">Catat kehadiran Anda hari ini atau ajukan izin sakit yang akan langsung tersinkronkan ke Guru kelas.</p>
    </div>

    <!-- Today's Attendance Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-7 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-5 border-b border-slate-100">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">
                    <i class="fa-regular fa-calendar mr-1"></i>
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </span>
                <h2 class="text-xl font-extrabold text-slate-900">Status Kehadiran Hari Ini</h2>
            </div>

            <!-- Current Badge Status -->
            <div>
                @if ($presensiHariIni)
                    @php
                        $badgeStyles = [
                            'hadir' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                            'sakit' => 'bg-amber-50 text-amber-800 border-amber-200',
                            'izin'  => 'bg-sky-50 text-sky-800 border-sky-200',
                            'alfa'  => 'bg-rose-50 text-rose-800 border-rose-200',
                        ];
                        $statusLabels = [
                            'hadir' => 'Tercatat Hadir',
                            'sakit' => 'Izin Sakit',
                            'izin'  => 'Izin Tidak Masuk',
                            'alfa'  => 'Tanpa Keterangan (Alfa)',
                        ];
                    @endphp
                    <div class="flex flex-col sm:items-end">
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl text-xs font-extrabold border {{ $badgeStyles[$presensiHariIni->status] ?? 'bg-slate-100' }} shadow-2xs">
                            <span class="w-2 h-2 rounded-full {{ $presensiHariIni->status === 'hadir' ? 'bg-emerald-500' : ($presensiHariIni->status === 'sakit' ? 'bg-amber-500' : 'bg-sky-500') }} animate-pulse"></span>
                            {{ $statusLabels[$presensiHariIni->status] ?? ucfirst($presensiHariIni->status) }}
                        </span>
                        @if ($presensiHariIni->keterangan)
                            <span class="text-xs text-slate-500 mt-1 italic font-medium">"{{ $presensiHariIni->keterangan }}"</span>
                        @endif
                    </div>
                @else
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl text-xs font-extrabold bg-slate-100 text-slate-600 border border-slate-200 shadow-2xs">
                        <i class="fa-regular fa-clock text-slate-400"></i>
                        Belum Mengisi Presensi Hari Ini
                    </span>
                @endif
            </div>
        </div>

        <!-- Presensi Form (Hadir / Sakit / Izin) -->
        <div class="pt-6">
            @if ($presensiHariIni)
                <div class="mb-5 p-4 rounded-2xl bg-indigo-50/70 border border-indigo-100 flex items-center justify-between">
                    <p class="text-xs text-indigo-900 font-semibold flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-indigo-600 text-sm"></i>
                        <span>Kehadiran Anda hari ini tercatat: <strong class="uppercase text-indigo-700">{{ $presensiHariIni->status }}</strong>. Anda dapat memperbarui data atau mengajukan izin sakit di formulir bawah ini kapan saja:</span>
                    </p>
                </div>
            @endif

            <form method="POST" action="{{ route('siswa.absen.checkin') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5">
                        Pilih Status Kehadiran Anda Hari Ini:
                    </label>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <!-- Option 1: Hadir -->
                        <label @click="statusPilihan = 'hadir'" 
                               :class="statusPilihan === 'hadir' ? 'border-emerald-500 bg-emerald-50/80 ring-2 ring-emerald-500/30' : 'border-slate-200 hover:border-slate-300 bg-white'"
                                class="flex items-center gap-3.5 p-4 rounded-2xl border transition-all cursor-pointer shadow-xs">
                            <input type="radio" name="status" value="hadir" x-model="statusPilihan" class="text-emerald-600 focus:ring-emerald-500">
                            <div>
                                <p class="text-sm font-extrabold text-slate-900 flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                                    <span>Hadir</span>
                                </p>
                                <p class="text-[11px] text-slate-500 mt-0.5">Saya hadir dan mengikuti pelajaran</p>
                            </div>
                        </label>

                        <!-- Option 2: Sakit -->
                        <label @click="statusPilihan = 'sakit'" 
                               :class="statusPilihan === 'sakit' ? 'border-amber-500 bg-amber-50/80 ring-2 ring-amber-500/30' : 'border-slate-200 hover:border-slate-300 bg-white'"
                                class="flex items-center gap-3.5 p-4 rounded-2xl border transition-all cursor-pointer shadow-xs">
                            <input type="radio" name="status" value="sakit" x-model="statusPilihan" class="text-amber-600 focus:ring-amber-500">
                            <div>
                                <p class="text-sm font-extrabold text-slate-900 flex items-center gap-1.5">
                                    <i class="fa-solid fa-notes-medical text-amber-500"></i>
                                    <span>Sakit</span>
                                </p>
                                <p class="text-[11px] text-slate-500 mt-0.5">Izin sakit berhalangan hadir</p>
                            </div>
                        </label>

                        <!-- Option 3: Izin -->
                        <label @click="statusPilihan = 'izin'" 
                               :class="statusPilihan === 'izin' ? 'border-sky-500 bg-sky-50/80 ring-2 ring-sky-500/30' : 'border-slate-200 hover:border-slate-300 bg-white'"
                                class="flex items-center gap-3.5 p-4 rounded-2xl border transition-all cursor-pointer shadow-xs">
                            <input type="radio" name="status" value="izin" x-model="statusPilihan" class="text-sky-600 focus:ring-sky-500">
                            <div>
                                <p class="text-sm font-extrabold text-slate-900 flex items-center gap-1.5">
                                    <i class="fa-solid fa-envelope-open-text text-sky-500"></i>
                                    <span>Izin</span>
                                </p>
                                <p class="text-[11px] text-slate-500 mt-0.5">Keperluan penting / keluarga</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Keterangan Alasan (Khusus Sakit & Izin) -->
                <div x-show="statusPilihan === 'sakit' || statusPilihan === 'izin'" x-transition>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        <span x-text="statusPilihan === 'sakit' ? 'Keterangan Sakit (Wajib / Opsional):' : 'Alasan Izin Keperluan:'"></span>
                    </label>
                    <input type="text" name="keterangan" x-model="keterangan" 
                           :placeholder="statusPilihan === 'sakit' ? 'Contoh: Demam tinggi / flu berat / istirahat di rumah' : 'Contoh: Ada acara keluarga penting di luar kota'"
                           class="w-full text-xs rounded-xl border border-slate-200 px-4 py-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 font-medium text-slate-800">
                    <p class="text-[11px] text-slate-400 mt-1">Keterangan ini akan langsung terbaca oleh Guru pengampu saat mengecek presensi kelas.</p>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold text-xs sm:text-sm shadow-md shadow-indigo-600/25 transition cursor-pointer">
                        <i class="fa-solid fa-paper-plane"></i>
                        <span>Simpan & Sinkronkan Presensi</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Attendance Statistics -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5 mb-8">
        <div class="bg-emerald-50/60 p-4 rounded-2xl border border-emerald-200/80 shadow-xs">
            <p class="text-[11px] font-black text-emerald-600 uppercase tracking-wider">Hadir</p>
            <p class="text-2xl font-black text-emerald-700 mt-1">{{ $stats['hadir'] }} <span class="text-xs font-medium text-emerald-600">Hari</span></p>
        </div>
        <div class="bg-amber-50/60 p-4 rounded-2xl border border-amber-200/80 shadow-xs">
            <p class="text-[11px] font-black text-amber-600 uppercase tracking-wider">Izin Sakit</p>
            <p class="text-2xl font-black text-amber-700 mt-1">{{ $stats['sakit'] }} <span class="text-xs font-medium text-amber-600">Hari</span></p>
        </div>
        <div class="bg-sky-50/60 p-4 rounded-2xl border border-sky-200/80 shadow-xs">
            <p class="text-[11px] font-black text-sky-600 uppercase tracking-wider">Izin Keperluan</p>
            <p class="text-2xl font-black text-sky-700 mt-1">{{ $stats['izin'] }} <span class="text-xs font-medium text-sky-600">Hari</span></p>
        </div>
        <div class="bg-slate-100 p-4 rounded-2xl border border-slate-200/80 shadow-xs">
            <p class="text-[11px] font-black text-slate-500 uppercase tracking-wider">Alfa</p>
            <p class="text-2xl font-black text-slate-700 mt-1">{{ $stats['alfa'] }} <span class="text-xs font-medium text-slate-500">Hari</span></p>
        </div>
    </div>

    <!-- Riwayat Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-base font-bold text-slate-900">Riwayat Catatan Kehadiran</h2>
            <span class="text-xs font-bold text-slate-500 bg-slate-100 px-3 py-1 rounded-full">Total: {{ $stats['total'] }} Catatan</span>
        </div>

        @if ($riwayatPresensi->isEmpty())
            <div class="p-12 text-center text-slate-400">
                <i class="fa-regular fa-calendar-xmark text-4xl text-slate-300 mb-3 block"></i>
                <p class="font-bold text-slate-700">Belum ada catatan presensi</p>
                <p class="text-xs text-slate-400 mt-1">Catatan kehadiran akan muncul di sini setelah Anda atau guru mengisi presensi.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 text-[11px] font-black text-slate-500 uppercase tracking-wider border-b border-slate-200">
                            <th class="py-3 px-4">Tanggal</th>
                            <th class="py-3 px-4">Sesi / Guru</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @foreach ($riwayatPresensi as $r)
                            @php
                                $badgeClass = match($r->status) {
                                    'hadir' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'sakit' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'izin'  => 'bg-sky-50 text-sky-700 border-sky-200',
                                    'alfa'  => 'bg-rose-50 text-rose-700 border-rose-200',
                                    default => 'bg-slate-50 text-slate-700 border-slate-200'
                                };
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3.5 px-4 font-bold text-slate-800 text-xs">
                                    {{ \Carbon\Carbon::parse($r->tanggal)->translatedFormat('l, d M Y') }}
                                </td>
                                <td class="py-3.5 px-4 text-xs font-medium text-slate-700">
                                    {{ $r->jadwal->mataPelajaran->nama_mapel ?? 'Presensi Harian' }}
                                    @if ($r->guru)
                                        <span class="text-slate-400 block text-[10px] mt-0.5">Guru: {{ $r->guru->nama_lengkap }}</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-black border {{ $badgeClass }}">
                                        {{ ucfirst($r->status) }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-xs text-slate-600">
                                    {{ $r->keterangan ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($riwayatPresensi->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $riwayatPresensi->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
