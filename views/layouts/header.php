<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Absensi PKL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php?page=<?= $_SESSION['role'] ?>-dashboard">
            <i class="bi bi-building"></i> Absensi PKL
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <?php if ($_SESSION['role'] == 'admin'): ?>
                <li class="nav-item"><a class="nav-link" href="index.php?page=admin-dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?page=admin-siswa"><i class="bi bi-people"></i> Siswa</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?page=admin-pembimbing"><i class="bi bi-person-badge"></i> Pembimbing</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?page=admin-perusahaan"><i class="bi bi-building"></i> Perusahaan</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?page=admin-pengaturan-waktu"><i class="bi bi-clock"></i> Jadwal</a></li>
                <?php elseif (in_array($_SESSION['role'], ['pembimbing_sekolah', 'pembimbing_pt'])): ?>
                <li class="nav-item"><a class="nav-link" href="index.php?page=pembimbing-dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?page=pembimbing-approval"><i class="bi bi-check-circle"></i> Approval</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?page=pembimbing-rekap"><i class="bi bi-file-text"></i> Rekap</a></li>
                <?php elseif ($_SESSION['role'] == 'siswa'): ?>
                <li class="nav-item"><a class="nav-link" href="index.php?page=siswa-dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?page=siswa-absen"><i class="bi bi-geo-alt"></i> Absensi</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?page=siswa-izin"><i class="bi bi-file-earmark-text"></i> Izin/Sakit</a></li>
                <?php endif; ?>
            </ul>
            <span class="navbar-text text-white me-3">
                <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['nama_lengkap'] ?? $_SESSION['username']) ?>
            </span>
            <a href="index.php?page=logout" class="btn btn-light btn-sm"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>
    </div>
</nav>
<div class="container-fluid mt-3">
