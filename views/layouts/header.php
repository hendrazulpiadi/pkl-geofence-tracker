<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Absensi PKL</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css?v=<?= filemtime(dirname(__DIR__, 2) . '/assets/css/style.css') ?>">
</head>
<body>

<?php
$currentPage = $_GET['page'] ?? '';
$userName = htmlspecialchars($_SESSION['nama_lengkap'] ?? $_SESSION['username']);
$initial = strtoupper(substr($userName, 0, 1));

$hasSidebar = in_array($_SESSION['role'] ?? '', ['admin', 'pembimbing_sekolah', 'pembimbing_pt', 'siswa']);
?>

<?php if ($hasSidebar): ?>
<?php
$pageTitles = [
    'admin-dashboard' => 'Dashboard',
    'admin-siswa' => 'Manajemen Siswa',
    'admin-pembimbing' => 'Manajemen Pembimbing',
    'admin-perusahaan' => 'Manajemen Perusahaan',
    'admin-pengaturan-waktu' => 'Pengaturan Jadwal Absen',
    'pembimbing-dashboard' => 'Dashboard',
    'pembimbing-approval' => 'Approval Absensi',
    'pembimbing-rekap' => 'Rekapitulasi Absensi',
    'siswa-dashboard' => 'Dashboard',
    'siswa-absen' => 'Absensi',
    'siswa-riwayat' => 'Riwayat Absensi',
    'siswa-izin' => 'Pengajuan Izin',
    'siswa-profil' => 'Profil Saya',
    'siswa-detail' => 'Detail Absensi',
];
$pageTitle = $pageTitles[$currentPage] ?? 'Dashboard';

$roleLabel = 'Administrator';
$roleIcon = '🏠';
if (in_array($_SESSION['role'] ?? '', ['pembimbing_sekolah', 'pembimbing_pt'])) {
    $jenis = $_SESSION['jenis_pembimbing'] ?? '';
    $roleLabel = $jenis == 'sekolah' ? 'Pembimbing Sekolah' : 'Pembimbing PT';
} elseif (($_SESSION['role'] ?? '') == 'siswa') {
    $roleLabel = 'Siswa PKL';
}

$isAdmin = $_SESSION['role'] == 'admin';
$isPembimbing = in_array($_SESSION['role'] ?? '', ['pembimbing_sekolah', 'pembimbing_pt']);
$isSiswa = $_SESSION['role'] == 'siswa';
?>
<div class="admin-wrapper">
    <aside class="sidebar" id="adminSidebar">
        <div class="sidebar-logo">
            <div class="logo-icon">SIA</div>
            <div class="logo-text">SIA <span>PKL</span></div>
        </div>
        <nav class="sidebar-menu">
            <?php if ($isAdmin): ?>
            <div class="menu-label">Menu Utama</div>
            <a href="<?= BASE_URL ?>/index.php?page=admin-dashboard" class="nav-item <?= $currentPage == 'admin-dashboard' ? 'active' : '' ?>">
                <span class="nav-icon">🏠</span> Dashboard
            </a>
            <a href="<?= BASE_URL ?>/index.php?page=admin-siswa" class="nav-item <?= $currentPage == 'admin-siswa' ? 'active' : '' ?>">
                <span class="nav-icon">👥</span> Siswa
            </a>
            <a href="<?= BASE_URL ?>/index.php?page=admin-pembimbing" class="nav-item <?= $currentPage == 'admin-pembimbing' ? 'active' : '' ?>">
                <span class="nav-icon">👨‍🏫</span> Pembimbing
            </a>
            <a href="<?= BASE_URL ?>/index.php?page=admin-perusahaan" class="nav-item <?= $currentPage == 'admin-perusahaan' ? 'active' : '' ?>">
                <span class="nav-icon">🏢</span> Perusahaan
            </a>
            <div class="menu-label">Pengaturan</div>
            <a href="<?= BASE_URL ?>/index.php?page=admin-pengaturan-waktu" class="nav-item <?= $currentPage == 'admin-pengaturan-waktu' ? 'active' : '' ?>">
                <span class="nav-icon">⏰</span> Jadwal Absen
            </a>
            <?php elseif ($isPembimbing): ?>
            <div class="menu-label">Menu Utama</div>
            <a href="<?= BASE_URL ?>/index.php?page=pembimbing-dashboard" class="nav-item <?= $currentPage == 'pembimbing-dashboard' ? 'active' : '' ?>">
                <span class="nav-icon">🏠</span> Dashboard
            </a>
            <a href="<?= BASE_URL ?>/index.php?page=pembimbing-approval" class="nav-item <?= $currentPage == 'pembimbing-approval' ? 'active' : '' ?>">
                <span class="nav-icon">✅</span> Approval
            </a>
            <a href="<?= BASE_URL ?>/index.php?page=pembimbing-rekap" class="nav-item <?= $currentPage == 'pembimbing-rekap' ? 'active' : '' ?>">
                <span class="nav-icon">📊</span> Rekapitulasi
            </a>
            <?php elseif ($isSiswa): ?>
            <div class="menu-label">Menu Utama</div>
            <a href="<?= BASE_URL ?>/index.php?page=siswa-dashboard" class="nav-item <?= $currentPage == 'siswa-dashboard' ? 'active' : '' ?>">
                <span class="nav-icon">🏠</span> Dashboard
            </a>
            <a href="<?= BASE_URL ?>/index.php?page=siswa-absen" class="nav-item <?= strpos($currentPage, 'siswa-absen') === 0 ? 'active' : '' ?>">
                <span class="nav-icon">📷</span> Absensi
            </a>
            <a href="<?= BASE_URL ?>/index.php?page=siswa-riwayat" class="nav-item <?= $currentPage == 'siswa-riwayat' ? 'active' : '' ?>">
                <span class="nav-icon">📋</span> Riwayat
            </a>
            <a href="<?= BASE_URL ?>/index.php?page=siswa-izin" class="nav-item <?= $currentPage == 'siswa-izin' ? 'active' : '' ?>">
                <span class="nav-icon">📄</span> Izin / Sakit
            </a>
            <div class="menu-label">Akun</div>
            <a href="<?= BASE_URL ?>/index.php?page=siswa-profil" class="nav-item <?= $currentPage == 'siswa-profil' ? 'active' : '' ?>">
                <span class="nav-icon">👤</span> Profil Saya
            </a>
            <?php endif; ?>
        </nav>
        <div class="sidebar-footer">
            <a href="<?= BASE_URL ?>/index.php?page=logout" class="nav-item">
                <span class="nav-icon">🚪</span> Keluar
            </a>
        </div>
    </aside>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="admin-main">
        <header class="top-nav">
            <div style="display:flex;align-items:center;gap:16px;">
                <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
                    <i class="bi bi-list"></i>
                </button>
                <div>
                    <div class="page-title">
                        <?= $pageTitle ?>
                        <span class="page-subtitle">Sistem Absensi PKL</span>
                    </div>
                </div>
            </div>
            <div class="top-nav-right">
                <button class="notification-btn" title="Notifikasi">
                    <i class="bi bi-bell"></i>
                    <span class="badge-dot"></span>
                </button>
                <div class="user-profile">
                    <div class="user-avatar"><?= $initial ?></div>
                    <div class="user-info">
                        <div class="user-name"><?= $userName ?></div>
                        <div class="user-role"><?= $roleLabel ?></div>
                    </div>
                </div>
            </div>
        </header>
        <main class="admin-content">
<?php else: ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= BASE_URL ?>/index.php?page=<?= $_SESSION['role'] ?>-dashboard">
            <i class="bi bi-building"></i> Absensi PKL
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <?php if ($_SESSION['role'] == 'siswa'): ?>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/index.php?page=siswa-dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/index.php?page=siswa-absen"><i class="bi bi-geo-alt"></i> Absensi</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/index.php?page=siswa-izin"><i class="bi bi-file-earmark-text"></i> Izin/Sakit</a></li>
                <?php endif; ?>
            </ul>
            <span class="navbar-text text-white me-3">
                <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['nama_lengkap'] ?? $_SESSION['username']) ?>
            </span>
            <a href="<?= BASE_URL ?>/index.php?page=logout" class="btn btn-light btn-sm"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>
    </div>
</nav>
<div class="container-fluid mt-3">
<?php endif; ?>
