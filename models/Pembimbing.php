<?php
require_once __DIR__ . '/../config/database.php';

class PembimbingModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getAll() {
        $sql = "SELECT p.*, u.username, u.status as user_status, pt.nama_pt
                FROM pembimbing p
                JOIN users u ON p.user_id = u.id
                LEFT JOIN perusahaan_pt pt ON p.perusahaan_id = pt.id
                ORDER BY p.nama_lengkap ASC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $sql = "SELECT p.*, u.username, u.status as user_status
                FROM pembimbing p
                JOIN users u ON p.user_id = u.id
                WHERE p.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByUserId($userId) {
        $stmt = $this->conn->prepare("SELECT * FROM pembimbing WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    public function getByPerusahaanId($perusahaanId) {
        $stmt = $this->conn->prepare("SELECT * FROM pembimbing WHERE perusahaan_id = ? AND jenis = 'pt'");
        $stmt->execute([$perusahaanId]);
        return $stmt->fetchAll();
    }

    public function create($data) {
        $this->conn->beginTransaction();
        try {
            $hash = password_hash($data['password'], PASSWORD_DEFAULT);
            $role = $data['jenis'] == 'sekolah' ? 'pembimbing_sekolah' : 'pembimbing_pt';
            $stmt = $this->conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt->execute([$data['username'], $hash, $role]);
            $userId = $this->conn->lastInsertId();

            $stmt = $this->conn->prepare("INSERT INTO pembimbing (user_id, nama_lengkap, nip_nik, jenis, perusahaan_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$userId, $data['nama_lengkap'], $data['nip_nik'], $data['jenis'], $data['perusahaan_id'] ?? null]);
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function update($id, $data) {
        $sql = "UPDATE pembimbing SET nama_lengkap=?, nip_nik=?, jenis=?, perusahaan_id=? WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['nama_lengkap'], $data['nip_nik'], $data['jenis'],
            $data['perusahaan_id'] ?? null, $id
        ]);
    }

    public function delete($id) {
        $pembimbing = $this->getById($id);
        if ($pembimbing) {
            $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
            return $stmt->execute([$pembimbing['user_id']]);
        }
        return false;
    }

    public function getCounterpart($pembimbingId, $jenis) {
        if ($jenis == 'sekolah') {
            $sql = "SELECT DISTINCT pb.id, pb.nama_lengkap, pb.nip_nik
                    FROM siswa s
                    JOIN pembimbing pb ON s.pembimbing_pt_id = pb.id
                    WHERE s.pembimbing_sekolah_id = ?
                    LIMIT 1";
        } else {
            $sql = "SELECT DISTINCT pb.id, pb.nama_lengkap, pb.nip_nik
                    FROM siswa s
                    JOIN pembimbing pb ON s.pembimbing_sekolah_id = pb.id
                    WHERE s.pembimbing_pt_id = ?
                    LIMIT 1";
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$pembimbingId]);
        return $stmt->fetch();
    }

    public function getSiswaBimbingan($pembimbingId, $jenis) {
        $kolom = $jenis == 'sekolah' ? 'pembimbing_sekolah_id' : 'pembimbing_pt_id';
        $sql = "SELECT s.*, u.username, pt.nama_pt, pt.alamat as alamat_pt,
                       a.id as absen_id, a.jam_datang, a.jam_pulang,
                       a.status_datang, a.status_pulang
                FROM siswa s
                JOIN users u ON s.user_id = u.id
                LEFT JOIN perusahaan_pt pt ON s.perusahaan_id = pt.id
                LEFT JOIN absensi a ON a.siswa_id = s.id AND a.tanggal = CURDATE()
                WHERE s.{$kolom} = ?
                ORDER BY s.nama_lengkap ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$pembimbingId]);
        return $stmt->fetchAll();
    }
}
