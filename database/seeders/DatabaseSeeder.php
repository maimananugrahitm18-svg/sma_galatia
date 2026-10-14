<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\GuruProfile;
use App\Models\SiswaProfile;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\MataPelajaran;
use App\Models\JadwalPelajaran;
use App\Models\Ujian;
use App\Models\SoalUjian;
use App\Models\OpsiJawaban;
use App\Models\Berita;
use App\Models\Galeri;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Roles
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $guruRole = Role::firstOrCreate(['name' => 'guru']);
        $siswaRole = Role::firstOrCreate(['name' => 'siswa']);

        // 2. Tahun Ajaran
        $ta = TahunAjaran::create([
            'tahun' => '2026/2027',
            'semester' => 'ganjil',
            'is_active' => true,
        ]);

        // 3. User Super Admin
        $superAdmin = User::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@galatia.sch.id',
            'password' => Hash::make('password'),
        ]);
        $superAdmin->assignRole($superAdminRole);

        // 4. User Admin TU
        $admin = User::create([
            'name' => 'Tata Usaha SMA Galatia',
            'email' => 'admin@galatia.sch.id',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole($adminRole);

        // 5. User Guru
        $guruUser = User::create([
            'name' => 'Budi Santoso',
            'email' => 'guru@galatia.sch.id',
            'password' => Hash::make('password'),
        ]);
        $guruUser->assignRole($guruRole);

        $guruProfile = GuruProfile::create([
            'user_id' => $guruUser->id,
            'nip' => '198501152010011005',
            'gelar_depan' => 'Drs.',
            'gelar_belakang' => 'M.Pd',
            'jenis_kelamin' => 'L',
            'no_hp' => '081234567890',
        ]);

        // 6. User Guru 2 (untuk demo jadwal)
        $guruUser2 = User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@galatia.sch.id',
            'password' => Hash::make('password'),
        ]);
        $guruUser2->assignRole($guruRole);

        $guruProfile2 = GuruProfile::create([
            'user_id' => $guruUser2->id,
            'nip' => '199002202015022003',
            'gelar_depan' => '',
            'gelar_belakang' => 'S.Pd',
            'jenis_kelamin' => 'P',
            'no_hp' => '081398765432',
        ]);

        // 7. Data Master Kelas
        $kelasX = Kelas::create([
            'nama_kelas' => 'X-MIPA-1',
            'tingkat' => '10',
            'wali_guru_id' => $guruProfile->id,
        ]);

        $kelasXI = Kelas::create([
            'nama_kelas' => 'XI-MIPA-1',
            'tingkat' => '11',
            'wali_guru_id' => $guruProfile2->id,
        ]);

        // 8. User Siswa
        $siswaUser = User::create([
            'name' => 'Kevin Pratama',
            'email' => 'siswa@galatia.sch.id',
            'password' => Hash::make('password'),
        ]);
        $siswaUser->assignRole($siswaRole);

        $siswaProfile = SiswaProfile::create([
            'user_id' => $siswaUser->id,
            'nis' => '20261001',
            'nisn' => '0065432198',
            'kelas_id' => $kelasX->id,
            'jenis_kelamin' => 'L',
            'no_hp' => '087812345678',
            'tahun_masuk' => 2026,
        ]);

        // 9. Mata Pelajaran
        $mapelMat = MataPelajaran::create([
            'kode_mapel' => 'MAT-10',
            'nama_mapel' => 'Matematika Wajib',
            'kategori' => 'umum',
        ]);

        $mapelFis = MataPelajaran::create([
            'kode_mapel' => 'FIS-10',
            'nama_mapel' => 'Fisika',
            'kategori' => 'peminatan',
        ]);

        $mapelBindo = MataPelajaran::create([
            'kode_mapel' => 'BIN-10',
            'nama_mapel' => 'Bahasa Indonesia',
            'kategori' => 'umum',
        ]);

        $mapelBing = MataPelajaran::create([
            'kode_mapel' => 'ENG-10',
            'nama_mapel' => 'Bahasa Inggris',
            'kategori' => 'umum',
        ]);

        // 10. Jadwal Mengajar Guru
        JadwalPelajaran::create([
            'tahun_ajaran_id' => $ta->id,
            'kelas_id' => $kelasX->id,
            'mata_pelajaran_id' => $mapelMat->id,
            'guru_id' => $guruProfile->id,
            'hari' => 'senin',
            'jam_mulai' => '07:30:00',
            'jam_selesai' => '09:00:00',
            'ruangan' => 'R-101',
        ]);

        JadwalPelajaran::create([
            'tahun_ajaran_id' => $ta->id,
            'kelas_id' => $kelasX->id,
            'mata_pelajaran_id' => $mapelFis->id,
            'guru_id' => $guruProfile->id,
            'hari' => 'rabu',
            'jam_mulai' => '09:15:00',
            'jam_selesai' => '10:45:00',
            'ruangan' => 'Lab Fisika',
        ]);

        JadwalPelajaran::create([
            'tahun_ajaran_id' => $ta->id,
            'kelas_id' => $kelasX->id,
            'mata_pelajaran_id' => $mapelBindo->id,
            'guru_id' => $guruProfile2->id,
            'hari' => 'selasa',
            'jam_mulai' => '07:30:00',
            'jam_selesai' => '09:00:00',
            'ruangan' => 'R-101',
        ]);

        JadwalPelajaran::create([
            'tahun_ajaran_id' => $ta->id,
            'kelas_id' => $kelasX->id,
            'mata_pelajaran_id' => $mapelBing->id,
            'guru_id' => $guruProfile2->id,
            'hari' => 'kamis',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '09:30:00',
            'ruangan' => 'Lab Bahasa',
        ]);

        // 11. Ulangan Harian CBT (Mobile Ready)
        $ujian = Ujian::create([
            'guru_id' => $guruProfile->id,
            'mata_pelajaran_id' => $mapelMat->id,
            'judul' => 'Ulangan Harian 1: Aljabar & Eksponen',
            'deskripsi' => 'Kerjakan soal pilihan ganda berikut dengan teliti. Dilarang membuka catatan atau aplikasi lain.',
            'tipe_ujian' => 'ulangan_harian',
            'durasi_menit' => 45,
            'waktu_mulai' => now()->subDay(),
            'waktu_selesai' => now()->addDays(7),
            'acak_soal' => false,
            'acak_opsi' => false,
            'tampilkan_nilai' => true,
            'status' => 'terbit',
        ]);
        $ujian->kelas()->attach($kelasX->id);

        // Soal 1
        $soal1 = SoalUjian::create([
            'ujian_id' => $ujian->id,
            'tipe_soal' => 'pilihan_ganda',
            'pertanyaan' => 'Berapakah nilai dari 2³ × 2⁴ ?',
            'bobot_nilai' => 20.00,
            'urutan' => 1,
        ]);
        OpsiJawaban::create(['soal_id' => $soal1->id, 'label' => 'A', 'teks_opsi' => '2⁷ (128)', 'is_benar' => true]);
        OpsiJawaban::create(['soal_id' => $soal1->id, 'label' => 'B', 'teks_opsi' => '2¹² (4096)', 'is_benar' => false]);
        OpsiJawaban::create(['soal_id' => $soal1->id, 'label' => 'C', 'teks_opsi' => '64', 'is_benar' => false]);
        OpsiJawaban::create(['soal_id' => $soal1->id, 'label' => 'D', 'teks_opsi' => '32', 'is_benar' => false]);
        OpsiJawaban::create(['soal_id' => $soal1->id, 'label' => 'E', 'teks_opsi' => '256', 'is_benar' => false]);

        // Soal 2
        $soal2 = SoalUjian::create([
            'ujian_id' => $ujian->id,
            'tipe_soal' => 'pilihan_ganda',
            'pertanyaan' => 'Jika f(x) = 3x - 5, maka nilai dari f(4) adalah ...',
            'bobot_nilai' => 20.00,
            'urutan' => 2,
        ]);
        OpsiJawaban::create(['soal_id' => $soal2->id, 'label' => 'A', 'teks_opsi' => '5', 'is_benar' => false]);
        OpsiJawaban::create(['soal_id' => $soal2->id, 'label' => 'B', 'teks_opsi' => '7', 'is_benar' => true]);
        OpsiJawaban::create(['soal_id' => $soal2->id, 'label' => 'C', 'teks_opsi' => '9', 'is_benar' => false]);
        OpsiJawaban::create(['soal_id' => $soal2->id, 'label' => 'D', 'teks_opsi' => '11', 'is_benar' => false]);
        OpsiJawaban::create(['soal_id' => $soal2->id, 'label' => 'E', 'teks_opsi' => '13', 'is_benar' => false]);

        // Soal 3
        $soal3 = SoalUjian::create([
            'ujian_id' => $ujian->id,
            'tipe_soal' => 'pilihan_ganda',
            'pertanyaan' => 'Bentuk sederhana dari √75 adalah ...',
            'bobot_nilai' => 20.00,
            'urutan' => 3,
        ]);
        OpsiJawaban::create(['soal_id' => $soal3->id, 'label' => 'A', 'teks_opsi' => '3√5', 'is_benar' => false]);
        OpsiJawaban::create(['soal_id' => $soal3->id, 'label' => 'B', 'teks_opsi' => '4√3', 'is_benar' => false]);
        OpsiJawaban::create(['soal_id' => $soal3->id, 'label' => 'C', 'teks_opsi' => '5√3', 'is_benar' => true]);
        OpsiJawaban::create(['soal_id' => $soal3->id, 'label' => 'D', 'teks_opsi' => '5√5', 'is_benar' => false]);
        OpsiJawaban::create(['soal_id' => $soal3->id, 'label' => 'E', 'teks_opsi' => '25√3', 'is_benar' => false]);

        // Soal 4
        $soal4 = SoalUjian::create([
            'ujian_id' => $ujian->id,
            'tipe_soal' => 'pilihan_ganda',
            'pertanyaan' => 'Himpunan penyelesaian dari 2x + 6 = 18 adalah ...',
            'bobot_nilai' => 20.00,
            'urutan' => 4,
        ]);
        OpsiJawaban::create(['soal_id' => $soal4->id, 'label' => 'A', 'teks_opsi' => '{4}', 'is_benar' => false]);
        OpsiJawaban::create(['soal_id' => $soal4->id, 'label' => 'B', 'teks_opsi' => '{6}', 'is_benar' => true]);
        OpsiJawaban::create(['soal_id' => $soal4->id, 'label' => 'C', 'teks_opsi' => '{8}', 'is_benar' => false]);
        OpsiJawaban::create(['soal_id' => $soal4->id, 'label' => 'D', 'teks_opsi' => '{10}', 'is_benar' => false]);
        OpsiJawaban::create(['soal_id' => $soal4->id, 'label' => 'E', 'teks_opsi' => '{12}', 'is_benar' => false]);

        // Soal 5
        $soal5 = SoalUjian::create([
            'ujian_id' => $ujian->id,
            'tipe_soal' => 'pilihan_ganda',
            'pertanyaan' => 'Hasil dari (a²b³)⁴ adalah ...',
            'bobot_nilai' => 20.00,
            'urutan' => 5,
        ]);
        OpsiJawaban::create(['soal_id' => $soal5->id, 'label' => 'A', 'teks_opsi' => 'a⁸b¹²', 'is_benar' => true]);
        OpsiJawaban::create(['soal_id' => $soal5->id, 'label' => 'B', 'teks_opsi' => 'a⁶b⁷', 'is_benar' => false]);
        OpsiJawaban::create(['soal_id' => $soal5->id, 'label' => 'C', 'teks_opsi' => 'a²b¹²', 'is_benar' => false]);
        OpsiJawaban::create(['soal_id' => $soal5->id, 'label' => 'D', 'teks_opsi' => 'a⁸b³', 'is_benar' => false]);
        OpsiJawaban::create(['soal_id' => $soal5->id, 'label' => 'E', 'teks_opsi' => 'a¹⁶b⁸¹', 'is_benar' => false]);

        // 12. Berita & Kegiatan Sekolah (Landing Page)
        Berita::create([
            'author_id' => $admin->id,
            'judul' => 'SMA Galatia Raih Juara 1 Olimpiade Sains Tingkat Provinsi 2026',
            'slug' => 'sma-galatia-raih-juara-1-olimpiade-sains-2026',
            'thumbnail' => 'https://images.unsplash.com/photo-1546410531-bb4caa6b424d?auto=format&fit=crop&w=800&q=80',
            'kategori' => 'Prestasi',
            'konten' => 'Prestasi membanggakan kembali ditorehkan oleh siswa SMA Galatia dalam ajang Olimpiade Sains Provinsi 2026. Dengan dedikasi tinggi dan bimbingan para guru, piala juara 1 berhasil dibawa pulang.',
            'is_published' => true,
            'views' => 142,
        ]);

        Berita::create([
            'author_id' => $admin->id,
            'judul' => 'Pembukaan Pendaftaran Peserta Didik Baru (PPDB) Tahun Ajaran 2026/2027',
            'slug' => 'pembukaan-ppdb-2026-2027',
            'thumbnail' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=800&q=80',
            'kategori' => 'Pengumuman',
            'konten' => 'SMA Galatia resmi membuka pendaftaran peserta didik baru gelombang 1. Fasilitas modern, pengajar berkompeten, dan kurikulum unggulan siap menyambut calon pemimpin bangsa.',
            'is_published' => true,
            'views' => 280,
        ]);

        Berita::create([
            'author_id' => $admin->id,
            'judul' => 'Pekan Olahraga dan Seni (PORSENI) Antar Kelas SMA Galatia',
            'slug' => 'pekan-olahraga-dan-seni-galatia-2026',
            'thumbnail' => 'https://images.unsplash.com/photo-1577896851231-70ef18881754?auto=format&fit=crop&w=800&q=80',
            'kategori' => 'Kegiatan',
            'konten' => 'Semarak Porseni 2026 mempererat persaudaraan antar siswa dengan berbagai kompetisi basket, futsal, tari tradisional, dan musik akustik.',
            'is_published' => true,
            'views' => 95,
        ]);

        // 13. Galeri Foto
        Galeri::create([
            'judul' => 'Laboratorium Komputer Modern SMA Galatia',
            'foto' => 'https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?auto=format&fit=crop&w=800&q=80',
            'kategori' => 'Fasilitas',
            'deskripsi' => 'Dilengkapi 40 unit PC spesifikasi tinggi dan koneksi fiber optic untuk mendukung CBT dan coding.',
        ]);

        Galeri::create([
            'judul' => 'Perpustakaan Digital dan Ruang Baca Nyaman',
            'foto' => 'https://images.unsplash.com/photo-1521587760476-6c12a4b040da?auto=format&fit=crop&w=800&q=80',
            'kategori' => 'Fasilitas',
            'deskripsi' => 'Koleksi ribuan buku fisik dan akses e-book nasional.',
        ]);

        Galeri::create([
            'judul' => 'Upacara Bendera dan Pembinaan Karakter Siswa',
            'foto' => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=800&q=80',
            'kategori' => 'Kegiatan',
            'deskripsi' => 'Rutin dilaksanakan setiap hari Senin untuk membentuk kedisiplinan dan rasa nasionalisme.',
        ]);
    }
}
