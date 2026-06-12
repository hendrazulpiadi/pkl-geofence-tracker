# Product Requirement Document (PRD)
## Sistem Informasi Absensi Siswa PKL Berbasis Web

---

### 1. Pendahuluan & Ringkasan Eksekutif
Sistem Absensi Siswa PKL adalah aplikasi berbasis web yang dirancang untuk mencatat, mengelola, dan memvalidasi kehadiran siswa Praktik Kerja Lapangan (PKL) secara *real-time*. Sistem ini memfasilitasi tiga aktor utama: Admin, Pembimbing (Sekolah & PT), dan Siswa. Aplikasi berfokus pada kecepatan, keamanan, ketepatan lokasi (geolokasi), dan validasi visual (selfie).

### 2. Spesifikasi Teknis (XAMPP Compatible)
Untuk memastikan aplikasi berjalan optimal di lingkungan **XAMPP** dan responsif di perangkat mobile (Android/iOS), teknologi yang digunakan adalah:
- **Backend:** PHP (Native MVC atau CodeIgniter 4 / Laravel dengan optimasi versi PHP XAMPP).
- **Database:** MySQL / MariaDB (Bawaan XAMPP).
- **Frontend:** HTML5, CSS3, Bootstrap 5 (Dipilih karena ringan, cepat, dan *mobile-first responsive*).
- **Integrasi Fitur:** - **HTML5 Geolocation API:** Mengambil koordinat GPS rieltime dari perangkat siswa.
  - **Webcam/Camera API (JavaScript):** Mengakses kamera depan smartphone untuk dokumentasi *selfie*.
  - **Haversine Formula (PHP/MySQL):** Menghitung jarak radius antara posisi siswa dan koordinat PT tempat PKL.

---

### 3. Arsitektur & Logika Sistem

#### A. Logika Keamanan & Kecepatan
1. **Validasi Waktu Rieltime:** Waktu absensi diambil dari server (waktu PHP/MySQL), bukan waktu perangkat klien untuk mencegah manipulasi jam oleh siswa.
2. **Validasi Lokasi (Geofencing):** Absen hanya sukses jika jarak siswa ke koordinat lokasi PT $\leq X$ meter (misal: 50 meter). Ditandai dengan rumus Haversine.
3. **Penyimpanan Gambar Efisien:** Foto *selfie* dikompresi di sisi klien (menggunakan JavaScript Canvas) sebelum diunggah untuk menghemat bandwidth dan mempercepat *load data*.
4. **Keamanan Data:** Prepared Statements (PDO/MySQLi) untuk mencegah *SQL Injection*, enkripsi password menggunakan `password_hash()`, dan proteksi session untuk mencegah pembajakan hak akses.

#### B. Alur Logika Absensi (Flowchart Logic)
1. Siswa klik menu "Absen Datang/Pulang".
2. Sistem mengecek rentang waktu (Apakah saat ini masuk dalam jendela waktu absen yang diatur pembimbing?).
3. Jika YA, browser meminta izin akses GPS dan Kamera.
4. Sistem menghitung radius lokasi. Jika berada di dalam radius PT, tombol "Ambil Foto & Kirim" aktif.
5. Siswa mengambil foto *selfie*, lalu mengirim data. Status kehadiran menjadi **"Pending Approval"**.

---

### 4. Kebutuhan Fungsional Berdasarkan Peran (User Roles)

#### 1. Peran: Siswa PKL
- **Dashboard:**
  - Menampilkan profil data diri siswa.
  - Informasi Tempat PKL: Nama PT, Alamat/Lokasi Koordinat PT.
  - Nama Pembimbing Sekolah & Pembimbing PT.
- **Menu Absensi:**
  - Tombol Absen Datang & Absen Pulang (Aktif sesuai jam rentang ketentuan PT).
  - Tampilan peta/koordinat dan modul kamera *selfie* langsung di halaman.
- **Menu Pengajuan Izin/Sakit:**
  - Formulir input tanggal mulai, tanggal selesai, alasan, dan unggah bukti (foto surat dokter/surat izin).

#### 2. Peran: Pembimbing (Sekolah & PT)
- **Dashboard:**
  - Informasi nama PT dan Pembimbing yang bersangkutan.
  - Daftar siswa PKL yang berada di bawah bimbingannya.
  - Ringkasan cepat kehadiran hari ini (Hadir, Izin, Alpa, Pending).
- **Manajemen Kontrol:**
  - Mengatur rentang waktu absensi (Contoh: Absen Datang: 07.00 - 08.00, Absen Pulang: 16.00 - 18.00).
- **Persetujuan (Approval):**
  - Halaman verifikasi untuk menyetujui (*approve*) atau menolak kehadiran, kepulangan, serta pengajuan izin/sakit siswa dilengkapi dengan foto *selfie* dan lokasi siswa saat absen.
- **Rekapitulasi & Laporan:**
  - Melihat dan mengunduh rekap absensi mingguan dan bulanan khusus siswa bimbingannya (Format Excel/PDF).

#### 3. Peran: Admin
- **Kontrol Hak Akses Total:**
  - CRUD (Create, Read, Update, Delete) data Siswa, Pembimbing Sekolah, Pembimbing PT, dan Data Perusahaan (PT).
- **Plotting Data:** Menghubungkan Siswa dengan Pembimbing dan Tempat PT terkait.
- **Rekap Global:** Membuat dan mengunduh laporan rekap absensi mingguan dan bulanan seluruh siswa PKL secara keseluruhan untuk kebutuhan institusi/sekolah.

---

### 5. Skema Basis Data (Ringkas)

1. **`users`** (id, username, password, role [admin/pembimbing_sekolah/pembimbing_pt/siswa], status)
2. **`perusahaan_pt`** (id, nama_pt, alamat, latitude, longitude, radius_toleransi)
3. **`pembimbing`** (id, user_id, nama_lengkap, nip_nik, jenis [sekolah/pt], perusahaan_id)
4. **`siswa`** (id, user_id, nama_lengkap, nisn, kelas, pembimbing_sekolah_id, pembimbing_pt_id, perusahaan_id)
5. **`absensi`** (id, siswa_id, tanggal, jam_datang, jam_pulang, lat_datang, lng_datang, lat_pulang, lng_pulang, foto_datang, foto_pulang, status_datang, status_pulang)
6. **`perizinan`** (id, siswa_id, jenis [izin/sakit], tgl_mulai, tgl_selesai, keterangan, bukti_foto, status_approval, approved_by)
7. **`pengaturan_waktu`** (id, perusahaan_id, jam_masuk_mulai, jam_masuk_selesai, jam_pulang_mulai, jam_pulang_selesai)

---

### 6. Kebutuhan Non-Fungsional (Kualitas Sistem)
- **Responsif UI:** Antarmuka harus menggunakan struktur grid fleksibel (seperti Bootstrap Container-Fluid) agar nyaman diakses langsung dari browser Android/iOS (terasa seperti aplikasi native).
- **Kecepatan (Performance):** Query database dioptimalkan menggunakan *indexing* pada `siswa_id` dan `tanggal`. Ukuran file foto dibatasi maksimal 500KB setelah kompresi.
- **Kompatibilitas:** Dapat langsung dijalankan setelah folder proyek dimasukkan ke dalam `xampp/htdocs/` dan melakukan import file `.sql`.
