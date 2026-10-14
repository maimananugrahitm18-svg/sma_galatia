# 🏫 SMA GALATIA - Website Sekolah & CBT Mobile Portal

Sistem Informasi Sekolah Terintegrasi berbasis **Laravel 12** yang mencakup:
1. **Portofolio & Landing Page Publik**: Profil sekolah, visi misi, sambutan kepala sekolah, fasilitas, galeri, dan warta kegiatan terkini.
2. **Sistem Autentikasi & Multi-Role RBAC**: Terbagi menjadi 4 peran (`Super Admin`, `Admin TU`, `Guru`, `Siswa`).
3. **Manajemen Jadwal Mengajar Guru**: Penjadwalan per kelas, hari, jam, dan ruangan dengan proteksi deteksi bentrok (*conflict prevention*).
4. **Ulangan Harian & Ujian Online CBT (Mobile-Friendly)**: Dirancang khusus agar siswa nyaman mengerjakan soal dari layar smartphone / HP dengan:
   - Tombol touch-target besar (ramah sentuhan jari)
   - Auto-save jawaban via background AJAX tanpa refresh
   - Drawer / bottom sheet navigasi nomor soal
   - Hitung mundur timer otomatis
   - Peringatan anti-curang (*visibilitychange*) jika beralih aplikasi di smartphone
   - Penilaian instan untuk pilihan ganda & rekap nilai guru

---

## 🚀 Cara Menjalankan

Server Laravel saat ini sudah aktif di:
👉 **`http://localhost:8000`**

Jika ingin menyalakan ulang di masa mendatang, cukup jalankan perintah di terminal:
```bash
php artisan serve
```

---

## 🔑 Akun Uji Coba Cepat (Seeder Ready)

Semua akun menggunakan password: `password`

| Role | Email Login | Keterangan |
| :--- | :--- | :--- |
| **Super Admin** | `superadmin@galatia.sch.id` | Akses penuh seluruh konfigurasi sistem |
| **Admin TU** | `admin@galatia.sch.id` | Kelola jadwal mengajar & konten sekolah |
| **Guru** | `guru@galatia.sch.id` | Lihat jadwal pribadi, buat ulangan harian, kelola bank soal |
| **Siswa** | `siswa@galatia.sch.id` | Lihat jadwal kelas, ikuti ulangan harian di HP |

*(Di halaman login juga telah disediakan tombol 1-klik untuk otomatis mengisi akun di atas demi kemudahan pengujian).*

---

## 📂 Struktur Rute Utama

- `/` : Landing Page Portofolio Sekolah
- `/login` : Halaman Masuk
- `/register` : Pendaftaran Akun Siswa & Guru Baru
- `/admin/dashboard` & `/admin/jadwal` : Panel Admin & Jadwal Mengajar
- `/guru/dashboard`, `/guru/jadwal`, `/guru/ujian` : Panel Guru, Jadwal Pribadi, Bank Soal & Rekap Nilai
- `/siswa/dashboard`, `/siswa/jadwal`, `/siswa/ujian` : Panel Siswa, Jadwal Kelas, & CBT Mobile Exam
