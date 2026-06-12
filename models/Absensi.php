<?php
require_once __DIR__ . '/../config/database.php';

class Absensi {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function absenDatang($siswaId, $lat, $lng, $foto) {
        $existing = $this->cekAbsenHariIni($siswaId);
        if ($existing && $existing['status_datang'] == 'rejected') {
            $sql = "UPDATE absensi SET jam_datang=NOW(), lat_datang=?, lng_datang=?, foto_datang=?, status_datang='pending'
                    WHERE id=?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$lat, $lng, $foto, $existing['id']]);
        }
        $sql = "INSERT INTO absensi (siswa_id, tanggal, jam_datang, lat_datang, lng_datang, foto_datang, status_datang)
                VALUES (?, CURDATE(), NOW(), ?, ?, ?, 'pending')";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$siswaId, $lat, $lng, $foto]);
    }

    public function absenPulang($siswaId, $lat, $lng, $foto) {
        $existing = $this->cekAbsenHariIni($siswaId);
        if ($existing) {
            $sql = "UPDATE absensi SET jam_pulang=NOW(), lat_pulang=?, lng_pulang=?, foto_pulang=?, status_pulang='pending'
                    WHERE id=? AND (jam_pulang IS NULL OR status_pulang = 'rejected')";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$lat, $lng, $foto, $existing['id']]);
        }
        return false;
    }

    public function cekAbsenHariIni($siswaId) {
        $stmt = $this->conn->prepare("SELECT * FROM absensi WHERE siswa_id = ? AND tanggal = CURDATE() ORDER BY id DESC LIMIT 1");
        $stmt->execute([$siswaId]);
        return $stmt->fetch();
    }

    public function getRiwayat($siswaId, $limit = 30) {
        $stmt = $this->conn->prepare("SELECT * FROM absensi WHERE siswa_id = ? ORDER BY tanggal DESC LIMIT ?");
        $stmt->bindValue(1, $siswaId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getPending($pembimbingId = null, $jenis = null) {
        $sql = "SELECT a.*, s.nama_lengkap, s.nisn, s.kelas, pt.nama_pt
                FROM absensi a
                JOIN siswa s ON a.siswa_id = s.id
                LEFT JOIN perusahaan_pt pt ON s.perusahaan_id = pt.id
                WHERE (a.status_datang = 'pending' OR a.status_pulang = 'pending')";
        $params = [];
        if ($pembimbingId && $jenis) {
            $kolom = $jenis == 'sekolah' ? 's.pembimbing_sekolah_id' : 's.pembimbing_pt_id';
            $sql .= " AND {$kolom} = ?";
            $params[] = $pembimbingId;
        }
        $sql .= " ORDER BY a.tanggal DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function approveDatang($id) {
        $stmt = $this->conn->prepare("UPDATE absensi SET status_datang='approved' WHERE id=?");
        return $stmt->execute([$id]);
    }

    public function rejectDatang($id) {
        $stmt = $this->conn->prepare("UPDATE absensi SET status_datang='rejected' WHERE id=?");
        return $stmt->execute([$id]);
    }

    public function approvePulang($id) {
        $stmt = $this->conn->prepare("UPDATE absensi SET status_pulang='approved' WHERE id=?");
        return $stmt->execute([$id]);
    }

    public function rejectPulang($id) {
        $stmt = $this->conn->prepare("UPDATE absensi SET status_pulang='rejected' WHERE id=?");
        return $stmt->execute([$id]);
    }

    public function getRekap($perusahaanId = null, $bulan = null, $tahun = null) {
        $where = "";
        $params = [];
        if ($perusahaanId) {
            $where .= " AND s.perusahaan_id = ?";
            $params[] = $perusahaanId;
        }
        if ($bulan) {
            $where .= " AND MONTH(a.tanggal) = ?";
            $params[] = $bulan;
        }
        if ($tahun) {
            $where .= " AND YEAR(a.tanggal) = ?";
            $params[] = $tahun;
        }
        $sql = "SELECT a.*, s.nama_lengkap, s.nisn, s.kelas, pt.nama_pt
                FROM absensi a
                JOIN siswa s ON a.siswa_id = s.id
                LEFT JOIN perusahaan_pt pt ON s.perusahaan_id = pt.id
                WHERE 1=1 {$where}
                ORDER BY a.tanggal DESC, s.nama_lengkap ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getRekapSiswaByPembimbing($pembimbingId, $jenis) {
        $kolom = $jenis == 'sekolah' ? 's.pembimbing_sekolah_id' : 's.pembimbing_pt_id';
        $sql = "SELECT a.*, s.nama_lengkap, s.nisn, s.kelas, pt.nama_pt
                FROM absensi a
                JOIN siswa s ON a.siswa_id = s.id
                LEFT JOIN perusahaan_pt pt ON s.perusahaan_id = pt.id
                WHERE {$kolom} = ?
                ORDER BY a.tanggal DESC, s.nama_lengkap ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$pembimbingId]);
        return $stmt->fetchAll();
    }
}
