<?php
require_once __DIR__ . '/../models/Absensi.php';
require_once __DIR__ . '/../models/Perizinan.php';
require_once __DIR__ . '/../models/Pembimbing.php';

class PembimbingController {
    private $absensiModel;
    private $perizinanModel;
    private $pembimbingModel;

    public function __construct() {
        cekLogin();
        cekRole(['pembimbing_sekolah', 'pembimbing_pt']);
        $this->absensiModel = new Absensi();
        $this->perizinanModel = new Perizinan();
        $this->pembimbingModel = new PembimbingModel();
    }

    public function dashboard() {
        $siswaBimbingan = $this->pembimbingModel->getSiswaBimbingan(
            $_SESSION['pembimbing_id'], $_SESSION['jenis_pembimbing']
        );
        $pendingAbsensi = $this->absensiModel->getPending($_SESSION['pembimbing_id'], $_SESSION['jenis_pembimbing']);
        $pendingIzin = $this->perizinanModel->getPending($_SESSION['pembimbing_id'], $_SESSION['jenis_pembimbing']);

        $totalSiswa = count($siswaBimbingan);
        $totalPending = count($pendingAbsensi) + count($pendingIzin);

        $hadirHariIni = 0;
        foreach ($siswaBimbingan as $s) {
            if (!empty($s['absen_id']) && ($s['status_datang'] ?? '') == 'approved') {
                $hadirHariIni++;
            }
        }

        $izinHariIni = 0;
        foreach ($siswaBimbingan as $s) {
            if (!empty($s['absen_id']) && ($s['status_datang'] ?? '') == 'izin') {
                $izinHariIni++;
            }
        }

        $totalPerusahaan = 0;
        $perusahaanIds = [];
        foreach ($siswaBimbingan as $s) {
            if (!empty($s['perusahaan_id']) && !in_array($s['perusahaan_id'], $perusahaanIds)) {
                $perusahaanIds[] = $s['perusahaan_id'];
            }
        }
        $totalPerusahaan = count($perusahaanIds);

        include __DIR__ . '/../views/pembimbing/dashboard.php';
    }

    public function approval() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $tipe = $_POST['tipe'];
            $aksi = $_POST['aksi'];

            if ($tipe == 'absen_datang') {
                $aksi == 'approve' ? $this->absensiModel->approveDatang($id) : $this->absensiModel->rejectDatang($id);
            } elseif ($tipe == 'absen_pulang') {
                $aksi == 'approve' ? $this->absensiModel->approvePulang($id) : $this->absensiModel->rejectPulang($id);
            } elseif ($tipe == 'izin') {
                $aksi == 'approve' ? $this->perizinanModel->approve($id, $_SESSION['pembimbing_id']) : $this->perizinanModel->reject($id, $_SESSION['pembimbing_id']);
            }

            header('Location: index.php?page=pembimbing-approval');
            exit;
        }

        $pendingAbsensi = $this->absensiModel->getPending($_SESSION['pembimbing_id'], $_SESSION['jenis_pembimbing']);
        $pendingIzin = $this->perizinanModel->getPending($_SESSION['pembimbing_id'], $_SESSION['jenis_pembimbing']);
        include __DIR__ . '/../views/pembimbing/approval.php';
    }

    public function rekap() {
        $bulan = $_GET['bulan'] ?? date('m');
        $tahun = $_GET['tahun'] ?? date('Y');
        $mode = $_GET['mode'] ?? 'bulan';
        $minggu_ke = $_GET['minggu_ke'] ?? null;

        $rekap = $this->absensiModel->getRekapSiswaByPembimbing(
            $_SESSION['pembimbing_id'], $_SESSION['jenis_pembimbing']
        );

        if ($mode == 'minggu' && $minggu_ke) {
            $tgl_awal = date('Y-m-d', strtotime("{$tahun}-W{$minggu_ke}-1"));
            $tgl_akhir = date('Y-m-d', strtotime("{$tahun}-W{$minggu_ke}-7"));
            $filteredRekap = array_filter($rekap, function($r) use ($tgl_awal, $tgl_akhir) {
                return $r['tanggal'] >= $tgl_awal && $r['tanggal'] <= $tgl_akhir;
            });
        } else {
            $filteredRekap = array_filter($rekap, function($r) use ($bulan, $tahun) {
                $tgl = strtotime($r['tanggal']);
                return date('m', $tgl) == $bulan && date('Y', $tgl) == $tahun;
            });
        }

        include __DIR__ . '/../views/pembimbing/rekap.php';
    }

    public function rekapPdf() {
        require_once __DIR__ . '/../helpers/fpdf.php';

        $bulan = $_GET['bulan'] ?? date('m');
        $tahun = $_GET['tahun'] ?? date('Y');
        $mode = $_GET['mode'] ?? 'bulan';
        $minggu_ke = $_GET['minggu_ke'] ?? null;

        $pembimbing = $this->pembimbingModel->getById($_SESSION['pembimbing_id']);
        $counterpart = $this->pembimbingModel->getCounterpart($_SESSION['pembimbing_id'], $_SESSION['jenis_pembimbing']);

        $rekap = $this->absensiModel->getRekapSiswaByPembimbing(
            $_SESSION['pembimbing_id'], $_SESSION['jenis_pembimbing']
        );

        if ($mode == 'minggu' && $minggu_ke) {
            $tgl_awal = date('Y-m-d', strtotime("{$tahun}-W{$minggu_ke}-1"));
            $tgl_akhir = date('Y-m-d', strtotime("{$tahun}-W{$minggu_ke}-7"));
            $filteredRekap = array_filter($rekap, function($r) use ($tgl_awal, $tgl_akhir) {
                return $r['tanggal'] >= $tgl_awal && $r['tanggal'] <= $tgl_akhir;
            });
            $periodeLabel = "Minggu ke-{$minggu_ke} {$tahun}";
        } else {
            $filteredRekap = array_filter($rekap, function($r) use ($bulan, $tahun) {
                $tgl = strtotime($r['tanggal']);
                return date('m', $tgl) == $bulan && date('Y', $tgl) == $tahun;
            });
            $periodeLabel = strtoupper(date('F', mktime(0,0,0,(int)$bulan,1))) . " {$tahun}";
        }

        $roleLabel = $_SESSION['jenis_pembimbing'] == 'sekolah' ? 'Pembimbing Sekolah' : 'Pembimbing PT';
        $jabatanPembuat = $_SESSION['jenis_pembimbing'] == 'sekolah' ? 'Pembimbing Sekolah' : 'Pembimbing PT';
        $jabatanMengetahui = $_SESSION['jenis_pembimbing'] == 'sekolah' ? 'Pembimbing PT' : 'Pembimbing Sekolah';

        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(true, 25);

        $pdf->SetFont('Times', 'B', 16);
        $pdf->Cell(0, 10, 'REKAP ABSENSI SISWA PKL', 0, 1, 'C');
        $pdf->SetFont('Times', '', 12);
        $pdf->Cell(0, 7, "Periode: {$periodeLabel}", 0, 1, 'C');
        $pdf->Cell(0, 7, "{$roleLabel}: {$pembimbing['nama_lengkap']}", 0, 1, 'C');
        $pdf->Ln(5);

        $pdf->SetFont('Times', 'B', 9);
        $header = ['No', 'Tanggal', 'NISN', 'Nama', 'PT', 'Jam Datang', 'Status', 'Jam Pulang', 'Status'];
        $w = [8, 28, 30, 50, 40, 30, 22, 30, 22];
        foreach ($header as $i => $h) {
            $pdf->Cell($w[$i], 7, $h, 1, 0, 'C');
        }
        $pdf->Ln();

        $pdf->SetFont('Times', '', 8);
        $no = 1;
        foreach ($filteredRekap as $r) {
            $pdf->Cell($w[0], 6, $no++, 1, 0, 'C');
            $pdf->Cell($w[1], 6, $r['tanggal'], 1, 0, 'C');
            $pdf->Cell($w[2], 6, $r['nisn'], 1, 0, 'C');
            $pdf->Cell($w[3], 6, substr($r['nama_lengkap'], 0, 25), 1, 0);
            $pdf->Cell($w[4], 6, substr($r['nama_pt'] ?? '-', 0, 20), 1, 0);
            $pdf->Cell($w[5], 6, $r['jam_datang'] ? date('H:i', strtotime($r['jam_datang'])) : '-', 1, 0, 'C');
            $pdf->Cell($w[6], 6, $r['status_datang'], 1, 0, 'C');
            $pdf->Cell($w[7], 6, $r['jam_pulang'] ? date('H:i', strtotime($r['jam_pulang'])) : '-', 1, 0, 'C');
            $pdf->Cell($w[8], 6, $r['status_pulang'] ?: '-', 1, 0, 'C');
            $pdf->Ln();
        }

        $y = $pdf->GetY() + 15;
        if ($y > 180) { $pdf->AddPage(); $y = $pdf->GetY() + 15; }

        $tableW = array_sum($w);
        $pdf->SetY($y);
        $pdf->SetFont('Times', '', 11);
        $tglSekarang = date('d F Y');
        $pdf->Cell($tableW, 7, "BATAM, {$tglSekarang}", 0, 1, 'R');
        $pdf->Ln(20);

        $halfW = ($pdf->GetPageWidth() - 20) / 2;
        $pdf->Cell($halfW, 7, "Pembimbing", 0, 0, 'C');
        $pdf->Cell($halfW, 7, "Mengetahui", 0, 1, 'C');
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell($halfW, 7, "({$jabatanPembuat})", 0, 0, 'C');
        $pdf->Cell($halfW, 7, "({$jabatanMengetahui})", 0, 1, 'C');
        $pdf->Ln(15);
        $pdf->SetFont('Times', 'BU', 11);
        $pdf->Cell($halfW, 7, $pembimbing['nama_lengkap'], 0, 0, 'C');
        $pdf->Cell($halfW, 7, $counterpart ? $counterpart['nama_lengkap'] : '-', 0, 1, 'C');
        $pdf->SetFont('Times', '', 9);
        $pdf->Cell($halfW, 6, "NIP/NIK: {$pembimbing['nip_nik']}", 0, 0, 'C');
        $pdf->Cell($halfW, 6, $counterpart ? "NIP/NIK: {$counterpart['nip_nik']}" : '-', 0, 1, 'C');

        $pdf->Output('I', "Rekap_Absensi_{$periodeLabel}.pdf");
        exit;
    }
}
