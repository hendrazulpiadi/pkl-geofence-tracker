<div class="bento-grid">
    <div class="bento-full">
        <div class="hero-welcome animate-fade-in-up">
            <div class="hero-content">
                <div class="hero-greeting">Halo, <?= htmlspecialchars($_SESSION['nama_lengkap'] ?? $_SESSION['username']) ?> 👋</div>
                <div class="hero-text">Selamat datang di panel administrasi Sistem Absensi PKL</div>
                <div class="hero-stats">
                    <div class="hero-stat-item">
                        <span class="hero-stat-value"><?= $totalSiswa ?></span>
                        <span class="hero-stat-label">Total Siswa</span>
                    </div>
                    <div class="hero-stat-item">
                        <span class="hero-stat-value"><?= $totalPembimbing ?></span>
                        <span class="hero-stat-label">Total Pembimbing</span>
                    </div>
                    <div class="hero-stat-item">
                        <span class="hero-stat-value"><?= $totalPerusahaan ?></span>
                        <span class="hero-stat-label">Total Perusahaan</span>
                    </div>
                </div>
            </div>
            <div class="hero-date">
                <?= date('j') ?>
                <span class="hero-date-day"><?= date('F Y') ?></span>
                <?= date('l') ?>
            </div>
        </div>
    </div>

    <div class="bento-full">
        <div class="bento-grid bento-grid-3">
            <div class="stat-card animate-fade-in-up animate-delay-1">
                <div class="stat-header">
                    <div class="stat-icon blue"><i class="bi bi-people"></i></div>
                    <span class="stat-trend up">+<?= $totalSiswa ?></span>
                </div>
                <div class="stat-value"><?= $totalSiswa ?></div>
                <div class="stat-label">Total Siswa Terdaftar</div>
                <a href="<?= BASE_URL ?>/index.php?page=admin-siswa" class="stat-action">Kelola Siswa <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="stat-card animate-fade-in-up animate-delay-2">
                <div class="stat-header">
                    <div class="stat-icon green"><i class="bi bi-person-badge"></i></div>
                    <span class="stat-trend up"><?= $totalPembimbing ?></span>
                </div>
                <div class="stat-value"><?= $totalPembimbing ?></div>
                <div class="stat-label">Total Pembimbing</div>
                <a href="<?= BASE_URL ?>/index.php?page=admin-pembimbing" class="stat-action">Kelola Pembimbing <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="stat-card animate-fade-in-up animate-delay-3">
                <div class="stat-header">
                    <div class="stat-icon purple"><i class="bi bi-building"></i></div>
                    <span class="stat-trend up"><?= $totalPerusahaan ?></span>
                </div>
                <div class="stat-value"><?= $totalPerusahaan ?></div>
                <div class="stat-label">Total Perusahaan Mitra</div>
                <a href="<?= BASE_URL ?>/index.php?page=admin-perusahaan" class="stat-action">Kelola Perusahaan <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>

    <div class="bento-full">
        <div class="section-header">
            <h2>Aksi Cepat</h2>
        </div>
        <div class="quick-actions">
            <a href="<?= BASE_URL ?>/index.php?page=admin-siswa" class="quick-action-card animate-fade-in-up animate-delay-1">
                <div class="qa-icon blue"><i class="bi bi-person-plus-fill"></i></div>
                <div class="qa-title">Tambah Siswa</div>
                <div class="qa-desc">Daftarkan siswa baru</div>
            </a>
            <a href="<?= BASE_URL ?>/index.php?page=admin-pembimbing" class="quick-action-card animate-fade-in-up animate-delay-2">
                <div class="qa-icon green"><i class="bi bi-person-plus-fill"></i></div>
                <div class="qa-title">Tambah Pembimbing</div>
                <div class="qa-desc">Daftarkan pembimbing baru</div>
            </a>
            <a href="<?= BASE_URL ?>/index.php?page=admin-perusahaan" class="quick-action-card animate-fade-in-up animate-delay-3">
                <div class="qa-icon orange"><i class="bi bi-building-add"></i></div>
                <div class="qa-title">Tambah Perusahaan</div>
                <div class="qa-desc">Daftarkan mitra baru</div>
            </a>
            <a href="<?= BASE_URL ?>/index.php?page=admin-pengaturan-waktu" class="quick-action-card animate-fade-in-up animate-delay-4">
                <div class="qa-icon purple"><i class="bi bi-clock-history"></i></div>
                <div class="qa-title">Atur Jadwal</div>
                <div class="qa-desc">Konfigurasi waktu absen</div>
            </a>
        </div>
    </div>
</div>
