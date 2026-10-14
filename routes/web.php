<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\SuperAdminController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Public Routes (Landing Page Portofolio Sekolah)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/berita/{slug}', [HomeController::class, 'detailBerita'])->name('berita.detail');

/*
|--------------------------------------------------------------------------
| Authentication Routes (Login Only — Register hanya oleh Admin)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Profile Routes — Semua User yang Sudah Login
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->name('profile.')->group(function () {
    Route::post('/profile/name',     [ProfileController::class, 'updateName'])->name('name.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Super Admin & Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:super-admin|admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Manajemen Absensi Siswa
    Route::get('/absen', [AdminController::class, 'absenIndex'])->name('absen.index');
    Route::post('/absen', [AdminController::class, 'absenStore'])->name('absen.store');

    // Manajemen Absensi Guru (oleh Admin)
    Route::get('/absen-guru', [AdminController::class, 'absenGuruIndex'])->name('absen-guru.index');
    Route::post('/absen-guru', [AdminController::class, 'absenGuruStore'])->name('absen-guru.store');

    // Manajemen Jadwal Mengajar
    Route::get('/jadwal', [AdminController::class, 'jadwalIndex'])->name('jadwal.index');
    Route::post('/jadwal', [AdminController::class, 'jadwalStore'])->name('jadwal.store');
    Route::delete('/jadwal/{id}', [AdminController::class, 'jadwalDestroy'])->name('jadwal.destroy');

    // Manajemen Kelas
    Route::get('/kelas', [AdminController::class, 'kelasIndex'])->name('kelas.index');
    Route::post('/kelas', [AdminController::class, 'kelasStore'])->name('kelas.store');
    Route::put('/kelas/{id}', [AdminController::class, 'kelasUpdate'])->name('kelas.update');
    Route::delete('/kelas/{id}', [AdminController::class, 'kelasDestroy'])->name('kelas.destroy');

    // Manajemen Mata Pelajaran
    Route::get('/mapel', [AdminController::class, 'mapelIndex'])->name('mapel.index');
    Route::post('/mapel', [AdminController::class, 'mapelStore'])->name('mapel.store');
    Route::put('/mapel/{id}', [AdminController::class, 'mapelUpdate'])->name('mapel.update');
    Route::delete('/mapel/{id}', [AdminController::class, 'mapelDestroy'])->name('mapel.destroy');

    // Manajemen Akun Pengguna (hanya Admin yang bisa tambah akun)
    Route::get('/users', [AdminController::class, 'userIndex'])->name('users.index');
    Route::post('/users', [AdminController::class, 'userStore'])->name('users.store');
    Route::delete('/users/{id}', [AdminController::class, 'userDestroy'])->name('users.destroy');

    // Reset Password Pengguna oleh Admin
    Route::get('/users/reset-password', [ProfileController::class, 'resetPasswordView'])->name('users.reset-password');
    Route::post('/users/{id}/reset-password', [ProfileController::class, 'resetPassword'])->name('users.do-reset-password');

    // Laporan & Rekapitulasi (Admin)
    Route::get('/rekap/absen-siswa', [AdminController::class, 'rekapAbsenSiswa'])->name('rekap.absen-siswa');
    Route::get('/rekap/absen-guru', [AdminController::class, 'rekapAbsenGuru'])->name('rekap.absen-guru');
    Route::get('/rekap/nilai', [AdminController::class, 'rekapNilai'])->name('rekap.nilai');
});

/*
|--------------------------------------------------------------------------
| Super Admin Only Routes (Kelola Landing Page)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:super-admin'])->prefix('super-admin')->name('superadmin.')->group(function () {
    Route::get('/landing', [SuperAdminController::class, 'editLanding'])->name('landing.edit');
    Route::post('/landing', [SuperAdminController::class, 'updateLanding'])->name('landing.update');
});

/*
|--------------------------------------------------------------------------
| Guru Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [GuruController::class, 'dashboard'])->name('dashboard');
    Route::get('/jadwal', [GuruController::class, 'jadwalSaya'])->name('jadwal.saya');
    
    // Modul Kehadiran Mandiri Guru (Absen Guru Harian)
    Route::get('/kehadiran', [GuruController::class, 'kehadiranIndex'])->name('kehadiran.index');
    Route::post('/kehadiran/checkin', [GuruController::class, 'kehadiranCheckin'])->name('kehadiran.checkin');

    // Modul Absensi Siswa oleh Guru
    Route::get('/absen', [GuruController::class, 'absenIndex'])->name('absen.index');
    Route::get('/absen/{jadwal_id}', [GuruController::class, 'absenKelas'])->name('absen.kelas');
    Route::post('/absen', [GuruController::class, 'absenSimpan'])->name('absen.simpan');

    // Modul Ulangan Harian & Bank Soal
    Route::get('/ujian', [GuruController::class, 'ujianIndex'])->name('ujian.index');
    Route::post('/ujian', [GuruController::class, 'ujianStore'])->name('ujian.store');
    Route::get('/ujian/{id}', [GuruController::class, 'ujianDetail'])->name('ujian.detail');
    Route::delete('/ujian/{id}', [GuruController::class, 'ujianDestroy'])->name('ujian.destroy');
    Route::post('/ujian/{id}/soal', [GuruController::class, 'soalStore'])->name('ujian.soal.store');
    Route::delete('/ujian/{ujianId}/soal/{soalId}', [GuruController::class, 'soalDestroy'])->name('ujian.soal.destroy');
    Route::get('/ujian/{id}/rekap', [GuruController::class, 'rekapNilai'])->name('ujian.rekap');

    // Modul Laporan & Rekapitulasi (Guru)
    Route::get('/rekap/nilai', [GuruController::class, 'rekapNilaiGuru'])->name('rekap.nilai');
    Route::get('/rekap/absen-siswa', [GuruController::class, 'rekapAbsenSiswa'])->name('rekap.absen-siswa');
    Route::get('/rekap/absen-guru', [GuruController::class, 'rekapAbsenGuru'])->name('rekap.absen-guru');
});

/*
|--------------------------------------------------------------------------
| Siswa Routes (Termasuk CBT Mobile Ulangan Harian)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [SiswaController::class, 'dashboard'])->name('dashboard');
    Route::get('/jadwal', [SiswaController::class, 'jadwalSaya'])->name('jadwal.saya');
    
    // Modul Absensi & Riwayat Kehadiran Siswa
    Route::get('/absen', [SiswaController::class, 'absenIndex'])->name('absen.index');
    Route::post('/absen/checkin', [SiswaController::class, 'absenCheckin'])->name('absen.checkin');

    // Modul Ujian Siswa
    Route::get('/ujian', [SiswaController::class, 'ujianIndex'])->name('ujian.index');
    Route::get('/ujian/{id}/kerjakan', [SiswaController::class, 'kerjakanUjian'])->name('ujian.kerjakan');
    Route::post('/ujian/simpan-jawaban', [SiswaController::class, 'simpanJawabanAjax'])->name('ujian.simpan-jawaban');
    Route::post('/ujian/{id}/submit', [SiswaController::class, 'submitUjian'])->name('ujian.submit');
    Route::get('/ujian/{id}/hasil', [SiswaController::class, 'hasilUjian'])->name('ujian.hasil');
});
