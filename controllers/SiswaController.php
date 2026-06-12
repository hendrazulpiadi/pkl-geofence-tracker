<?php
require_once __DIR__ . '/../models/Absensi.php';
require_once __DIR__ . '/../models/Siswa.php';
require_once __DIR__ . '/../models/Perizinan.php';
require_once __DIR__ . '/../models/PengaturanWaktu.php';

class SiswaController {
    private $absensiModel;
    private $siswaModel;
    private $perizinanModel;

    public function __construct() {
        cekLogin();
        cekRole('siswa');
        $this->absensiModel = new Absensi();
        $this->siswaModel = new SiswaModel();
        $this->perizinanModel = new Perizinan();
    }

    public function dashboard() {
        $siswa = $this->siswaModel->getById($_SESSION['siswa_id']);
        $riwayat = $this->absensiModel->getRiwayat($_SESSION['siswa_id'], 5);
        $absenHariIni = $this->absensiModel->cekAbsenHariIni($_SESSION['siswa_id']);
        include __DIR__ . '/../views/siswa/dashboard.php';
    }

    public function absen() {
        $siswa = $this->siswaModel->getById($_SESSION['siswa_id']);
        $pengaturanModel = new PengaturanWaktu();
        $jadwal = $pengaturanModel->getByPerusahaanId($siswa['perusahaan_id']);
        $absenHariIni = $this->absensiModel->cekAbsenHariIni($_SESSION['siswa_id']);

        $waktuSekarang = date('H:i:s');
        $bolehDatang = false;
        $bolehPulang = false;

        if ($jadwal) {
            $bolehDatang = $waktuSekarang >= $jadwal['jam_masuk_mulai'] && $waktuSekarang <= $jadwal['jam_masuk_selesai'];
            $bolehPulang = $waktuSekarang >= $jadwal['jam_pulang_mulai'] && $waktuSekarang <= $jadwal['jam_pulang_selesai'];
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tipe = $_POST['tipe'] ?? 'datang';

            if ($tipe == 'datang' && $jadwal && !$bolehDatang) {
                $error = "Belum waktunya absen datang. Jadwal masuk: {$jadwal['jam_masuk_mulai']} - {$jadwal['jam_masuk_selesai']}";
                include __DIR__ . '/../views/siswa/absen.php';
                return;
            }
            if ($tipe == 'pulang' && $jadwal && !$bolehPulang) {
                $error = "Belum waktunya absen pulang. Jadwal pulang: {$jadwal['jam_pulang_mulai']} - {$jadwal['jam_pulang_selesai']}";
                include __DIR__ . '/../views/siswa/absen.php';
                return;
            }

            $lat = (float)($_POST['latitude'] ?? 0);
            $lng = (float)($_POST['longitude'] ?? 0);
            $foto = $_POST['foto_data'] ?? '';

            $radiusPT = (float)($siswa['radius_toleransi'] ?? 0);

            if ($lat == 0 && $lng == 0) {
                $jarak = 0;
            } else {
                $jarak = hitungJarak($lat, $lng, $siswa['latitude'], $siswa['longitude']);
            }

            if ($radiusPT > 0 && $jarak > $radiusPT) {
                $error = "Anda berada di luar radius absen (" . round($jarak) . " m dari PT)";
                include __DIR__ . '/../views/siswa/absen.php';
                return;
            }

            $fotoName = '';
            if ($foto) {
                $ext = 'jpg';
                if (preg_match('/^data:image\/(\w+);base64,/', $foto, $m)) {
                    $ext = $m[1] == 'png' ? 'png' : 'jpg';
                }
                $fotoName = 'selfie_' . $_SESSION['siswa_id'] . '_' . time() . '.' . $ext;
                $fotoPath = __DIR__ . '/../uploads/foto/' . $fotoName;
                $foto = preg_replace('/^data:image\/\w+;base64,/', '', $foto);
                $foto = str_replace(' ', '+', $foto);
                file_put_contents($fotoPath, base64_decode($foto));
            }

            if ($tipe == 'datang') {
                $this->absensiModel->absenDatang($_SESSION['siswa_id'], $lat, $lng, $fotoName);
            } else {
                $this->absensiModel->absenPulang($_SESSION['siswa_id'], $lat, $lng, $fotoName);
            }

            header('Location: index.php?page=siswa-dashboard&success=1');
            exit;
        }

        include __DIR__ . '/../views/siswa/absen.php';
    }

    public function izin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bukti = '';
            if (isset($_FILES['bukti_foto']) && $_FILES['bukti_foto']['error'] == 0) {
                $ext = pathinfo($_FILES['bukti_foto']['name'], PATHINFO_EXTENSION);
                $bukti = 'izin_' . $_SESSION['siswa_id'] . '_' . time() . '.' . $ext;
                move_uploaded_file($_FILES['bukti_foto']['tmp_name'], __DIR__ . '/../uploads/foto/' . $bukti);
            }

            $this->perizinanModel->create([
                'siswa_id' => $_SESSION['siswa_id'],
                'jenis' => $_POST['jenis'],
                'tgl_mulai' => $_POST['tgl_mulai'],
                'tgl_selesai' => $_POST['tgl_selesai'],
                'keterangan' => $_POST['keterangan'],
                'bukti_foto' => $bukti,
            ]);

            header('Location: index.php?page=siswa-izin&success=1');
            exit;
        }

        $riwayatIzin = $this->perizinanModel->getBySiswaId($_SESSION['siswa_id']);
        include __DIR__ . '/../views/siswa/izin.php';
    }
}
