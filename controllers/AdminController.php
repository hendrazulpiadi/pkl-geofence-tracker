<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Siswa.php';
require_once __DIR__ . '/../models/Pembimbing.php';
require_once __DIR__ . '/../models/Perusahaan.php';
require_once __DIR__ . '/../models/PengaturanWaktu.php';

class AdminController {
    private $userModel;
    private $siswaModel;
    private $pembimbingModel;
    private $perusahaanModel;

    public function __construct() {
        cekLogin();
        cekRole('admin');
        $this->userModel = new User();
        $this->siswaModel = new SiswaModel();
        $this->pembimbingModel = new PembimbingModel();
        $this->perusahaanModel = new Perusahaan();
    }

    public function dashboard() {
        $totalSiswa = count($this->siswaModel->getAll());
        $totalPembimbing = count($this->pembimbingModel->getAll());
        $totalPerusahaan = count($this->perusahaanModel->getAll());
        include __DIR__ . '/../views/admin/dashboard.php';
    }

    public function siswa() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action'])) {
                try {
                    if ($_POST['action'] == 'create') {
                        $result = $this->siswaModel->create($_POST);
                        if ($result) {
                            setFlash('siswa', 'Data siswa berhasil ditambahkan.', 'success');
                        } else {
                            setFlash('siswa', 'Gagal menambahkan data siswa. Periksa kembali inputan.', 'danger');
                        }
                    } elseif ($_POST['action'] == 'update') {
                        $result = $this->siswaModel->update($_POST['id'], $_POST);
                        if ($result) {
                            setFlash('siswa', 'Data siswa berhasil diperbarui.', 'success');
                        } else {
                            setFlash('siswa', 'Gagal memperbarui data siswa.', 'danger');
                        }
                    } elseif ($_POST['action'] == 'delete') {
                        $result = $this->siswaModel->delete($_POST['id']);
                        if ($result) {
                            setFlash('siswa', 'Data siswa berhasil dihapus.', 'success');
                        } else {
                            setFlash('siswa', 'Gagal menghapus data siswa.', 'danger');
                        }
                    }
                } catch (Exception $e) {
                    setFlash('siswa', 'Terjadi kesalahan: ' . $e->getMessage(), 'danger');
                }
            }
            header('Location: index.php?page=admin-siswa');
            exit;
        }
        $siswa = $this->siswaModel->getAll();
        $pembimbingSekolah = array_filter($this->pembimbingModel->getAll(), fn($p) => $p['jenis'] == 'sekolah');
        $pembimbingPt = array_filter($this->pembimbingModel->getAll(), fn($p) => $p['jenis'] == 'pt');
        $perusahaan = $this->perusahaanModel->getAll();
        include __DIR__ . '/../views/admin/siswa.php';
    }

    public function pembimbing() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action'])) {
                try {
                    if ($_POST['action'] == 'create') {
                        $result = $this->pembimbingModel->create($_POST);
                        if ($result) {
                            setFlash('pembimbing', 'Data pembimbing berhasil ditambahkan.', 'success');
                        } else {
                            setFlash('pembimbing', 'Gagal menambahkan data pembimbing. Mungkin username sudah digunakan.', 'danger');
                        }
                    } elseif ($_POST['action'] == 'update') {
                        $result = $this->pembimbingModel->update($_POST['id'], $_POST);
                        if ($result) {
                            setFlash('pembimbing', 'Data pembimbing berhasil diperbarui.', 'success');
                        } else {
                            setFlash('pembimbing', 'Gagal memperbarui data pembimbing.', 'danger');
                        }
                    } elseif ($_POST['action'] == 'delete') {
                        $result = $this->pembimbingModel->delete($_POST['id']);
                        if ($result) {
                            setFlash('pembimbing', 'Data pembimbing berhasil dihapus.', 'success');
                        } else {
                            setFlash('pembimbing', 'Gagal menghapus data pembimbing.', 'danger');
                        }
                    }
                } catch (Exception $e) {
                    setFlash('pembimbing', 'Terjadi kesalahan: ' . $e->getMessage(), 'danger');
                }
            }
            header('Location: index.php?page=admin-pembimbing');
            exit;
        }
        $pembimbing = $this->pembimbingModel->getAll();
        $perusahaan = $this->perusahaanModel->getAll();
        include __DIR__ . '/../views/admin/pembimbing.php';
    }

    public function perusahaan() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action'])) {
                try {
                    if ($_POST['action'] == 'create') {
                        $result = $this->perusahaanModel->create($_POST);
                        if ($result) {
                            setFlash('perusahaan', 'Data perusahaan berhasil ditambahkan.', 'success');
                        } else {
                            setFlash('perusahaan', 'Gagal menambahkan data perusahaan.', 'danger');
                        }
                    } elseif ($_POST['action'] == 'update') {
                        $result = $this->perusahaanModel->update($_POST['id'], $_POST);
                        if ($result) {
                            setFlash('perusahaan', 'Data perusahaan berhasil diperbarui.', 'success');
                        } else {
                            setFlash('perusahaan', 'Gagal memperbarui data perusahaan.', 'danger');
                        }
                    } elseif ($_POST['action'] == 'delete') {
                        $result = $this->perusahaanModel->delete($_POST['id']);
                        if ($result) {
                            setFlash('perusahaan', 'Data perusahaan berhasil dihapus.', 'success');
                        } else {
                            setFlash('perusahaan', 'Gagal menghapus data perusahaan.', 'danger');
                        }
                    }
                } catch (Exception $e) {
                    setFlash('perusahaan', 'Terjadi kesalahan: ' . $e->getMessage(), 'danger');
                }
            }
            header('Location: index.php?page=admin-perusahaan');
            exit;
        }
        $perusahaan = $this->perusahaanModel->getAll();
        include __DIR__ . '/../views/admin/perusahaan.php';
    }

    public function pengaturanWaktu() {
        $pengaturanModel = new PengaturanWaktu();
        $pengaturan = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pengaturanModel->save($_POST);
            setFlash('waktu', 'Pengaturan waktu berhasil disimpan.', 'success');
            header('Location: index.php?page=admin-pengaturan-waktu');
            exit;
        }
        $perusahaan = $this->perusahaanModel->getAll();
        $selectedPerusahaanId = $_GET['perusahaan_id'] ?? null;
        if ($selectedPerusahaanId) {
            $pengaturan = $pengaturanModel->getByPerusahaanId($selectedPerusahaanId);
        }
        include __DIR__ . '/../views/admin/pengaturan_waktu.php';
    }
}
