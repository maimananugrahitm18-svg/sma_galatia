<?php

namespace App\Http\Controllers;

use App\Models\GuruProfile;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Presensi;
use App\Models\PresensiGuru;
use App\Models\SiswaProfile;
use App\Models\SiswaUjian;
use App\Models\TahunAjaran;
use App\Models\Ujian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_guru' => GuruProfile::count(),
            'total_siswa' => SiswaProfile::count(),
            'total_kelas' => Kelas::count(),
            'total_mapel' => MataPelajaran::count(),
            'total_jadwal' => JadwalPelajaran::count(),
            'total_ujian' => Ujian::count(),
        ];

        $jadwalHariIni = JadwalPelajaran::with(['guru.user', 'kelas', 'mataPelajaran'])
            ->where('hari', strtolower(now()->locale('id')->isoFormat('dddd')))
            ->orderBy('jam_mulai')
            ->get();

        $ujianTerbaru = Ujian::with(['guru.user', 'mataPelajaran', 'kelas'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'jadwalHariIni', 'ujianTerbaru'));
    }

    public function jadwalIndex(Request $request)
    {
        $query = JadwalPelajaran::with(['tahunAjaran', 'kelas', 'mataPelajaran', 'guru.user']);

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        if ($request->filled('guru_id')) {
            $query->where('guru_id', $request->guru_id);
        }

        if ($request->filled('hari')) {
            $query->where('hari', $request->hari);
        }

        $jadwalList = $query->orderBy('hari')->orderBy('jam_mulai')->get();
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $guruList = GuruProfile::with('user')->get();
        $mapelList = MataPelajaran::orderBy('nama_mapel')->get();
        $tahunAjaran = TahunAjaran::where('is_active', true)->first();

        return view('admin.jadwal.index', compact('jadwalList', 'kelasList', 'guruList', 'mapelList', 'tahunAjaran'));
    }

    public function jadwalStore(Request $request)
    {
        $validated = $request->validate([
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'guru_id' => 'required|exists:guru_profiles,id',
            'hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'ruangan' => 'nullable|string|max:50',
        ]);

        // Cek Bentrok 1: Apakah GURU sedang mengajar di kelas lain pada jam dan hari yang sama?
        $bentrokGuru = JadwalPelajaran::where('tahun_ajaran_id', $validated['tahun_ajaran_id'])
            ->where('guru_id', $validated['guru_id'])
            ->where('hari', $validated['hari'])
            ->where(function ($q) use ($validated) {
                $q->whereBetween('jam_mulai', [$validated['jam_mulai'], $validated['jam_selesai']])
                  ->orWhereBetween('jam_selesai', [$validated['jam_mulai'], $validated['jam_selesai']])
                  ->orWhere(function ($sub) use ($validated) {
                      $sub->where('jam_mulai', '<=', $validated['jam_mulai'])
                          ->where('jam_selesai', '>=', $validated['jam_selesai']);
                  });
            })
            ->first();

        if ($bentrokGuru) {
            return back()->withInput()->with('error', 'Gagal: Guru yang bersangkutan sudah memiliki jadwal mengajar di kelas lain pada jam tersebut (' . $bentrokGuru->jam_mulai . ' - ' . $bentrokGuru->jam_selesai . ').');
        }

        // Cek Bentrok 2: Apakah KELAS sudah ada jadwal mata pelajaran lain di rentang jam tersebut?
        $bentrokKelas = JadwalPelajaran::where('tahun_ajaran_id', $validated['tahun_ajaran_id'])
            ->where('kelas_id', $validated['kelas_id'])
            ->where('hari', $validated['hari'])
            ->where(function ($q) use ($validated) {
                $q->whereBetween('jam_mulai', [$validated['jam_mulai'], $validated['jam_selesai']])
                  ->orWhereBetween('jam_selesai', [$validated['jam_mulai'], $validated['jam_selesai']])
                  ->orWhere(function ($sub) use ($validated) {
                      $sub->where('jam_mulai', '<=', $validated['jam_mulai'])
                          ->where('jam_selesai', '>=', $validated['jam_selesai']);
                  });
            })
            ->first();

        if ($bentrokKelas) {
            return back()->withInput()->with('error', 'Gagal: Kelas tersebut sudah memiliki jadwal pelajaran lain pada jam tersebut.');
        }

        JadwalPelajaran::create($validated);

        return back()->with('success', 'Jadwal pelajaran berhasil ditambahkan tanpa konflik!');
    }

    public function jadwalDestroy($id)
    {
        $jadwal = JadwalPelajaran::findOrFail($id);
        $jadwal->delete();

        return back()->with('success', 'Jadwal pelajaran berhasil dihapus.');
    }

    public function userIndex(Request $request)
    {
        $query = User::with(['roles', 'guruProfile', 'siswaProfile.kelas']);

        if ($request->filled('role')) {
            $query->role($request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15);
        $kelasList = Kelas::orderBy('nama_kelas')->get();

        return view('admin.users.index', compact('users', 'kelasList'));
    }

    public function userStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,guru,siswa,super-admin',
            // Guru fields
            'nip' => 'nullable|string|max:30|unique:guru_profiles,nip',
            'gelar_depan' => 'nullable|string|max:20',
            'gelar_belakang' => 'nullable|string|max:20',
            // Siswa fields
            'nis' => 'nullable|string|max:20|unique:siswa_profiles,nis',
            'nisn' => 'nullable|string|max:20|unique:siswa_profiles,nisn',
            'kelas_id' => 'nullable|exists:kelas,id',
            'no_hp' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole($validated['role']);

        if ($validated['role'] === 'guru') {
            GuruProfile::create([
                'user_id' => $user->id,
                'nip' => $validated['nip'] ?? null,
                'gelar_depan' => $validated['gelar_depan'] ?? null,
                'gelar_belakang' => $validated['gelar_belakang'] ?? null,
                'no_hp' => $validated['no_hp'] ?? null,
            ]);
        } elseif ($validated['role'] === 'siswa') {
            SiswaProfile::create([
                'user_id' => $user->id,
                'nis' => $validated['nis'] ?? ('S' . rand(100000, 999999)),
                'nisn' => $validated['nisn'] ?? null,
                'kelas_id' => $validated['kelas_id'] ?? null,
                'no_hp' => $validated['no_hp'] ?? null,
                'tahun_masuk' => date('Y'),
            ]);
        }

        return back()->with('success', 'Akun ' . ucfirst($validated['role']) . ' berhasil ditambahkan oleh Admin!');
    }

    public function userDestroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return back()->with('success', 'Akun pengguna berhasil dihapus.');
    }

    public function absenIndex(Request $request)
    {
        $tanggal = $request->input('tanggal', date('Y-m-d'));
        $kelasId = $request->input('kelas_id');

        $kelasList = Kelas::orderBy('nama_kelas')->get();
        if (!$kelasId && $kelasList->isNotEmpty()) {
            $kelasId = $kelasList->first()->id;
        }

        $siswaList = collect();
        if ($kelasId) {
            $siswaList = SiswaProfile::with(['user', 'kelas'])
                ->where('kelas_id', $kelasId)
                ->get();
        }

        // Ambil presensi yang sudah tercatat
        $presensiMap = Presensi::where('tanggal', $tanggal)
            ->where('kelas_id', $kelasId)
            ->get()
            ->keyBy('siswa_id');

        // Statistik kehadiran hari ini
        $stats = [
            'total_siswa' => $siswaList->count(),
            'hadir'       => $presensiMap->where('status', 'hadir')->count(),
            'sakit'       => $presensiMap->where('status', 'sakit')->count(),
            'izin'        => $presensiMap->where('status', 'izin')->count(),
            'alfa'        => $presensiMap->where('status', 'alfa')->count(),
        ];

        return view('admin.absen.index', compact('kelasList', 'kelasId', 'tanggal', 'siswaList', 'presensiMap', 'stats'));
    }

    public function absenStore(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kelas_id' => 'required|exists:kelas,id',
            'presensi' => 'required|array',
            'presensi.*' => 'in:hadir,sakit,izin,alfa',
        ]);

        $tanggal = $request->tanggal;
        $kelasId = $request->kelas_id;

        foreach ($request->presensi as $siswaId => $status) {
            Presensi::updateOrCreate(
                [
                    'tanggal' => $tanggal,
                    'siswa_id' => $siswaId,
                ],
                [
                    'kelas_id'   => $kelasId,
                    'status'     => $status,
                    'keterangan' => $request->input("keterangan.$siswaId"),
                ]
            );
        }

        return back()->with('success', 'Data presensi siswa berhasil disimpan!');
    }

    // ===========================
    //  MANAJEMEN ABSENSI GURU (Admin)
    // ===========================

    public function absenGuruIndex(Request $request)
    {
        $tanggal = $request->input('tanggal', date('Y-m-d'));

        $guruList = GuruProfile::with('user')->get();

        $presensiMap = PresensiGuru::where('tanggal', $tanggal)
            ->get()
            ->keyBy('guru_id');

        $stats = [
            'total_guru'  => $guruList->count(),
            'hadir'       => $presensiMap->where('status', 'hadir')->count(),
            'sakit'       => $presensiMap->where('status', 'sakit')->count(),
            'izin'        => $presensiMap->where('status', 'izin')->count(),
            'alfa'        => $presensiMap->where('status', 'alfa')->count(),
            'belum_absen' => $guruList->count() - $presensiMap->count(),
        ];

        return view('admin.absen-guru.index', compact('tanggal', 'guruList', 'presensiMap', 'stats'));
    }

    public function absenGuruStore(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'presensi' => 'required|array',
            'presensi.*' => 'in:hadir,sakit,izin,alfa',
        ]);

        $tanggal = $request->tanggal;

        foreach ($request->presensi as $guruId => $status) {
            $keterangan = $request->input("keterangan.$guruId");

            PresensiGuru::updateOrCreate(
                [
                    'tanggal' => $tanggal,
                    'guru_id' => $guruId,
                ],
                [
                    'status'     => $status,
                    'keterangan' => $keterangan,
                ]
            );
        }

        return back()->with('success', 'Presensi seluruh guru berhasil diperbarui!');
    }

    // ===========================
    //  MANAJEMEN KELAS
    // ===========================

    public function kelasIndex()
    {
        $kelasList = Kelas::with(['waliKelas.user'])
            ->withCount('siswa')
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get();
        $guruList = GuruProfile::with('user')->get();

        return view('admin.kelas.index', compact('kelasList', 'guruList'));
    }

    public function kelasStore(Request $request)
    {
        $validated = $request->validate([
            'nama_kelas'   => 'required|string|max:50|unique:kelas,nama_kelas',
            'tingkat'      => 'required|in:10,11,12',
            'wali_guru_id' => 'nullable|exists:guru_profiles,id',
        ]);

        Kelas::create($validated);

        return back()->with('success', 'Kelas "' . $validated['nama_kelas'] . '" berhasil ditambahkan!');
    }

    public function kelasUpdate(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $validated = $request->validate([
            'nama_kelas'   => 'required|string|max:50|unique:kelas,nama_kelas,' . $id,
            'tingkat'      => 'required|in:10,11,12',
            'wali_guru_id' => 'nullable|exists:guru_profiles,id',
        ]);

        $kelas->update($validated);

        return back()->with('success', 'Data kelas berhasil diperbarui!');
    }

    public function kelasDestroy($id)
    {
        $kelas = Kelas::findOrFail($id);

        if ($kelas->siswa()->count() > 0) {
            return back()->with('error', 'Gagal: Kelas ini masih memiliki ' . $kelas->siswa()->count() . ' siswa. Pindahkan siswa terlebih dahulu.');
        }

        if ($kelas->jadwalPelajaran()->count() > 0) {
            return back()->with('error', 'Gagal: Kelas ini masih terdaftar di ' . $kelas->jadwalPelajaran()->count() . ' jadwal pelajaran. Hapus jadwal terlebih dahulu.');
        }

        $kelas->delete();

        return back()->with('success', 'Kelas berhasil dihapus.');
    }

    // ===========================
    //  MANAJEMEN MATA PELAJARAN
    // ===========================

    public function mapelIndex()
    {
        $mapelList = MataPelajaran::withCount('jadwalPelajaran')
            ->orderBy('kategori')
            ->orderBy('nama_mapel')
            ->get();

        return view('admin.mapel.index', compact('mapelList'));
    }

    public function mapelStore(Request $request)
    {
        $validated = $request->validate([
            'kode_mapel' => 'required|string|max:20|unique:mata_pelajaran,kode_mapel',
            'nama_mapel' => 'required|string|max:100',
            'kategori'   => 'required|in:umum,peminatan,muatan_lokal',
        ]);

        MataPelajaran::create($validated);

        return back()->with('success', 'Mata pelajaran "' . $validated['nama_mapel'] . '" berhasil ditambahkan!');
    }

    public function mapelUpdate(Request $request, $id)
    {
        $mapel = MataPelajaran::findOrFail($id);

        $validated = $request->validate([
            'kode_mapel' => 'required|string|max:20|unique:mata_pelajaran,kode_mapel,' . $id,
            'nama_mapel' => 'required|string|max:100',
            'kategori'   => 'required|in:umum,peminatan,muatan_lokal',
        ]);

        $mapel->update($validated);

        return back()->with('success', 'Data mata pelajaran berhasil diperbarui!');
    }

    public function mapelDestroy($id)
    {
        $mapel = MataPelajaran::findOrFail($id);

        if ($mapel->jadwalPelajaran()->count() > 0) {
            return back()->with('error', 'Gagal: Mata pelajaran ini masih terdaftar di ' . $mapel->jadwalPelajaran()->count() . ' jadwal. Hapus jadwal terlebih dahulu.');
        }

        if ($mapel->ujian()->count() > 0) {
            return back()->with('error', 'Gagal: Mata pelajaran ini memiliki ' . $mapel->ujian()->count() . ' ujian aktif. Hapus ujian terlebih dahulu.');
        }

        $mapel->delete();

        return back()->with('success', 'Mata pelajaran berhasil dihapus.');
    }

    // ===========================
    //  REKAP ABSEN SISWA (Admin)
    // ===========================

    public function rekapAbsenSiswa(Request $request)
    {
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $kelasId   = $request->input('kelas_id', $kelasList->first()?->id);
        $bulan     = $request->input('bulan', now()->format('Y-m'));
        $tglMulai  = $bulan . '-01';
        $tglSelesai = date('Y-m-t', strtotime($tglMulai));

        $kelas = null;
        $siswaList = collect();
        $rekapAbsen = collect();
        $ringkasan = [
            'total_siswa' => 0,
            'total_hadir' => 0,
            'total_sakit' => 0,
            'total_izin'  => 0,
            'total_alfa'  => 0,
            'persentase_kehadiran' => 0,
        ];

        if ($kelasId) {
            $kelas = Kelas::find($kelasId);
            $siswaList = SiswaProfile::with('user')
                ->where('kelas_id', $kelasId)
                ->get();

            $presensiPeriode = Presensi::where('kelas_id', $kelasId)
                ->whereBetween('tanggal', [$tglMulai, $tglSelesai])
                ->get();

            $totalHadir = 0;
            $totalSakit = 0;
            $totalIzin  = 0;
            $totalAlfa  = 0;

            $rekapAbsen = $siswaList->map(function ($siswa) use ($presensiPeriode, &$totalHadir, &$totalSakit, &$totalIzin, &$totalAlfa) {
                $ps = $presensiPeriode->where('siswa_id', $siswa->id);
                $hadir = $ps->where('status', 'hadir')->count();
                $sakit = $ps->where('status', 'sakit')->count();
                $izin  = $ps->where('status', 'izin')->count();
                $alfa  = $ps->where('status', 'alfa')->count();
                $total = $hadir + $sakit + $izin + $alfa;

                $totalHadir += $hadir;
                $totalSakit += $sakit;
                $totalIzin  += $izin;
                $totalAlfa  += $alfa;

                $persen = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;

                return [
                    'siswa'      => $siswa,
                    'hadir'      => $hadir,
                    'sakit'      => $sakit,
                    'izin'       => $izin,
                    'alfa'       => $alfa,
                    'total'      => $total,
                    'persentase' => $persen,
                ];
            });

            $grandTotalRecord = $totalHadir + $totalSakit + $totalIzin + $totalAlfa;
            $ringkasan = [
                'total_siswa'          => $siswaList->count(),
                'total_hadir'          => $totalHadir,
                'total_sakit'          => $totalSakit,
                'total_izin'           => $totalIzin,
                'total_alfa'           => $totalAlfa,
                'persentase_kehadiran' => $grandTotalRecord > 0 ? round(($totalHadir / $grandTotalRecord) * 100, 1) : 0,
            ];
        }

        return view('admin.rekap.absen-siswa', compact(
            'kelasList', 'kelasId', 'bulan', 'kelas',
            'siswaList', 'rekapAbsen', 'ringkasan', 'tglMulai', 'tglSelesai'
        ));
    }

    // ===========================
    //  REKAP ABSENSI GURU (Admin)
    // ===========================

    public function rekapAbsenGuru(Request $request)
    {
        $bulan     = $request->input('bulan', now()->format('Y-m'));
        $tglMulai  = $bulan . '-01';
        $tglSelesai = date('Y-m-t', strtotime($tglMulai));

        $guruList = GuruProfile::with(['user', 'jadwalPelajaran.mataPelajaran', 'jadwalPelajaran.kelas'])
            ->get();

        // Data presensi harian seluruh guru pada bulan ini
        $presensiHarian = PresensiGuru::whereBetween('tanggal', [$tglMulai, $tglSelesai])->get();

        // Ambil data presensi sesi mengajar di kelas pada bulan ini
        $sesiPresensi = Presensi::whereNotNull('guru_id')
            ->whereBetween('tanggal', [$tglMulai, $tglSelesai])
            ->select('guru_id', 'tanggal', 'jadwal_id', 'kelas_id')
            ->distinct()
            ->get();

        $rekapGuru = $guruList->map(function ($guru) use ($presensiHarian, $sesiPresensi) {
            $ph = $presensiHarian->where('guru_id', $guru->id);
            $hadir     = $ph->where('status', 'hadir')->count();
            $sakit     = $ph->where('status', 'sakit')->count();
            $izin      = $ph->where('status', 'izin')->count();
            $alfa      = $ph->where('status', 'alfa')->count();
            $totalHari = $hadir + $sakit + $izin + $alfa;
            $persen    = $totalHari > 0 ? round(($hadir / $totalHari) * 100, 1) : 0;

            $sesi = $sesiPresensi->where('guru_id', $guru->id);
            $totalJadwal = $guru->jadwalPelajaran->count();
            $totalSesiMengajar = $sesi->count();

            return [
                'guru'               => $guru,
                'hadir'              => $hadir,
                'sakit'              => $sakit,
                'izin'               => $izin,
                'alfa'               => $alfa,
                'total_hari'         => $totalHari,
                'persentase'         => $persen,
                'total_jadwal'       => $totalJadwal,
                'total_sesi'         => $totalSesiMengajar,
                'mapel'              => $guru->jadwalPelajaran->pluck('mataPelajaran.nama_mapel')->unique()->implode(', ') ?: '-',
                'kelas'              => $guru->jadwalPelajaran->pluck('kelas.nama_kelas')->unique()->implode(', ') ?: '-',
            ];
        });

        $totalHadirAll = $presensiHarian->where('status', 'hadir')->count();
        $totalSakitAll = $presensiHarian->where('status', 'sakit')->count();
        $totalIzinAll  = $presensiHarian->where('status', 'izin')->count();
        $totalAlfaAll  = $presensiHarian->where('status', 'alfa')->count();
        $grandTotal    = $totalHadirAll + $totalSakitAll + $totalIzinAll + $totalAlfaAll;
        $ringkasan = [
            'total_guru'  => $guruList->count(),
            'total_hadir' => $totalHadirAll,
            'total_sakit' => $totalSakitAll,
            'total_izin'  => $totalIzinAll,
            'total_alfa'  => $totalAlfaAll,
            'persentase'  => $grandTotal > 0 ? round(($totalHadirAll / $grandTotal) * 100, 1) : 0,
            'total_sesi'  => $sesiPresensi->count(),
        ];

        return view('admin.rekap.absen-guru', compact('guruList', 'rekapGuru', 'bulan', 'ringkasan', 'tglMulai', 'tglSelesai'));
    }

    // ===========================
    //  REKAP NILAI SISWA (Admin)
    // ===========================

    public function rekapNilai(Request $request)
    {
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $mapelList = MataPelajaran::orderBy('nama_mapel')->get();

        $ujianQuery = Ujian::with(['mataPelajaran', 'kelas', 'guru.user', 'pesertaUjian.siswa.user', 'pesertaUjian.siswa.kelas'])
            ->latest();

        if ($request->filled('kelas_id')) {
            $ujianQuery->whereHas('kelas', fn($q) => $q->where('kelas.id', $request->kelas_id));
        }

        if ($request->filled('mapel_id')) {
            $ujianQuery->where('mata_pelajaran_id', $request->mapel_id);
        }

        $ujianList = $ujianQuery->get();

        $rekapUjian = $ujianList->map(function ($ujian) {
            $peserta = $ujian->pesertaUjian->where('status', 'selesai');
            $countSelesai = $peserta->count();

            return [
                'ujian'         => $ujian,
                'total_peserta' => $ujian->pesertaUjian->count(),
                'sudah_selesai' => $countSelesai,
                'rata_rata'     => $countSelesai > 0 ? round($peserta->avg('total_nilai'), 1) : 0,
                'nilai_max'     => $countSelesai > 0 ? $peserta->max('total_nilai') : 0,
                'nilai_min'     => $countSelesai > 0 ? $peserta->min('total_nilai') : 0,
                'lulus'         => $peserta->where('total_nilai', '>=', 70)->count(),
                'belum_lulus'   => $peserta->where('total_nilai', '<', 70)->count(),
            ];
        });

        return view('admin.rekap.nilai', compact('rekapUjian', 'kelasList', 'mapelList'));
    }
}

