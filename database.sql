CREATE DATABASE IF NOT EXISTS db_absen_pkl;
USE db_absen_pkl;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','pembimbing_sekolah','pembimbing_pt','siswa') NOT NULL,
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE perusahaan_pt (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_pt VARCHAR(100) NOT NULL,
    alamat TEXT,
    latitude DECIMAL(10,8),
    longitude DECIMAL(11,8),
    radius_toleransi INT DEFAULT 50,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE pembimbing (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    nip_nik VARCHAR(50),
    jenis ENUM('sekolah','pt') NOT NULL,
    perusahaan_id INT DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (perusahaan_id) REFERENCES perusahaan_pt(id) ON DELETE SET NULL
);

CREATE TABLE siswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    nisn VARCHAR(20) UNIQUE NOT NULL,
    kelas VARCHAR(20),
    pembimbing_sekolah_id INT DEFAULT NULL,
    pembimbing_pt_id INT DEFAULT NULL,
    perusahaan_id INT DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (pembimbing_sekolah_id) REFERENCES pembimbing(id) ON DELETE SET NULL,
    FOREIGN KEY (pembimbing_pt_id) REFERENCES pembimbing(id) ON DELETE SET NULL,
    FOREIGN KEY (perusahaan_id) REFERENCES perusahaan_pt(id) ON DELETE SET NULL
);

CREATE TABLE absensi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    siswa_id INT NOT NULL,
    tanggal DATE NOT NULL,
    jam_datang DATETIME DEFAULT NULL,
    jam_pulang DATETIME DEFAULT NULL,
    lat_datang DECIMAL(10,8) DEFAULT NULL,
    lng_datang DECIMAL(11,8) DEFAULT NULL,
    lat_pulang DECIMAL(10,8) DEFAULT NULL,
    lng_pulang DECIMAL(11,8) DEFAULT NULL,
    foto_datang VARCHAR(255) DEFAULT NULL,
    foto_pulang VARCHAR(255) DEFAULT NULL,
    status_datang ENUM('pending','approved','rejected') DEFAULT 'pending',
    status_pulang ENUM('pending','approved','rejected') DEFAULT 'pending',
    FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE,
    INDEX idx_siswa_tanggal (siswa_id, tanggal)
);

CREATE TABLE perizinan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    siswa_id INT NOT NULL,
    jenis ENUM('izin','sakit') NOT NULL,
    tgl_mulai DATE NOT NULL,
    tgl_selesai DATE NOT NULL,
    keterangan TEXT,
    bukti_foto VARCHAR(255) DEFAULT NULL,
    status_approval ENUM('pending','approved','rejected') DEFAULT 'pending',
    approved_by INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES pembimbing(id) ON DELETE SET NULL
);

CREATE TABLE pengaturan_waktu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    perusahaan_id INT NOT NULL,
    jam_masuk_mulai TIME NOT NULL,
    jam_masuk_selesai TIME NOT NULL,
    jam_pulang_mulai TIME NOT NULL,
    jam_pulang_selesai TIME NOT NULL,
    FOREIGN KEY (perusahaan_id) REFERENCES perusahaan_pt(id) ON DELETE CASCADE
);

INSERT INTO users (username, password, role, status) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1);
