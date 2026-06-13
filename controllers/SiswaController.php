<?php
require_once __DIR__ . '/../models/Absensi.php';
require_once __DIR__ . '/../models/Siswa.php';
require_once __DIR__ . '/../models/Perizinan.php';
require_once __DIR__ . '/../models/PengaturanWaktu.php';
require_once __DIR__ . '/../models/Pembimbing.php';

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

            header('Location: index.php?page=siswa-absen&success=1');
            exit;
        }

        include __DIR__ . '/../views/siswa/absen.php';
    }

    public function riwayat() {
        $siswa = $this->siswaModel->getById($_SESSION['siswa_id']);

        $filter = $_GET['filter'] ?? 'bulan';
        $tanggalCari = $_GET['tanggal'] ?? '';

        switch ($filter) {
            case 'hari':
                $mulai = date('Y-m-d');
                $akhir = date('Y-m-d');
                break;
            case 'minggu':
                $mulai = date('Y-m-d', strtotime('monday this week'));
                $akhir = date('Y-m-d', strtotime('sunday this week'));
                break;
            case 'bulan':
            default:
                $mulai = date('Y-m-01');
                $akhir = date('Y-m-t');
                break;
        }

        if ($tanggalCari) {
            $mulai = $tanggalCari;
            $akhir = $tanggalCari;
        }

        $riwayat = $this->absensiModel->getRiwayat($_SESSION['siswa_id'], 90);
        $filteredRiwayat = array_filter($riwayat, function($r) use ($mulai, $akhir) {
            return $r['tanggal'] >= $mulai && $r['tanggal'] <= $akhir;
        });

        $totalHadir = 0;
        $totalIzin = 0;
        $totalAlpa = 0;
        $totalHari = count($filteredRiwayat);

        foreach ($filteredRiwayat as $r) {
            if ($r['status_datang'] == 'approved') $totalHadir++;
            elseif ($r['status_datang'] == 'rejected') $totalAlpa++;
        }

        $izinList = $this->perizinanModel->getBySiswaId($_SESSION['siswa_id']);
        foreach ($izinList as $izin) {
            if ($izin['status_approval'] == 'approved') {
                $tglMulai = new DateTime($izin['tgl_mulai']);
                $tglSelesai = new DateTime($izin['tgl_selesai']);
                $diff = $tglMulai->diff($tglSelesai)->days + 1;
                if ($tglMulai >= new DateTime($mulai) && $tglSelesai <= new DateTime($akhir)) {
                    $totalIzin += $diff;
                }
            }
        }

        $persentase = $totalHari > 0 ? round(($totalHadir / $totalHari) * 100) : 0;

        include __DIR__ . '/../views/siswa/riwayat.php';
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

    public function profil() {
        $siswa = $this->siswaModel->getById($_SESSION['siswa_id']);

        $pembimbingSekolah = null;
        $pembimbingPt = null;
        if ($siswa['pembimbing_sekolah_id']) {
            $pm = new PembimbingModel();
            $pembimbingSekolah = $pm->getById($siswa['pembimbing_sekolah_id']);
        }
        if ($siswa['pembimbing_pt_id']) {
            $pm = new PembimbingModel();
            $pembimbingPt = $pm->getById($siswa['pembimbing_pt_id']);
        }

        $riwayat = $this->absensiModel->getRiwayat($_SESSION['siswa_id'], 365);
        $totalHadir = 0;
        $totalAlpa = 0;
        foreach ($riwayat as $r) {
            if ($r['status_datang'] == 'approved') $totalHadir++;
            elseif ($r['status_datang'] == 'rejected') $totalAlpa++;
        }
        $totalIzin = count($this->perizinanModel->getBySiswaId($_SESSION['siswa_id']));
        $totalAll = count($riwayat);
        $persentase = $totalAll > 0 ? round(($totalHadir / $totalAll) * 100) : 0;

        include __DIR__ . '/../views/siswa/profil.php';
    }

    public function detail() {
        $id = $_GET['id'] ?? 0;
        $absensi = $this->absensiModel->getById($id);

        if (!$absensi || $absensi['siswa_id'] != $_SESSION['siswa_id']) {
            header('Location: index.php?page=siswa-riwayat');
            exit;
        }

        include __DIR__ . '/../views/siswa/detail.php';
    }
}
