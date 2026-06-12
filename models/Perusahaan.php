<?php
require_once __DIR__ . '/../config/database.php';

class Perusahaan {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getAll() {
        $stmt = $this->conn->query("SELECT * FROM perusahaan_pt ORDER BY nama_pt ASC");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM perusahaan_pt WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO perusahaan_pt (nama_pt, alamat, latitude, longitude, radius_toleransi) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['nama_pt'], $data['alamat'], $data['latitude'],
            $data['longitude'], $data['radius_toleransi']
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE perusahaan_pt SET nama_pt=?, alamat=?, latitude=?, longitude=?, radius_toleransi=? WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['nama_pt'], $data['alamat'], $data['latitude'],
            $data['longitude'], $data['radius_toleransi'], $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM perusahaan_pt WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
