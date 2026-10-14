<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CBT Mobile - {{ $ujian->judul }}</title>
    
    <!-- Google Fonts & Tailwind -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                    colors: {
                        primary: {
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        /* Disable text selection during exam for integrity */
        * {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        /* Fullscreen overlay styles */
        #lockdown-overlay {
            position: fixed; inset: 0; z-index: 9999;
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #1e1b4b 100%);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            text-align: center; padding: 2rem;
        }
        #lockdown-overlay .pulse-ring {
            animation: pulse-ring 1.5s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
        }
        @keyframes pulse-ring {
            0%   { box-shadow: 0 0 0 0 rgba(99,102,241,0.7); }
            70%  { box-shadow: 0 0 0 20px rgba(99,102,241,0); }
            100% { box-shadow: 0 0 0 0 rgba(99,102,241,0); }
        }
        #cheat-modal {
            animation: shake 0.4s ease-in-out;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%       { transform: translateX(-8px); }
            40%       { transform: translateX(8px); }
            60%       { transform: translateX(-5px); }
            80%       { transform: translateX(5px); }
        }
        /* Disable screenshot / screen capture CSS hint */
        body { -webkit-touch-callout: none; }
    </style>
</head>
<body class="flex flex-col min-h-full font-sans text-slate-800 antialiased select-none" 
      x-data="cbtExam({
          siswaUjianId: {{ $siswaUjian->id }},
          totalSoal: {{ $ujian->soal->count() }},
          initialAnswers: {{ json_encode($jawabanTersimpan) }},
          sisaDetik: {{ $sisaDetik }},
          submitUrl: '{{ route('siswa.ujian.submit', $ujian->id) }}',
          saveUrl: '{{ route('siswa.ujian.simpan-jawaban') }}',
          csrfToken: '{{ csrf_token() }}'
      })" 
      x-init="initTimer()">

    <!-- Sticky Mobile Header -->
    <header class="sticky top-0 z-40 bg-white border-b border-slate-200/90 shadow-xs px-3 sm:px-6 py-2.5">
        <div class="max-w-4xl mx-auto flex items-center justify-between">
            
            <!-- Exam Title & Save Status -->
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-primary-600 text-white flex items-center justify-center text-xs font-bold shrink-0">
                    <i class="fa-solid fa-mobile-screen"></i>
                </div>
                <div class="leading-tight">
                    <h1 class="text-xs sm:text-sm font-bold text-slate-900 truncate max-w-[150px] sm:max-w-xs">{{ $ujian->judul }}</h1>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full" :class="saveStatus === 'saved' ? 'bg-emerald-500' : 'bg-amber-500 animate-ping'"></span>
                        <span class="text-[10px] font-medium" :class="saveStatus === 'saved' ? 'text-emerald-700' : 'text-amber-700'" x-text="saveStatusText"></span>
                    </div>
                </div>
            </div>

            <!-- Timer Countdown & Question Drawer Button -->
            <div class="flex items-center gap-2">
                <!-- Timer Box -->
                <div class="flex items-center gap-1 px-2.5 py-1.5 rounded-xl font-mono text-xs sm:text-sm font-bold shadow-2xs border"
                     :class="sisaDetik < 300 ? 'bg-rose-50 text-rose-700 border-rose-300 animate-pulse' : 'bg-slate-50 text-slate-800 border-slate-200'">
                    <i class="fa-solid fa-stopwatch text-xs" :class="sisaDetik < 300 ? 'text-rose-600' : 'text-primary-600'"></i>
                    <span x-text="timerFormatted">00:00:00</span>
                </div>

                <!-- Palette Drawer Trigger -->
                <button @click="showPalette = true" class="px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs flex items-center gap-1 border border-slate-200 transition">
                    <i class="fa-solid fa-table-cells-large"></i>
                    <span class="hidden sm:inline">Nomor</span>
                    <span class="text-primary-600" x-text="`(${answeredCount}/${totalSoal})`"></span>
                </button>
            </div>

        </div>
    </header>

    <!-- ===== FULLSCREEN LOCKDOWN ENTRY OVERLAY ===== -->
    <div id="lockdown-overlay">
        <div class="pulse-ring w-24 h-24 rounded-full bg-indigo-600/30 flex items-center justify-center mb-6">
            <div class="w-16 h-16 rounded-full bg-indigo-600 flex items-center justify-center">
                <i class="fa-solid fa-lock text-white text-2xl"></i>
            </div>
        </div>
        <h2 class="text-white text-xl font-extrabold mb-2">Mode Ujian Aman</h2>
        <p class="text-indigo-200 text-sm mb-1 max-w-xs">Ujian akan berjalan dalam mode <strong>layar penuh terkunci</strong>.</p>
        <p class="text-indigo-300 text-xs mb-6 max-w-xs">Dilarang berpindah aplikasi, membuka Google, atau tab lain selama ujian berlangsung.</p>
        <div class="bg-indigo-900/60 border border-indigo-700 rounded-2xl p-3 mb-6 text-left max-w-xs w-full">
            <p class="text-indigo-200 text-xs font-semibold mb-1.5"><i class="fa-solid fa-shield-halved text-indigo-400 mr-1"></i>Sistem Pengawasan Aktif:</p>
            <ul class="text-indigo-300 text-xs space-y-1">
                <li><i class="fa-solid fa-check text-emerald-400 mr-1"></i>Deteksi perpindahan layar / tab</li>
                <li><i class="fa-solid fa-check text-emerald-400 mr-1"></i>Blokir tombol keyboard berbahaya</li>
                <li><i class="fa-solid fa-check text-emerald-400 mr-1"></i>Nonaktifkan salin & tempel teks</li>
                <li><i class="fa-solid fa-check text-emerald-400 mr-1"></i>Ujian otomatis dikumpulkan jika menyontek</li>
            </ul>
        </div>
        <button onclick="enterFullscreenAndStart()" class="px-8 py-3.5 bg-indigo-500 hover:bg-indigo-400 text-white font-extrabold rounded-2xl text-base shadow-lg shadow-indigo-900/50 transition active:scale-95">
            <i class="fa-solid fa-play mr-2"></i>Mulai Ujian
        </button>
        <p class="text-indigo-400 text-[10px] mt-4">Dengan menekan tombol di atas, Anda menyetujui aturan integritas akademik.</p>
    </div>

    <!-- ===== CHEAT DETECTED MODAL ===== -->
    <div id="cheat-modal" x-show="showCheatModal" x-cloak
         class="fixed inset-0 z-[9998] flex items-center justify-center p-4 bg-rose-950/90 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-sm w-full p-6 text-center shadow-2xl border-2 border-rose-300">
            <div class="w-16 h-16 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-3xl mx-auto mb-4">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h3 class="text-base font-extrabold text-rose-700 mb-1">⚠️ Terdeteksi Berpindah Layar!</h3>
            <p class="text-xs text-slate-600 mb-2" x-text="cheatMessage"></p>
            <div class="bg-rose-50 border border-rose-200 rounded-xl p-2.5 mb-4">
                <p class="text-xs text-rose-800 font-bold">Pelanggaran: <span class="text-rose-600 text-base" x-text="cheatCount"></span> / <span x-text="maxCheat"></span></p>
                <p class="text-[10px] text-rose-600 mt-0.5">Ujian otomatis dikumpulkan jika melebihi batas!</p>
            </div>
            <button @click="dismissCheatWarning()" class="w-full py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl transition">
                <i class="fa-solid fa-rotate-right mr-1"></i>Kembali ke Ujian & Kunci Layar
            </button>
        </div>
    </div>

    <!-- Main Exam Question Body (Mobile Optimized) -->
    <main class="grow max-w-4xl w-full mx-auto p-4 sm:p-6 pb-28">

        @php
            $soalArray = $ujian->soal->values();
        @endphp

        @foreach ($soalArray as $index => $soal)
            <div x-show="activeQuestionIndex === {{ $index }}" x-cloak class="space-y-4">
                
                <!-- Question Header Card -->
                <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200 shadow-2xs">
                    <div class="flex items-center justify-between mb-3 pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-8 rounded-xl bg-primary-600 text-white font-bold text-sm flex items-center justify-center shadow-xs shadow-primary-500/30">
                                {{ $index + 1 }}
                            </span>
                            <span class="text-xs font-semibold text-slate-500">Soal {{ $index + 1 }} dari {{ $soalArray->count() }}</span>
                        </div>
                        <span class="text-[11px] font-bold text-primary-700 bg-primary-50 border border-primary-100 px-2.5 py-0.5 rounded-lg">
                            Bobot: {{ $soal->bobot_nilai }} Poin
                        </span>
                    </div>

                    <!-- Pertanyaan Soal -->
                    <div class="text-base sm:text-lg font-medium text-slate-900 leading-relaxed space-y-3">
                        <p>{!! nl2br(e($soal->pertanyaan)) !!}</p>

                        @if ($soal->gambar)
                            <div class="mt-3 rounded-2xl overflow-hidden border border-slate-200 max-h-60 sm:max-h-80">
                                <img src="{{ $soal->gambar }}" alt="Gambar Soal {{ $index + 1 }}" class="w-full object-contain">
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Opsi Pilihan Ganda (Touch Target Besar untuk HP) -->
                @if ($soal->tipe_soal === 'pilihan_ganda')
                    <div class="space-y-2.5">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block px-1">Pilih Jawaban Anda:</span>

                        @foreach ($soal->opsi as $op)
                            <label class="group relative flex items-start gap-3 p-3.5 sm:p-4 rounded-2xl border-2 transition-all cursor-pointer active:scale-[0.99] touch-manipulation"
                                   :class="answers[{{ $soal->id }}] === {{ $op->id }} 
                                        ? 'bg-primary-50/80 border-primary-600 shadow-sm shadow-primary-500/10' 
                                        : 'bg-white border-slate-200 hover:border-slate-300 hover:bg-slate-50/70'">
                                
                                <input type="radio" 
                                       name="jawaban_{{ $soal->id }}" 
                                       value="{{ $op->id }}" 
                                       @change="selectAnswer({{ $soal->id }}, {{ $op->id }})"
                                       :checked="answers[{{ $soal->id }}] === {{ $op->id }}"
                                       class="sr-only">

                                <!-- Circular Option Badge (A, B, C, D, E) -->
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-sm shrink-0 transition-colors border"
                                     :class="answers[{{ $soal->id }}] === {{ $op->id }} 
                                        ? 'bg-primary-600 text-white border-primary-600 shadow-xs' 
                                        : 'bg-slate-100 text-slate-700 border-slate-300 group-hover:border-slate-400'">
                                    {{ $op->label }}
                                </div>

                                <!-- Teks Pilihan -->
                                <div class="pt-1.5 grow">
                                    <span class="text-sm sm:text-base font-medium text-slate-900 leading-normal block">
                                        {{ $op->teks_opsi }}
                                    </span>
                                </div>

                                <!-- Check Icon indicator -->
                                <div class="pt-2 text-primary-600 transition-opacity"
                                     :class="answers[{{ $soal->id }}] === {{ $op->id }} ? 'opacity-100' : 'opacity-0'">
                                    <i class="fa-solid fa-circle-check text-base"></i>
                                </div>
                            </label>
                        @endforeach
                    </div>
                @endif

            </div>
        @endforeach

    </main>

    <!-- Bottom Action Navigation Bar (Fixed for Mobile Screens) -->
    <footer class="fixed bottom-0 inset-x-0 bg-white/95 backdrop-blur-md border-t border-slate-200/90 p-3 sm:p-4 z-30">
        <div class="max-w-4xl mx-auto flex items-center justify-between gap-2">
            
            <!-- Tombol Sebelumnya -->
            <button @click="prevQuestion()" 
                    :disabled="activeQuestionIndex === 0"
                    :class="activeQuestionIndex === 0 ? 'opacity-40 cursor-not-allowed bg-slate-100 text-slate-400' : 'bg-slate-100 hover:bg-slate-200 text-slate-800'"
                    class="px-4 py-2.5 rounded-xl font-bold text-xs sm:text-sm flex items-center gap-1.5 transition">
                <i class="fa-solid fa-chevron-left text-xs"></i>
                <span>Sebelumnya</span>
            </button>

            <!-- Status Ragu-Ragu Toggle -->
            <button @click="toggleRagu()" 
                    class="px-3.5 py-2.5 rounded-xl border text-xs sm:text-sm font-bold flex items-center gap-1.5 transition"
                    :class="raguList[activeQuestionIndex] ? 'bg-amber-100 border-amber-300 text-amber-800' : 'bg-slate-50 border-slate-200 text-slate-600 hover:bg-slate-100'">
                <i class="fa-solid fa-flag text-xs" :class="raguList[activeQuestionIndex] ? 'text-amber-600' : 'text-slate-400'"></i>
                <span class="hidden sm:inline">Tandai</span> Ragu
            </button>

            <!-- Tombol Selanjutnya / Selesai -->
            <template x-if="activeQuestionIndex < totalSoal - 1">
                <button @click="nextQuestion()" 
                        class="px-4 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-bold text-xs sm:text-sm flex items-center gap-1.5 shadow-sm transition">
                    <span>Selanjutnya</span>
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </button>
            </template>

            <template x-if="activeQuestionIndex === totalSoal - 1">
                <button @click="showConfirmSubmit = true" 
                        class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm flex items-center gap-1.5 shadow-sm transition animate-pulse">
                    <i class="fa-solid fa-check-double text-xs"></i>
                    <span>Selesai Ujian</span>
                </button>
            </template>

        </div>
    </footer>

    <!-- Bottom Sheet Modal / Drawer Nomor Soal (Palette) -->
    <div x-show="showPalette" x-cloak class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4 bg-slate-900/60 backdrop-blur-xs">
        <div class="bg-white rounded-t-3xl sm:rounded-3xl w-full max-w-lg p-5 sm:p-6 shadow-2xl border border-slate-200 max-h-[85vh] overflow-y-auto" @click.outside="showPalette = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-table-cells text-primary-600"></i>
                    Daftar Nomor Soal Ujian
                </h3>
                <button @click="showPalette = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <!-- Legenda -->
            <div class="flex items-center gap-3 text-[11px] text-slate-500 mb-4 bg-slate-50 p-2.5 rounded-xl">
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-md bg-primary-600"></span> Dijawab</span>
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-md bg-amber-400"></span> Ragu-Ragu</span>
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-md bg-slate-200"></span> Kosong</span>
            </div>

            <!-- Grid Numbers -->
            <div class="grid grid-cols-5 gap-2.5">
                @foreach ($soalArray as $index => $soal)
                    <button @click="activeQuestionIndex = {{ $index }}; showPalette = false" 
                            class="py-3 rounded-xl font-mono font-bold text-xs flex flex-col items-center justify-center border transition relative"
                            :class="{
                                'ring-2 ring-indigo-500 font-extrabold': activeQuestionIndex === {{ $index }},
                                'bg-amber-400 text-slate-900 border-amber-500': raguList[{{ $index }}],
                                'bg-primary-600 text-white border-primary-700': !raguList[{{ $index }}] && answers[{{ $soal->id }}],
                                'bg-slate-100 text-slate-700 border-slate-200 hover:bg-slate-200': !raguList[{{ $index }}] && !answers[{{ $soal->id }}]
                            }">
                        <span>{{ $index + 1 }}</span>
                    </button>
                @endforeach
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100">
                <button @click="showConfirmSubmit = true; showPalette = false" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl transition">
                    Kumpulkan Ujian Sekarang
                </button>
            </div>
        </div>
    </div>

    <!-- Confirm Submit Modal -->
    <div x-show="showConfirmSubmit" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div class="bg-white rounded-3xl max-w-sm w-full p-6 text-center shadow-2xl border border-slate-200" @click.outside="showConfirmSubmit = false">
            <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl mx-auto mb-4">
                <i class="fa-solid fa-cloud-arrow-up"></i>
            </div>
            <h3 class="text-base font-bold text-slate-900 mb-1">Selesaikan Ulangan Harian?</h3>
            <p class="text-xs text-slate-500 mb-4">
                Anda telah menjawab <strong class="text-primary-600" x-text="answeredCount"></strong> dari <span x-text="totalSoal"></span> soal. Jawaban tidak dapat diubah setelah dikumpulkan.
            </p>

            <form :action="submitUrl" method="POST" class="space-y-2">
                <input type="hidden" name="_token" :value="csrfToken">
                <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                    Ya, Kumpulkan Jawaban
                </button>
                <button type="button" @click="showConfirmSubmit = false" class="w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition">
                    Cek Kembali Soal
                </button>
            </form>
        </div>
    </div>

    <!-- CBT Mobile Alpine Controller & Auto-Save Logic -->
    <script>
        function cbtExam(config) {
            return {
                siswaUjianId: config.siswaUjianId,
                totalSoal: config.totalSoal,
                answers: config.initialAnswers || {},
                raguList: {},
                sisaDetik: config.sisaDetik,
                submitUrl: config.submitUrl,
                saveUrl: config.saveUrl,
                csrfToken: config.csrfToken,
                
                activeQuestionIndex: 0,
                showPalette: false,
                showConfirmSubmit: false,
                showCheatModal: false,
                cheatCount: 0,
                maxCheat: 3,
                cheatMessage: '',

                saveStatus: 'saved',
                saveStatusText: 'Tersimpan di Cloud',

                get answeredCount() {
                    return Object.keys(this.answers).length;
                },

                get timerFormatted() {
                    const jam = Math.floor(this.sisaDetik / 3600);
                    const menit = Math.floor((this.sisaDetik % 3600) / 60);
                    const detik = this.sisaDetik % 60;
                    return [jam, menit, detik].map(v => String(v).padStart(2, '0')).join(':');
                },

                initTimer() {
                    // ── Timer Countdown ──
                    const interval = setInterval(() => {
                        if (this.sisaDetik > 0) {
                            this.sisaDetik--;
                        } else {
                            clearInterval(interval);
                            this.autoSubmit();
                        }
                    }, 1000);

                    // ── Anti-Cheat: Deteksi pindah tab / minimize / buka app lain ──
                    document.addEventListener('visibilitychange', () => {
                        if (document.hidden) {
                            this.triggerCheatDetected('Anda terdeteksi meninggalkan halaman ujian!');
                        }
                    });

                    // ── Anti-Cheat: Deteksi window blur (pindah app di HP/desktop) ──
                    window.addEventListener('blur', () => {
                        this.triggerCheatDetected('Anda terdeteksi berpindah ke aplikasi lain!');
                    });

                    // ── Anti-Cheat: Deteksi fullscreen keluar ──
                    document.addEventListener('fullscreenchange', () => {
                        if (!document.fullscreenElement) {
                            this.triggerCheatDetected('Anda keluar dari mode layar penuh!');
                        }
                    });
                },

                triggerCheatDetected(msg) {
                    // Jangan trigger jika modal sudah terbuka atau ujian sudah selesai
                    if (this.showCheatModal || this.showConfirmSubmit) return;
                    this.cheatCount++;
                    this.cheatMessage = msg + ' Ini adalah pelanggaran ke-' + this.cheatCount + '.';
                    this.showCheatModal = true;

                    // Auto-submit jika melebihi batas
                    if (this.cheatCount >= this.maxCheat) {
                        this.cheatMessage = 'Anda telah ' + this.cheatCount + 'x berpindah layar. Ujian dikumpulkan otomatis!';
                        setTimeout(() => { this.autoSubmit(); }, 3000);
                    }
                },

                dismissCheatWarning() {
                    this.showCheatModal = false;
                    // Paksa fullscreen kembali
                    if (document.documentElement.requestFullscreen) {
                        document.documentElement.requestFullscreen().catch(() => {});
                    }
                },

                selectAnswer(soalId, opsiId) {
                    this.answers[soalId] = opsiId;
                    this.saveStatus = 'saving';
                    this.saveStatusText = 'Menyimpan...';

                    // Background auto-save via AJAX (Fetch API)
                    fetch(this.saveUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            siswa_ujian_id: this.siswaUjianId,
                            soal_id: soalId,
                            opsi_jawaban_id: opsiId,
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.saveStatus = 'saved';
                        this.saveStatusText = 'Tersimpan di Cloud';
                    })
                    .catch(err => {
                        this.saveStatus = 'error';
                        this.saveStatusText = 'Gagal menyimpan, cek koneksi';
                    });
                },

                toggleRagu() {
                    this.raguList[this.activeQuestionIndex] = !this.raguList[this.activeQuestionIndex];
                },

                prevQuestion() {
                    if (this.activeQuestionIndex > 0) {
                        this.activeQuestionIndex--;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                },

                nextQuestion() {
                    if (this.activeQuestionIndex < this.totalSoal - 1) {
                        this.activeQuestionIndex++;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                },

                autoSubmit() {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = this.submitUrl;
                    const token = document.createElement('input');
                    token.type = 'hidden';
                    token.name = '_token';
                    token.value = this.csrfToken;
                    form.appendChild(token);
                    document.body.appendChild(form);
                    form.submit();
                }
            }
        }
    </script>

    <!-- ===== LOCKDOWN SECURITY SCRIPTS ===== -->
    <script>
        // ── Fungsi: Masuk fullscreen lalu sembunyikan overlay ──
        function enterFullscreenAndStart() {
            const overlay = document.getElementById('lockdown-overlay');
            const el = document.documentElement;

            const goFullscreen = el.requestFullscreen
                || el.webkitRequestFullscreen
                || el.mozRequestFullScreen
                || el.msRequestFullscreen;

            if (goFullscreen) {
                goFullscreen.call(el)
                    .then(() => { overlay.style.display = 'none'; })
                    .catch(() => {
                        // Browser tidak support fullscreen (beberapa HP) — tetap lanjut
                        overlay.style.display = 'none';
                    });
            } else {
                overlay.style.display = 'none';
            }
        }

        // ── Blokir Klik Kanan ──
        document.addEventListener('contextmenu', e => e.preventDefault());

        // ── Blokir Copy / Paste / Cut ──
        document.addEventListener('copy',  e => e.preventDefault());
        document.addEventListener('paste', e => e.preventDefault());
        document.addEventListener('cut',   e => e.preventDefault());

        // ── Blokir Keyboard Shortcuts Berbahaya ──
        document.addEventListener('keydown', function(e) {
            const blocked = [
                // Ctrl/Cmd + T (tab baru), W (tutup tab), N (jendela baru)
                // Ctrl + L (address bar), F (find), A (select all)
                // Ctrl + U (view source), Shift+I / F12 (DevTools)
                { ctrl: true,  key: 't' }, { ctrl: true,  key: 'w' },
                { ctrl: true,  key: 'n' }, { ctrl: true,  key: 'l' },
                { ctrl: true,  key: 'f' }, { ctrl: true,  key: 'a' },
                { ctrl: true,  key: 'u' }, { ctrl: true,  key: 'r' },
                { ctrl: true,  shift: true, key: 'i' },
                { ctrl: true,  shift: true, key: 'j' },
                { ctrl: true,  shift: true, key: 'c' },
                // F keys
                { key: 'F1' }, { key: 'F3' }, { key: 'F5' },
                { key: 'F6' }, { key: 'F11' }, { key: 'F12' },
                // Alt+F4, Alt+Tab
                { alt: true, key: 'F4' },
                { alt: true, key: 'Tab' },
                // Windows / Meta key
                { meta: true, key: 'Tab' },
            ];

            const match = blocked.some(b => {
                if (b.ctrl  !== undefined && b.ctrl  !== e.ctrlKey)  return false;
                if (b.alt   !== undefined && b.alt   !== e.altKey)   return false;
                if (b.shift !== undefined && b.shift !== e.shiftKey) return false;
                if (b.meta  !== undefined && b.meta  !== e.metaKey)  return false;
                return e.key.toLowerCase() === b.key.toLowerCase();
            });

            if (match) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
        }, true);
    </script>

</body>
</html>
