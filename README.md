# Sistem Informasi Absensi Siswa PKL

Aplikasi pencatatan dan validasi kehadiran siswa PKL berbasis web. Menggunakan geofencing (Haversine) untuk validasi lokasi, selfie via kamera untuk dokumentasi, serta sistem approval oleh pembimbing.

## Teknologi

| Komponen | Teknologi |
|---|---|
| Backend | PHP Native (MVC) |
| Database | MySQL / MariaDB |
| Frontend | HTML5, CSS3, Bootstrap 5 |
| Geolokasi | HTML5 Geolocation API + Haversine |
| Kamera | Camera API (JavaScript) |
| PDF | FPDF |

## Role Pengguna

- **Admin** — Kelola siswa, pembimbing, perusahaan, jadwal absen
- **Pembimbing Sekolah** — Memantau siswa bimbingan, approval absensi & izin, rekap PDF
- **Pembimbing PT** — Memantau siswa PKL di perusahaannya, approval absensi & izin, rekap PDF
- **Siswa** — Absen datang/pulang (dengan GPS + selfie), ajukan izin/sakit

## Instalasi di XAMPP

1. Clone atau salin folder ke `C:\xampp\htdocs\web-absen-pkl`

2. Buka **phpMyAdmin**, buat database `db_absen_pkl`, import `database.sql`

3. Sesuaikan konfigurasi di `config/database.php` jika perlu:
   ```php
   $this->host = 'localhost';
   $this->db_name = 'db_absen_pkl';
   $this->username = 'root';
   $this->password = '';
   ```

4. Akses di browser:
   ```
   http://localhost/web-absen-pkl/
   ```

### Login Default

| Role | Username | Password |
|---|---|---|
| Admin | `admin` | `password` |

> Data siswa, pembimbing, dan perusahaan PT ditambahkan melalui menu Admin.

## Lisensi

MIT License — silakan gunakan, modifikasi, dan distribusikan.
