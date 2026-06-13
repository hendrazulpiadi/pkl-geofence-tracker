<div class="bento-grid">
    <div class="bento-full animate-fade-in-up">
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:8px;">
            <h3 style="margin:0;font-size:20px;font-weight:700;">Riwayat Absensi</h3>
            <a href="<?= BASE_URL ?>/index.php?page=pembimbing-rekap-pdf" class="btn" style="background:var(--card);border:1.5px solid var(--border);padding:8px 18px;border-radius:var(--radius-sm);font-size:13px;font-weight:500;text-decoration:none;color:var(--text-primary);">
                <i class="bi bi-download"></i> Download PDF
            </a>
        </div>
    </div>

    <div class="bento-full animate-fade-in-up animate-delay-1">
        <div class="filter-bar">
            <a href="?page=siswa-riwayat&filter=hari" class="filter-btn <?= $filter == 'hari' ? 'active' : '' ?>">Hari Ini</a>
            <a href="?page=siswa-riwayat&filter=minggu" class="filter-btn <?= $filter == 'minggu' ? 'active' : '' ?>">Minggu Ini</a>
            <a href="?page=siswa-riwayat&filter=bulan" class="filter-btn <?= $filter == 'bulan' ? 'active' : '' ?>">Bulan Ini</a>
            <input type="date" class="filter-date" id="filterDate" value="<?= $tanggalCari ?>" onchange="if(this.value) window.location='?page=siswa-riwayat&tanggal='+this.value;">
        </div>
    </div>

    <div class="bento-full animate-fade-in-up animate-delay-1">
        <div class="stats-bento">
            <div class="stat-mini">
                <div class="stat-mini-value hadir"><?= $totalHadir ?></div>
                <div class="stat-mini-label">Hadir</div>
            </div>
            <div class="stat-mini">
                <div class="stat-mini-value izin"><?= $totalIzin ?></div>
                <div class="stat-mini-label">Izin</div>
            </div>
            <div class="stat-mini">
                <div class="stat-mini-value alpa"><?= $totalAlpa ?></div>
                <div class="stat-mini-label">Alpa</div>
            </div>
        </div>
    </div>

    <div class="bento-full animate-fade-in-up animate-delay-2">
        <div class="persen-ring">
            <div class="ring-chart">
                <svg viewBox="0 0 120 120">
                    <circle class="ring-bg" cx="60" cy="60" r="52"></circle>
                    <circle class="ring-fill" cx="60" cy="60" r="52" stroke-dasharray="326.73" stroke-dashoffset="<?= 326.73 - (326.73 * $persentase / 100) ?>"></circle>
                </svg>
                <div class="ring-text"><?= $persentase ?>%</div>
            </div>
            <div class="ring-info">
                <h4>Kehadiran</h4>
                <p><?= $totalHadir ?> dari <?= $totalHari ?> hari</p>
            </div>
        </div>
    </div>

    <div class="bento-full">
        <div class="recent-timeline animate-fade-in-up animate-delay-3">
            <div class="timeline-header">
                <h3>Timeline Absensi</h3>
                <span style="font-size:12px;color:var(--text-muted);"><?= count($filteredRiwayat) ?> catatan</span>
            </div>
            <div class="timeline-list">
                <?php foreach ($filteredRiwayat as $r):
                    $statusDatang = $r['status_datang'] == 'approved' ? 'Disetujui' : ($r['status_datang'] == 'rejected' ? 'Ditolak' : 'Menunggu');
                    $statusPulang = $r['status_pulang'] ? ($r['status_pulang'] == 'approved' ? 'Disetujui' : ($r['status_pulang'] == 'rejected' ? 'Ditolak' : 'Menunggu')) : null;
                    $fotoSrc = $r['foto_datang'] ? BASE_URL . '/uploads/foto/' . $r['foto_datang'] : null;
                ?>
                <div class="timeline-item">
                    <div class="timeline-dot <?= $r['status_datang']=='approved'?'dot-success':($r['status_datang']=='rejected'?'dot-danger':'dot-warning') ?>"></div>
                    <div class="timeline-content">
                        <div class="timeline-top">
                            <div>
                                <div class="timeline-type">Absen Datang</div>
                                <div class="timeline-date"><?= date('d F Y', strtotime($r['tanggal'])) ?></div>
                            </div>
                            <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                <?php if ($r['status_datang']): ?>
                                <span class="badge bg-<?= $r['status_datang']=='approved'?'success':($r['status_datang']=='rejected'?'danger':'warning') ?>" style="font-size:11px;padding:3px 10px;">Datang: <?= $statusDatang ?></span>
                                <?php endif; ?>
                                <?php if ($statusPulang): ?>
                                <span class="badge bg-<?= $r['status_pulang']=='approved'?'success':($r['status_pulang']=='rejected'?'danger':'warning') ?>" style="font-size:11px;padding:3px 10px;">Pulang: <?= $statusPulang ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="timeline-meta">
                            <span><i class="bi bi-clock"></i> Datang: <?= date('H:i', strtotime($r['jam_datang'])) ?></span>
                            <?php if ($r['jam_pulang']): ?>
                            <span><i class="bi bi-clock"></i> Pulang: <?= date('H:i', strtotime($r['jam_pulang'])) ?></span>
                            <?php endif; ?>
                            <?php if ($r['lat_datang']): ?>
                            <span><i class="bi bi-geo"></i> <?= round(hitungJarak($r['lat_datang'], $r['lng_datang'], $siswa['latitude'], $siswa['longitude'])) ?> m</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if ($fotoSrc): ?>
                    <div class="timeline-thumb" style="cursor:pointer;" onclick="window.location='<?= BASE_URL ?>/index.php?page=siswa-detail&id=<?= $r['id'] ?>'">
                        <img src="<?= $fotoSrc ?>" alt="selfie">
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
                <?php if (empty($filteredRiwayat)): ?>
                <p style="text-align:center;color:var(--text-muted);padding:40px 0;margin:0;">Belum ada data absensi untuk periode ini</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
