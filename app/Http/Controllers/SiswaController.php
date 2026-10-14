<?php

namespace App\Http\Controllers;

use App\Models\JadwalPelajaran;
use App\Models\OpsiJawaban;
use App\Models\Presensi;
use App\Models\SiswaJawaban;
use App\Models\SiswaProfile;
use App\Models\SiswaUjian;
use App\Models\SoalUjian;
use App\Models\Ujian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    protected function getSiswaProfile()
    {
        return SiswaProfile::where('user_id', Auth::id())->firstOrFail();
    }

    public function dashboard()
    {
        $siswa = $this->getSiswaProfile();

        $jadwalHariIni = collect();
        if ($siswa->kelas_id) {
            $jadwalHariIni = JadwalPelajaran::with(['guru.user', 'mataPelajaran'])
                ->where('kelas_id', $siswa->kelas_id)
                ->where('hari', strtolower(now()->locale('id')->isoFormat('dddd')))
                ->orderBy('jam_mulai')
                ->get();
        }

        // Ujian yang tersedia untuk kelas siswa
        $ujianTersedia = Ujian::whereHas('kelas', function ($q) use ($siswa) {
                $q->where('kelas_id', $siswa->kelas_id);
            })
            ->where('status', 'terbit')
            ->with(['mataPelajaran', 'guru.user', 'pesertaUjian' => function ($q) use ($siswa) {
                $q->where('siswa_id', $siswa->id);
            }])
            ->latest()
            ->get();

        return view('siswa.dashboard', compact('siswa', 'jadwalHariIni', 'ujianTersedia'));
    }

    public function jadwalSaya()
    {
        $siswa = $this->getSiswaProfile();
        $jadwal = collect();
        if ($siswa->kelas_id) {
            $jadwal = JadwalPelajaran::with(['guru.user', 'mataPelajaran', 'tahunAjaran'])
                ->where('kelas_id', $siswa->kelas_id)
                ->orderBy('hari')
                ->orderBy('jam_mulai')
                ->get();
        }

        return view('siswa.jadwal', compact('siswa', 'jadwal'));
    }

    public function ujianIndex()
    {
        $siswa = $this->getSiswaProfile();

        $ujianList = Ujian::whereHas('kelas', function ($q) use ($siswa) {
                $q->where('kelas_id', $siswa->kelas_id);
            })
            ->where('status', 'terbit')
            ->with(['mataPelajaran', 'guru.user', 'pesertaUjian' => function ($q) use ($siswa) {
                $q->where('siswa_id', $siswa->id);
            }])
            ->latest()
            ->get();

        return view('siswa.ujian.index', compact('siswa', 'ujianList'));
    }

    /**
     * Tampilan CBT Ulangan Harian Responsif Mobile
     */
    public function kerjakanUjian($id)
    {
        $siswa = $this->getSiswaProfile();

        $ujian = Ujian::whereHas('kelas', function ($q) use ($siswa) {
                $q->where('kelas_id', $siswa->kelas_id);
            })
            ->with(['soal.opsi', 'mataPelajaran'])
            ->findOrFail($id);

        // Cek atau buat sesi ujian siswa
        $siswaUjian = SiswaUjian::firstOrCreate(
            [
                'ujian_id' => $ujian->id,
                'siswa_id' => $siswa->id,
            ],
            [
                'waktu_mulai' => now(),
                'sisa_waktu_detik' => $ujian->durasi_menit * 60,
                'status' => 'sedang_mengerjakan',
            ]
        );

        if ($siswaUjian->status === 'selesai') {
            return redirect()->route('siswa.ujian.hasil', $ujian->id)->with('info', 'Anda telah menyelesaikan ujian ini.');
        }

        // Ambil jawaban yang sudah pernah disimpan sebelumnya
        $jawabanTersimpan = SiswaJawaban::where('siswa_ujian_id', $siswaUjian->id)
            ->pluck('opsi_jawaban_id', 'soal_id')
            ->toArray();

        // Hitung sisa waktu aktual berdasarkan durasi
        $waktuHabis = Carbon::parse($siswaUjian->waktu_mulai)->addMinutes($ujian->durasi_menit);
        $sisaDetikAktual = max(0, now()->diffInSeconds($waktuHabis, false));

        if ($sisaDetikAktual <= 0) {
            $this->selesaikanUjianInternal($siswaUjian);
            return redirect()->route('siswa.ujian.hasil', $ujian->id)->with('info', 'Waktu ujian telah berakhir.');
        }

        return view('siswa.ujian.cbt-mobile', [
            'ujian' => $ujian,
            'siswaUjian' => $siswaUjian,
            'jawabanTersimpan' => $jawabanTersimpan,
            'sisaDetik' => $sisaDetikAktual,
        ]);
    }

    /**
     * Auto-save jawaban via AJAX dari smartphone tanpa refresh halaman
     */
    public function simpanJawabanAjax(Request $request)
    {
        $siswa = $this->getSiswaProfile();

        $request->validate([
            'siswa_ujian_id' => 'required|exists:siswa_ujian,id',
            'soal_id' => 'required|exists:soal_ujian,id',
            'opsi_jawaban_id' => 'nullable|exists:opsi_jawaban,id',
        ]);

        $siswaUjian = SiswaUjian::where('id', $request->siswa_ujian_id)
            ->where('siswa_id', $siswa->id)
            ->where('status', 'sedang_mengerjakan')
            ->firstOrFail();

        SiswaJawaban::updateOrCreate(
            [
                'siswa_ujian_id' => $siswaUjian->id,
                'soal_id' => $request->soal_id,
            ],
            [
                'opsi_jawaban_id' => $request->opsi_jawaban_id,
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Jawaban berhasil disimpan otomatis!',
        ]);
    }

    public function submitUjian(Request $request, $id)
    {
        $siswa = $this->getSiswaProfile();
        $ujian = Ujian::findOrFail($id);

        $siswaUjian = SiswaUjian::where('ujian_id', $ujian->id)
            ->where('siswa_id', $siswa->id)
            ->where('status', 'sedang_mengerjakan')
            ->firstOrFail();

        $this->selesaikanUjianInternal($siswaUjian);

        return redirect()->route('siswa.ujian.hasil', $ujian->id)->with('success', 'Selamat, ujian Anda berhasil dikumpulkan!');
    }

    protected function selesaikanUjianInternal(SiswaUjian $siswaUjian)
    {
        $ujian = $siswaUjian->ujian()->with('soal.opsi')->first();
        $jawabanList = SiswaJawaban::where('siswa_ujian_id', $siswaUjian->id)->get()->keyBy('soal_id');

        $totalNilai = 0;

        foreach ($ujian->soal as $soal) {
            if ($soal->tipe_soal === 'pilihan_ganda') {
                $jawaban = $jawabanList->get($soal->id);
                if ($jawaban && $jawaban->opsi_jawaban_id) {
                    $opsiBenar = $soal->opsi->firstWhere('is_benar', true);
                    if ($opsiBenar && $opsiBenar->id == $jawaban->opsi_jawaban_id) {
                        $jawaban->nilai_didapat = $soal->bobot_nilai;
                        $jawaban->save();
                        $totalNilai += $soal->bobot_nilai;
                    } else {
                        $jawaban->nilai_didapat = 0;
                        $jawaban->save();
                    }
                }
            }
        }

        $siswaUjian->update([
            'status' => 'selesai',
            'waktu_selesai' => now(),
            'total_nilai' => $totalNilai,
        ]);
    }

    public function hasilUjian($id)
    {
        $siswa = $this->getSiswaProfile();
        $ujian = Ujian::with('mataPelajaran')->findOrFail($id);
        $siswaUjian = SiswaUjian::where('ujian_id', $ujian->id)
            ->where('siswa_id', $siswa->id)
            ->with(['jawaban.soal.opsi', 'jawaban.opsiTerpilih'])
            ->firstOrFail();

        return view('siswa.ujian.hasil', compact('ujian', 'siswaUjian'));
    }

    public function absenIndex()
    {
        $siswa = $this->getSiswaProfile();

        // Use whereDate to properly match SQLite date fields regardless of stored format
        $presensiHariIni = Presensi::where('siswa_id', $siswa->id)
            ->whereDate('tanggal', today())
            ->first();

        $riwayatPresensi = Presensi::with(['jadwal.mataPelajaran', 'guru.user'])
            ->where('siswa_id', $siswa->id)
            ->orderBy('tanggal', 'desc')
            ->paginate(15);

        $stats = [
            'total' => Presensi::where('siswa_id', $siswa->id)->count(),
            'hadir' => Presensi::where('siswa_id', $siswa->id)->where('status', 'hadir')->count(),
            'sakit' => Presensi::where('siswa_id', $siswa->id)->where('status', 'sakit')->count(),
            'izin'  => Presensi::where('siswa_id', $siswa->id)->where('status', 'izin')->count(),
            'alfa'  => Presensi::where('siswa_id', $siswa->id)->where('status', 'alfa')->count(),
        ];

        return view('siswa.absen.index', compact('siswa', 'presensiHariIni', 'riwayatPresensi', 'stats'));
    }

    public function absenCheckin(Request $request)
    {
        $siswa = $this->getSiswaProfile();

        $request->validate([
            'status' => 'required|in:hadir,sakit,izin',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $status = $request->status;
        $keterangan = $request->keterangan;

        if (!$keterangan) {
            $keterangan = match($status) {
                'hadir' => 'Presensi mandiri siswa hadir',
                'sakit' => 'Izin sakit mandiri',
                'izin'  => 'Izin keperluan mandiri',
                default => null
            };
        }

        $presensi = Presensi::where('siswa_id', $siswa->id)
            ->whereDate('tanggal', today())
            ->first();

        if ($presensi) {
            $presensi->update([
                'kelas_id'   => $siswa->kelas_id,
                'status'     => $status,
                'keterangan' => $keterangan,
            ]);
        } else {
            Presensi::create([
                'tanggal'    => today()->format('Y-m-d'),
                'siswa_id'   => $siswa->id,
                'kelas_id'   => $siswa->kelas_id,
                'status'     => $status,
                'keterangan' => $keterangan,
            ]);
        }

        $statusText = match($status) {
            'hadir' => 'Hadir',
            'sakit' => 'Izin Sakit',
            'izin'  => 'Izin',
            default => 'Hadir'
        };

        return back()->with('success', "Presensi hari ini ({$statusText}) berhasil dicatat!");
    }
}
