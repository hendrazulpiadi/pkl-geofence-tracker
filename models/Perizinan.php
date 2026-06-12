<?php
require_once __DIR__ . '/../config/database.php';

class Perizinan {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function create($data) {
        $sql = "INSERT INTO perizinan (siswa_id, jenis, tgl_mulai, tgl_selesai, keterangan, bukti_foto)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$data['siswa_id'], $data['jenis'], $data['tgl_mulai'],
                               $data['tgl_selesai'], $data['keterangan'], $data['bukti_foto']]);
    }

    public function getBySiswaId($siswaId) {
        $stmt = $this->conn->prepare("SELECT * FROM perizinan WHERE siswa_id = ? ORDER BY created_at DESC");
        $stmt->execute([$siswaId]);
        return $stmt->fetchAll();
    }

    public function getPending($pembimbingId = null, $jenis = null) {
        $sql = "SELECT p.*, s.nama_lengkap, s.nisn, s.kelas
                FROM perizinan p
                JOIN siswa s ON p.siswa_id = s.id
                WHERE p.status_approval = 'pending'";
        $params = [];
        if ($pembimbingId && $jenis) {
            $kolom = $jenis == 'sekolah' ? 's.pembimbing_sekolah_id' : 's.pembimbing_pt_id';
            $sql .= " AND {$kolom} = ?";
            $params[] = $pembimbingId;
        }
        $sql .= " ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function approve($id, $pembimbingId) {
        $stmt = $this->conn->prepare("UPDATE perizinan SET status_approval='approved', approved_by=? WHERE id=?");
        return $stmt->execute([$pembimbingId, $id]);
    }

    public function reject($id, $pembimbingId) {
        $stmt = $this->conn->prepare("UPDATE perizinan SET status_approval='rejected', approved_by=? WHERE id=?");
        return $stmt->execute([$pembimbingId, $id]);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM perizinan WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
