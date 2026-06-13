# Sistem Informasi Absensi Siswa PKL

Aplikasi pencatatan dan validasi kehadiran siswa PKL berbasis web. Menggunakan geofencing (Haversine) untuk validasi lokasi, selfie via kamera untuk dokumentasi, serta sistem approval oleh pembimbing.

## Teknologi

| Komponen | Teknologi |
|---|---|
| Backend | PHP Native (MVC) |
| Database | MySQL / MariaDB |
| Frontend | HTML5, CSS3, Custom CSS Framework (Tailwind-like), Bootstrap 5 |
| Geolokasi | HTML5 Geolocation API + Haversine |
| Kamera | MediaDevices API (JavaScript) |
| PDF | FPDF |
| Font | Inter (Google Fonts) |
| Icons | Bootstrap Icons |

## Fitur

### Role Pengguna

- **Admin** — Kelola siswa, pembimbing, perusahaan, jadwal absen
- **Pembimbing Sekolah** — Memantau siswa bimbingan, approval absensi & izin, rekap PDF
- **Pembimbing PT** — Memantau siswa PKL di perusahaannya, approval absensi & izin, rekap PDF
- **Siswa** — Absen datang/pulang (dengan GPS + selfie), ajukan izin/sakit

### Halaman

| Halaman | Role | Deskripsi |
|---|---|---|
| Dashboard | Semua | Hero welcome card, statistik, quick actions, timeline |
| Absensi | Siswa | GPS card (lat/lng/akurasi/jarak/map), live selfie, verification checklist 3-step, success modal |
| Riwayat | Siswa | Filter (hari/minggu/bulan/tanggal), stat hadir/izin/alpa, progress ring chart, timeline with selfie thumbnails |
| Izin/Sakit | Siswa | Radio card selector, date range, drag-drop upload with preview, history timeline |
| Profil | Siswa | Hero card, info PKL & pembimbing, ringkasan kehadiran dengan ring chart, pengaturan akun |
| Detail Absensi | Siswa | Foto absensi, peta lokasi, approval timeline 3-step (Dikirim → Review → Selesai) |
| Approval | Pembimbing | Approve/reject absensi & izin siswa bimbingan |
| Rekapitulasi | Pembimbing | Filter per bulan/minggu, export PDF |
| Manajemen | Admin | CRUD siswa, pembimbing, perusahaan, jadwal absen |

### Desain

- **Premium SaaS theme** dengan design tokens CSS custom properties
- **Glassmorphism** components, bento grid layout
- **50/50 split login page** — blue gradient hero panel + centered login card
- **Premium sidebar navigasi** untuk semua role (admin, pembimbing, siswa)
- **Responsive** — mobile-first, hamburger toggle sidebar
- **Animasi** — fade-in-up sequential loading, hover effects, loading spinner

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

4. **Penting:** Sesuaikan timezone PHP di `C:\xampp\php\php.ini`:
   ```ini
   date.timezone = Asia/Jakarta
   ```

5. Restart Apache melalui XAMPP Control Panel

6. Akses di browser:
   ```
   http://localhost/web-absen-pkl/
   ```

### Login Default

| Role | Username | Password |
|---|---|---|
| Admin | `admin` | `password` |

> Data siswa, pembimbing, dan perusahaan PT ditambahkan melalui menu Admin.

## Struktur Direktori

```
web-absen-pkl/
├── assets/
│   ├── css/
│   │   └── style.css          # Custom CSS framework + components
│   └── img/
├── config/
│   └── database.php           # Koneksi database
├── controllers/
│   ├── AdminController.php    # CRUD admin
│   ├── AuthController.php     # Login/logout
│   ├── PembimbingController.php # Dashboard, approval, rekap
│   └── SiswaController.php    # Dashboard, absen, riwayat, profil, izin
├── helpers/
│   ├── fpdf.php               # Library PDF
│   ├── haversine.php          # Fungsi hitung jarak GPS
│   └── session.php            # Session & flash messages
├── models/
│   ├── Absensi.php            # CRUD absensi
│   ├── Pembimbing.php         # CRUD pembimbing
│   ├── PengaturanWaktu.php    # Jadwal absen
│   ├── Perizinan.php          # CRUD izin/sakit
│   ├── Perusahaan.php         # CRUD perusahaan
│   ├── Siswa.php              # CRUD siswa
│   └── User.php               # Autentikasi user
├── views/
│   ├── admin/                 # Halaman admin
│   ├── auth/
│   │   └── login.php          # Halaman login premium
│   ├── layouts/
│   │   ├── header.php         # Sidebar + topnav
│   │   └── footer.php
│   ├── pembimbing/            # Halaman pembimbing
│   └── siswa/                 # Halaman siswa
│       ├── absen.php          # Absensi dengan GPS + selfie
│       ├── dashboard.php      # Dashboard siswa
│       ├── detail.php         # Detail absensi
│       ├── izin.php           # Pengajuan izin/sakit
│       ├── profil.php         # Profil & pengaturan
│       └── riwayat.php        # Riwayat absensi
├── uploads/foto/              # Foto selfie & bukti izin
├── index.php                  # Entry point & routing
└── database.sql               # Skema database
```

## API Routes (Internal)

Semua routing via parameter `?page=` di `index.php`:

| Page | Controller Method |
|---|---|
| `login` | AuthController::login() |
| `logout` | AuthController::logout() |
| `admin-dashboard` | AdminController::dashboard() |
| `admin-siswa` | AdminController::siswa() |
| `admin-pembimbing` | AdminController::pembimbing() |
| `admin-perusahaan` | AdminController::perusahaan() |
| `admin-pengaturan-waktu` | AdminController::pengaturanWaktu() |
| `siswa-dashboard` | SiswaController::dashboard() |
| `siswa-absen` | SiswaController::absen() |
| `siswa-riwayat` | SiswaController::riwayat() |
| `siswa-izin` | SiswaController::izin() |
| `siswa-profil` | SiswaController::profil() |
| `siswa-detail` | SiswaController::detail() |
| `pembimbing-dashboard` | PembimbingController::dashboard() |
| `pembimbing-approval` | PembimbingController::approval() |
| `pembimbing-rekap` | PembimbingController::rekap() |
| `pembimbing-rekap-pdf` | PembimbingController::rekapPdf() |

## Lisensi

MIT License — silakan gunakan, modifikasi, dan distribusikan.
