<?php
require_once __DIR__ . '/../config/database.php';

class SiswaModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getAll() {
        $sql = "SELECT s.*, u.username, u.status as user_status,
                       ps.nama_lengkap as pembimbing_sekolah,
                       pp.nama_lengkap as pembimbing_pt,
                       pt.nama_pt
                FROM siswa s
                JOIN users u ON s.user_id = u.id
                LEFT JOIN pembimbing ps ON s.pembimbing_sekolah_id = ps.id
                LEFT JOIN pembimbing pp ON s.pembimbing_pt_id = pp.id
                LEFT JOIN perusahaan_pt pt ON s.perusahaan_id = pt.id
                ORDER BY s.nama_lengkap ASC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $sql = "SELECT s.*, u.username, u.status as user_status,
                       ps.nama_lengkap as pembimbing_sekolah,
                       pp.nama_lengkap as pembimbing_pt,
                       pt.*
                FROM siswa s
                JOIN users u ON s.user_id = u.id
                LEFT JOIN pembimbing ps ON s.pembimbing_sekolah_id = ps.id
                LEFT JOIN pembimbing pp ON s.pembimbing_pt_id = pp.id
                LEFT JOIN perusahaan_pt pt ON s.perusahaan_id = pt.id
                WHERE s.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByUserId($userId) {
        $sql = "SELECT s.*, pt.*
                FROM siswa s
                LEFT JOIN perusahaan_pt pt ON s.perusahaan_id = pt.id
                WHERE s.user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    public function create($data) {
        $this->conn->beginTransaction();
        try {
            $hash = password_hash($data['password'], PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'siswa')");
            $stmt->execute([$data['username'], $hash]);
            $userId = $this->conn->lastInsertId();

            $stmt = $this->conn->prepare("INSERT INTO siswa (user_id, nama_lengkap, nisn, kelas, pembimbing_sekolah_id, pembimbing_pt_id, perusahaan_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $userId, $data['nama_lengkap'], $data['nisn'], $data['kelas'],
                $data['pembimbing_sekolah_id'], $data['pembimbing_pt_id'], $data['perusahaan_id']
            ]);
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function update($id, $data) {
        $sql = "UPDATE siswa SET nama_lengkap=?, nisn=?, kelas=?, pembimbing_sekolah_id=?, pembimbing_pt_id=?, perusahaan_id=? WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['nama_lengkap'], $data['nisn'], $data['kelas'],
            $data['pembimbing_sekolah_id'], $data['pembimbing_pt_id'],
            $data['perusahaan_id'], $id
        ]);
    }

    public function delete($id) {
        $siswa = $this->getById($id);
        if ($siswa) {
            $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
            return $stmt->execute([$siswa['user_id']]);
        }
        return false;
    }
}
