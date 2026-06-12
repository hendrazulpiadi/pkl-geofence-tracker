<?php
require_once __DIR__ . '/../config/database.php';

class PengaturanWaktu {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getByPerusahaanId($perusahaanId) {
        $stmt = $this->conn->prepare("SELECT * FROM pengaturan_waktu WHERE perusahaan_id = ?");
        $stmt->execute([$perusahaanId]);
        return $stmt->fetch();
    }

    public function save($data) {
        $existing = $this->getByPerusahaanId($data['perusahaan_id']);
        if ($existing) {
            $sql = "UPDATE pengaturan_waktu SET jam_masuk_mulai=?, jam_masuk_selesai=?, jam_pulang_mulai=?, jam_pulang_selesai=? WHERE perusahaan_id=?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$data['jam_masuk_mulai'], $data['jam_masuk_selesai'],
                                   $data['jam_pulang_mulai'], $data['jam_pulang_selesai'],
                                   $data['perusahaan_id']]);
        } else {
            $sql = "INSERT INTO pengaturan_waktu (perusahaan_id, jam_masuk_mulai, jam_masuk_selesai, jam_pulang_mulai, jam_pulang_selesai) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$data['perusahaan_id'], $data['jam_masuk_mulai'], $data['jam_masuk_selesai'],
                                   $data['jam_pulang_mulai'], $data['jam_pulang_selesai']]);
        }
    }
}
