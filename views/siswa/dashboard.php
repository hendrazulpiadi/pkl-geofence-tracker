<div class="bento-grid">
    <?php if (isset($_GET['success'])): ?>
    <div class="bento-full">
        <div class="alert alert-success alert-dismissible fade show">Absen berhasil dikirim! Menunggu persetujuan pembimbing.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    </div>
    <?php endif; ?>

    <div class="bento-full">
        <div class="hero-welcome animate-fade-in-up">
            <div class="hero-content">
                <div class="hero-greeting">Halo, <?= htmlspecialchars($siswa['nama_lengkap']) ?> 👋</div>
                <div class="hero-text">Selamat datang di sistem absensi PKL. Jangan lupa absen hari ini!</div>
                <div class="hero-stats">
                    <div class="hero-stat-item">
                        <span class="hero-stat-value"><?= htmlspecialchars($siswa['nisn'] ?? '-') ?></span>
                        <span class="hero-stat-label">NISN</span>
                    </div>
                    <div class="hero-stat-item">
                        <span class="hero-stat-value"><?= htmlspecialchars($siswa['kelas'] ?? '-') ?></span>
                        <span class="hero-stat-label">Kelas</span>
                    </div>
                    <div class="hero-stat-item">
                        <span class="hero-stat-value"><?= htmlspecialchars($siswa['nama_pt'] ?? '-') ?></span>
                        <span class="hero-stat-label">Tempat PKL</span>
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
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;">
            <div class="stat-card animate-fade-in-up animate-delay-1">
                <div class="stat-icon stat-icon-primary"><i class="bi bi-check-circle"></i></div>
                <div class="stat-value"><?= $absenHariIni && $absenHariIni['status_datang'] == 'approved' ? 'Hadir' : ($absenHariIni ? 'Menunggu' : 'Belum Absen') ?></div>
                <div class="stat-label">Status Hari Ini</div>
            </div>
            <div class="stat-card animate-fade-in-up animate-delay-2">
                <div class="stat-icon stat-icon-success"><i class="bi bi-clock"></i></div>
                <div class="stat-value"><?= $absenHariIni && $absenHariIni['jam_datang'] ? date('H:i', strtotime($absenHariIni['jam_datang'])) : '-' ?></div>
                <div class="stat-label">Jam Datang</div>
            </div>
            <div class="stat-card animate-fade-in-up animate-delay-3">
                <div class="stat-icon stat-icon-info"><i class="bi bi-building"></i></div>
                <div class="stat-value"><?= htmlspecialchars($siswa['nama_pt'] ?? '-') ?></div>
                <div class="stat-label">Tempat PKL</div>
            </div>
        </div>
    </div>

    <div class="bento-full">
        <div class="quick-actions" style="margin-bottom:24px;">
            <a href="<?= BASE_URL ?>/index.php?page=siswa-absen" class="quick-action-card">
                <div class="qa-icon blue">📷</div>
                <div class="qa-title">Absen Sekarang</div>
                <div class="qa-desc">Selfie & GPS</div>
            </a>
            <a href="<?= BASE_URL ?>/index.php?page=siswa-riwayat" class="quick-action-card">
                <div class="qa-icon green">📋</div>
                <div class="qa-title">Riwayat Absensi</div>
                <div class="qa-desc">Catatan kehadiran</div>
            </a>
            <a href="<?= BASE_URL ?>/index.php?page=siswa-izin" class="quick-action-card">
                <div class="qa-icon orange">📄</div>
                <div class="qa-title">Izin / Sakit</div>
                <div class="qa-desc">Ajukan izin atau sakit</div>
            </a>
            <a href="<?= BASE_URL ?>/index.php?page=siswa-profil" class="quick-action-card">
                <div class="qa-icon purple">👤</div>
                <div class="qa-title">Profil Saya</div>
                <div class="qa-desc">Informasi akun</div>
            </a>
        </div>
    </div>

    <div class="bento-full" style="margin-top:-16px;">
        <div class="recent-timeline">
            <div class="timeline-header">
                <h3>Riwayat Terbaru</h3>
                <a href="<?= BASE_URL ?>/index.php?page=siswa-riwayat" style="font-size:13px;color:var(--primary);font-weight:500;text-decoration:none;">Lihat Semua</a>
            </div>
            <div class="timeline-list">
                <?php foreach ($riwayat as $r):
                    $dotClass = $r['status_datang'] == 'approved' ? 'dot-success' : ($r['status_datang'] == 'rejected' ? 'dot-danger' : 'dot-warning');
                    $statusLabel = $r['status_datang'] == 'approved' ? 'Disetujui' : ($r['status_datang'] == 'rejected' ? 'Ditolak' : 'Menunggu');
                ?>
                <div class="timeline-item">
                    <div class="timeline-dot <?= $dotClass ?>"></div>
                    <div class="timeline-content">
                        <div class="timeline-top">
                            <div>
                                <div class="timeline-type">Absen Datang</div>
                                <div class="timeline-date"><?= date('d F Y', strtotime($r['tanggal'])) ?></div>
                            </div>
                            <span class="badge bg-<?= $r['status_datang']=='approved'?'success':($r['status_datang']=='rejected'?'danger':'warning') ?>"><?= $statusLabel ?></span>
                        </div>
                        <div class="timeline-meta">
                            <span><i class="bi bi-clock"></i> <?= date('H:i', strtotime($r['jam_datang'])) ?></span>
                            <?php if ($r['jam_pulang']): ?><span><i class="bi bi-clock"></i> Pulang: <?= date('H:i', strtotime($r['jam_pulang'])) ?></span><?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php if (empty($riwayat)): ?>
                <p style="text-align:center;color:var(--text-muted);padding:20px 0;margin:0;">Belum ada riwayat absensi</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
