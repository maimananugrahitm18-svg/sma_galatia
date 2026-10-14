<?php

namespace App\Http\Controllers;

use App\Models\GuruProfile;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\OpsiJawaban;
use App\Models\Presensi;
use App\Models\PresensiGuru;
use App\Models\SiswaProfile;
use App\Models\SiswaUjian;
use App\Models\SoalUjian;
use App\Models\Ujian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruController extends Controller
{
    protected function getGuruProfile()
    {
        return GuruProfile::where('user_id', Auth::id())->firstOrFail();
    }

    public function dashboard()
    {
        $guru = $this->getGuruProfile();

        $jadwalHariIni = JadwalPelajaran::with(['kelas', 'mataPelajaran'])
            ->where('guru_id', $guru->id)
            ->where('hari', strtolower(now()->locale('id')->isoFormat('dddd')))
            ->orderBy('jam_mulai')
            ->get();

        $ujianSaya = Ujian::with(['mataPelajaran', 'kelas', 'pesertaUjian'])
            ->where('guru_id', $guru->id)
            ->latest()
            ->take(5)
            ->get();

        return view('guru.dashboard', compact('guru', 'jadwalHariIni', 'ujianSaya'));
    }

    public function jadwalSaya()
    {
        $guru = $this->getGuruProfile();
        $jadwal = JadwalPelajaran::with(['kelas', 'mataPelajaran', 'tahunAjaran'])
            ->where('guru_id', $guru->id)
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();

        return view('guru.jadwal', compact('guru', 'jadwal'));
    }

    public function ujianIndex()
    {
        $guru = $this->getGuruProfile();
        $ujianList = Ujian::with(['mataPelajaran', 'kelas', 'soal', 'pesertaUjian'])
            ->where('guru_id', $guru->id)
            ->latest()
            ->get();
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $mapelList = MataPelajaran::orderBy('nama_mapel')->get();

        return view('guru.ujian.index', compact('ujianList', 'kelasList', 'mapelList'));
    }

    public function ujianStore(Request $request)
    {
        $guru = $this->getGuruProfile();

        $validated = $request->validate([
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'durasi_menit' => 'required|integer|min:5|max:180',
            'waktu_mulai' => 'nullable|date',
            'waktu_selesai' => 'nullable|date|after_or_equal:waktu_mulai',
            'kelas_ids' => 'required|array',
            'kelas_ids.*' => 'exists:kelas,id',
        ]);

        $ujian = Ujian::create([
            'guru_id' => $guru->id,
            'mata_pelajaran_id' => $validated['mata_pelajaran_id'],
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'durasi_menit' => $validated['durasi_menit'],
            'waktu_mulai' => $validated['waktu_mulai'] ?? now(),
            'waktu_selesai' => $validated['waktu_selesai'] ?? now()->addDays(7),
            'status' => 'terbit',
        ]);

        $ujian->kelas()->sync($validated['kelas_ids']);

        return redirect()->route('guru.ujian.detail', $ujian->id)->with('success', 'Paket ujian berhasil dibuat! Silakan tambahkan butir soal di bawah.');
    }

    public function ujianDetail($id)
    {
        $guru = $this->getGuruProfile();
        $ujian = Ujian::with(['mataPelajaran', 'kelas', 'soal.opsi', 'pesertaUjian.siswa.user'])
            ->where('guru_id', $guru->id)
            ->findOrFail($id);

        return view('guru.ujian.detail', compact('ujian'));
    }

    public function soalStore(Request $request, $ujianId)
    {
        $guru = $this->getGuruProfile();
        $ujian = Ujian::where('guru_id', $guru->id)->findOrFail($ujianId);

        $validated = $request->validate([
            'tipe_soal' => 'required|in:pilihan_ganda,esai',
            'pertanyaan' => 'required|string',
            'bobot_nilai' => 'required|numeric|min:1',
            'opsi' => 'nullable|array',
            'opsi.*' => 'nullable|string',
            'jawaban_benar' => 'nullable|in:A,B,C,D,E',
        ]);

        $nextUrutan = $ujian->soal()->count() + 1;

        $soal = SoalUjian::create([
            'ujian_id' => $ujian->id,
            'tipe_soal' => $validated['tipe_soal'],
            'pertanyaan' => $validated['pertanyaan'],
            'bobot_nilai' => $validated['bobot_nilai'],
            'urutan' => $nextUrutan,
        ]);

        if ($validated['tipe_soal'] === 'pilihan_ganda' && !empty($validated['opsi'])) {
            foreach ($validated['opsi'] as $label => $teks) {
                if (!empty($teks)) {
                    OpsiJawaban::create([
                        'soal_id' => $soal->id,
                        'label' => $label,
                        'teks_opsi' => $teks,
                        'is_benar' => ($validated['jawaban_benar'] ?? '') === $label,
                    ]);
                }
            }
        }

        return back()->with('success', 'Butir soal nomor #' . $nextUrutan . ' berhasil ditambahkan!');
    }

    public function rekapNilai($id)
    {
        $guru = $this->getGuruProfile();
        $ujian = Ujian::with(['mataPelajaran', 'kelas', 'pesertaUjian.siswa.user', 'pesertaUjian.siswa.kelas'])
            ->where('guru_id', $guru->id)
            ->findOrFail($id);

        return view('guru.ujian.rekap', compact('ujian'));
    }

    public function soalDestroy($ujianId, $soalId)
    {
        $guru = $this->getGuruProfile();
        $ujian = Ujian::where('guru_id', $guru->id)->findOrFail($ujianId);
        $soal = SoalUjian::where('ujian_id', $ujian->id)->findOrFail($soalId);

        // Hapus opsi jawaban dan butir soal untuk menghemat penyimpanan
        $soal->opsi()->delete();
        $soal->delete();

        return back()->with('success', 'Butir soal berhasil dihapus untuk menghemat penyimpanan!');
    }

    public function ujianDestroy($id)
    {
        $guru = $this->getGuruProfile();
        $ujian = Ujian::where('guru_id', $guru->id)->findOrFail($id);

        // Hapus riwayat jawaban siswa agar hemat storage database
        foreach ($ujian->pesertaUjian as $pu) {
            $pu->jawaban()->delete();
            $pu->delete();
        }

        // Hapus butir soal dan opsi
        foreach ($ujian->soal as $soal) {
            $soal->opsi()->delete();
            $soal->delete();
        }

        // Lepas relasi kelas dan hapus paket ujian
        $ujian->kelas()->detach();
        $ujian->delete();

        return redirect()->route('guru.ujian.index')->with('success', 'Paket ulangan harian yang telah selesai berhasil dihapus permanen untuk menghemat ruang penyimpanan!');
    }

    public function absenIndex()
    {
        $guru = $this->getGuruProfile();
        $hariIni = strtolower(now()->locale('id')->isoFormat('dddd'));

        $jadwalHariIni = JadwalPelajaran::with(['kelas', 'mataPelajaran'])
            ->where('guru_id', $guru->id)
            ->where('hari', $hariIni)
            ->orderBy('jam_mulai')
            ->get();

        $semuaJadwal = JadwalPelajaran::with(['kelas', 'mataPelajaran'])
            ->where('guru_id', $guru->id)
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();

        return view('guru.absen.index', compact('guru', 'jadwalHariIni', 'semuaJadwal'));
    }

    public function absenKelas($jadwal_id, Request $request)
    {
        $guru = $this->getGuruProfile();
        $jadwal = JadwalPelajaran::with(['kelas', 'mataPelajaran'])
            ->where('guru_id', $guru->id)
            ->findOrFail($jadwal_id);

        $tanggal = $request->input('tanggal', date('Y-m-d'));

        $siswaList = SiswaProfile::with(['user', 'kelas'])
            ->where('kelas_id', $jadwal->kelas_id)
            ->get();

        $presensiMap = Presensi::where('tanggal', $tanggal)
            ->where('kelas_id', $jadwal->kelas_id)
            ->get()
            ->keyBy('siswa_id');

        return view('guru.absen.kelas', compact('guru', 'jadwal', 'tanggal', 'siswaList', 'presensiMap'));
    }

    public function absenSimpan(Request $request)
    {
        $guru = $this->getGuruProfile();

        $request->validate([
            'jadwal_id' => 'required|exists:jadwal_pelajaran,id',
            'tanggal' => 'required|date',
            'kelas_id' => 'required|exists:kelas,id',
            'presensi' => 'required|array',
            'presensi.*' => 'in:hadir,sakit,izin,alfa',
        ]);

        $tanggal = $request->tanggal;
        $kelasId = $request->kelas_id;
        $jadwalId = $request->jadwal_id;

        foreach ($request->presensi as $siswaId => $status) {
            Presensi::updateOrCreate(
                [
                    'tanggal' => $tanggal,
                    'siswa_id' => $siswaId,
                ],
                [
                    'kelas_id' => $kelasId,
                    'jadwal_id' => $jadwalId,
                    'guru_id' => $guru->id,
                    'status' => $status,
                    'keterangan' => $request->input("keterangan.$siswaId"),
                ]
            );
        }

        return back()->with('success', 'Presensi kelas berhasil disimpan dan disinkronkan!');
    }

    // ===========================
    //  REKAP NILAI SISWA (per Ujian)
    // ===========================

    // rekapNilai sudah ada di bawah ujianDetail — method ini adalah rekap global per guru
    public function rekapNilaiGuru(Request $request)
    {
        $guru = $this->getGuruProfile();

        $ujianList = Ujian::with(['mataPelajaran', 'kelas', 'pesertaUjian.siswa.user', 'pesertaUjian.siswa.kelas'])
            ->where('guru_id', $guru->id)
            ->when($request->filled('kelas_id'), fn($q) => $q->whereHas('kelas', fn($k) => $k->where('kelas.id', $request->kelas_id)))
            ->when($request->filled('mapel_id'), fn($q) => $q->where('mata_pelajaran_id', $request->mapel_id))
            ->latest()
            ->get();

        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $mapelList = MataPelajaran::orderBy('nama_mapel')->get();

        // Agregat nilai per ujian
        $rekapData = $ujianList->map(function ($ujian) {
            $peserta = $ujian->pesertaUjian->where('status', 'selesai');
            return [
                'ujian'       => $ujian,
                'total_peserta' => $ujian->pesertaUjian->count(),
                'sudah_selesai' => $peserta->count(),
                'rata_rata'   => $peserta->avg('total_nilai') ?? 0,
                'nilai_max'   => $peserta->max('total_nilai') ?? 0,
                'nilai_min'   => $peserta->min('total_nilai') ?? 0,
                'lulus'       => $peserta->where('total_nilai', '>=', 70)->count(),
            ];
        });

        return view('guru.rekap.nilai', compact('guru', 'rekapData', 'kelasList', 'mapelList'));
    }

    // ===========================
    //  REKAP ABSEN SISWA (oleh Guru)
    // ===========================

    public function rekapAbsenSiswa(Request $request)
    {
        $guru = $this->getGuruProfile();

        // Kelas yang diajar oleh guru ini
        $kelasYangDiajar = JadwalPelajaran::where('guru_id', $guru->id)
            ->with('kelas')
            ->get()
            ->pluck('kelas')
            ->unique('id')
            ->values();

        $kelasId   = $request->input('kelas_id', $kelasYangDiajar->first()?->id);
        $bulan     = $request->input('bulan', now()->format('Y-m'));
        $tglMulai  = $bulan . '-01';
        $tglSelesai = date('Y-m-t', strtotime($tglMulai));

        $siswaList = collect();
        $rekapAbsen = collect();
        $kelas = null;

        if ($kelasId) {
            $kelas = Kelas::find($kelasId);
            $siswaList = SiswaProfile::with('user')
                ->where('kelas_id', $kelasId)
                ->get();

            // Semua presensi pada periode yang diminta untuk kelas ini
            $presensiPeriode = Presensi::where('kelas_id', $kelasId)
                ->whereBetween('tanggal', [$tglMulai, $tglSelesai])
                ->get();

            // Hitung per siswa
            $rekapAbsen = $siswaList->map(function ($siswa) use ($presensiPeriode) {
                $ps = $presensiPeriode->where('siswa_id', $siswa->id);
                return [
                    'siswa'  => $siswa,
                    'hadir'  => $ps->where('status', 'hadir')->count(),
                    'sakit'  => $ps->where('status', 'sakit')->count(),
                    'izin'   => $ps->where('status', 'izin')->count(),
                    'alfa'   => $ps->where('status', 'alfa')->count(),
                    'total'  => $ps->count(),
                ];
            });
        }

        return view('guru.rekap.absen-siswa', compact(
            'guru', 'kelasYangDiajar', 'kelasId', 'bulan', 'kelas',
            'siswaList', 'rekapAbsen', 'tglMulai', 'tglSelesai'
        ));
    }

    // ===========================
    //  ABSEN KEHADIRAN GURU SENDIRI (Check-In Harian)
    // ===========================

    public function kehadiranIndex()
    {
        $guru = $this->getGuruProfile();

        $presensiHariIni = PresensiGuru::where('guru_id', $guru->id)
            ->whereDate('tanggal', today())
            ->first();

        $riwayatPresensi = PresensiGuru::where('guru_id', $guru->id)
            ->orderBy('tanggal', 'desc')
            ->paginate(15);

        $bulanIni = now()->format('Y-m');
        $tglMulai = $bulanIni . '-01';
        $tglSelesai = date('Y-m-t', strtotime($tglMulai));

        $presensiBulanIni = PresensiGuru::where('guru_id', $guru->id)
            ->whereBetween('tanggal', [$tglMulai, $tglSelesai])
            ->get();

        $stats = [
            'total' => $presensiBulanIni->count(),
            'hadir' => $presensiBulanIni->where('status', 'hadir')->count(),
            'sakit' => $presensiBulanIni->where('status', 'sakit')->count(),
            'izin'  => $presensiBulanIni->where('status', 'izin')->count(),
            'alfa'  => $presensiBulanIni->where('status', 'alfa')->count(),
        ];

        return view('guru.kehadiran.index', compact('guru', 'presensiHariIni', 'riwayatPresensi', 'stats'));
    }

    public function kehadiranCheckin(Request $request)
    {
        $guru = $this->getGuruProfile();

        $request->validate([
            'status' => 'required|in:hadir,sakit,izin',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $status = $request->status;
        $keterangan = $request->keterangan;

        if (!$keterangan) {
            $keterangan = match($status) {
                'hadir' => 'Presensi mandiri guru hadir',
                'sakit' => 'Izin sakit guru',
                'izin'  => 'Izin keperluan dinas / pribadi',
                default => null
            };
        }

        $presensi = PresensiGuru::where('guru_id', $guru->id)
            ->whereDate('tanggal', today())
            ->first();

        if ($presensi) {
            $presensi->update([
                'status'     => $status,
                'keterangan' => $keterangan,
            ]);
        } else {
            PresensiGuru::create([
                'tanggal'    => today()->format('Y-m-d'),
                'guru_id'    => $guru->id,
                'status'     => $status,
                'keterangan' => $keterangan,
            ]);
        }

        $statusText = match($status) {
            'hadir' => 'Hadir',
            'sakit' => 'Sakit',
            'izin'  => 'Izin',
            default => 'Hadir'
        };

        return back()->with('success', "Presensi hari ini ({$statusText}) berhasil dicatat!");
    }

    // ===========================
    //  REKAP ABSEN GURU SENDIRI (Presensi Harian & Jurnal Mengajar)
    // ===========================

    public function rekapAbsenGuru(Request $request)
    {
        $guru = $this->getGuruProfile();
        $bulan = $request->input('bulan', now()->format('Y-m'));
        $tglMulai = $bulan . '-01';
        $tglSelesai = date('Y-m-t', strtotime($tglMulai));

        // Presensi harian guru dari presensi_guru
        $presensiHarian = PresensiGuru::where('guru_id', $guru->id)
            ->whereBetween('tanggal', [$tglMulai, $tglSelesai])
            ->orderBy('tanggal', 'desc')
            ->get();

        $statsHarian = [
            'hadir' => $presensiHarian->where('status', 'hadir')->count(),
            'sakit' => $presensiHarian->where('status', 'sakit')->count(),
            'izin'  => $presensiHarian->where('status', 'izin')->count(),
            'alfa'  => $presensiHarian->where('status', 'alfa')->count(),
            'total' => $presensiHarian->count(),
        ];
        $totalRecord = $statsHarian['total'];
        $statsHarian['persen'] = $totalRecord > 0 ? round(($statsHarian['hadir'] / $totalRecord) * 100, 1) : 0;

        // Riwayat sesi mengajar di kelas
        $logMengajar = Presensi::with(['kelas', 'jadwal.mataPelajaran'])
            ->where('guru_id', $guru->id)
            ->whereBetween('tanggal', [$tglMulai, $tglSelesai])
            ->select('tanggal', 'kelas_id', 'jadwal_id', 'guru_id')
            ->distinct()
            ->orderBy('tanggal', 'desc')
            ->get();

        $totalSesi = $logMengajar->count();

        // Jadwal aktif guru
        $jadwalList = JadwalPelajaran::with(['kelas', 'mataPelajaran'])
            ->where('guru_id', $guru->id)
            ->get();

        return view('guru.rekap.absen-guru', compact('guru', 'presensiHarian', 'statsHarian', 'logMengajar', 'bulan', 'totalSesi', 'jadwalList', 'tglMulai', 'tglSelesai'));
    }
}


