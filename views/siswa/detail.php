<div class="bento-grid">
    <div class="bento-full animate-fade-in-up">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px;">
            <a href="<?= BASE_URL ?>/index.php?page=siswa-riwayat" style="text-decoration:none;color:var(--text-muted);font-size:20px;"><i class="bi bi-arrow-left"></i></a>
            <h3 style="margin:0;font-size:20px;font-weight:700;">Detail Absensi</h3>
        </div>
    </div>

    <div class="bento-full">
        <div class="detail-grid">
            <div class="profil-card animate-fade-in-up animate-delay-1">
                <div class="card-title"><i class="bi bi-camera"></i> Foto Absensi</div>
                <div class="detail-photo">
                    <?php if ($absensi['foto_datang']): ?>
                    <img src="<?= BASE_URL ?>/uploads/foto/<?= $absensi['foto_datang'] ?>" alt="Foto Absensi">
                    <?php else: ?>
                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:var(--text-muted);font-size:14px;">Tidak ada foto</div>
                    <?php endif; ?>
                </div>

                <div style="margin-top:16px;">
                    <div class="info-row">
                        <span class="info-label">Tanggal</span>
                        <span class="info-value"><?= date('d F Y', strtotime($absensi['tanggal'])) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Jam Datang</span>
                        <span class="info-value"><?= date('H:i:s', strtotime($absensi['jam_datang'])) ?></span>
                    </div>
                    <?php if ($absensi['jam_pulang']): ?>
                    <div class="info-row">
                        <span class="info-label">Jam Pulang</span>
                        <span class="info-value"><?= date('H:i:s', strtotime($absensi['jam_pulang'])) ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="info-row">
                        <span class="info-label">Status Datang</span>
                        <span class="info-value">
                            <span class="badge bg-<?= $absensi['status_datang']=='approved'?'success':($absensi['status_datang']=='rejected'?'danger':'warning') ?>">
                                <?= $absensi['status_datang'] == 'approved' ? 'Disetujui' : ($absensi['status_datang'] == 'rejected' ? 'Ditolak' : 'Menunggu') ?>
                            </span>
                        </span>
                    </div>
                    <?php if ($absensi['status_pulang']): ?>
                    <div class="info-row">
                        <span class="info-label">Status Pulang</span>
                        <span class="info-value">
                            <span class="badge bg-<?= $absensi['status_pulang']=='approved'?'success':($absensi['status_pulang']=='rejected'?'danger':'warning') ?>">
                                <?= $absensi['status_pulang'] == 'approved' ? 'Disetujui' : ($absensi['status_pulang'] == 'rejected' ? 'Ditolak' : 'Menunggu') ?>
                            </span>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div>
                <div class="profil-card animate-fade-in-up animate-delay-2" style="margin-bottom:20px;">
                    <div class="card-title"><i class="bi bi-geo-alt"></i> Lokasi Absensi</div>
                    <div class="info-row">
                        <span class="info-label">Latitude</span>
                        <span class="info-value"><?= $absensi['lat_datang'] ?? '-' ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Longitude</span>
                        <span class="info-value"><?= $absensi['lng_datang'] ?? '-' ?></span>
                    </div>
                    <?php if ($absensi['latitude'] && $absensi['lat_datang']): ?>
                    <div class="info-row">
                        <span class="info-label">Jarak dari PT</span>
                        <span class="info-value"><?= round(hitungJarak($absensi['lat_datang'], $absensi['lng_datang'], $absensi['latitude'], $absensi['longitude'])) ?> m</span>
                    </div>
                    <?php endif; ?>
                    <?php if ($absensi['lat_datang']): ?>
                    <div class="detail-map" id="detailMap">
                        <iframe width="100%" height="100%" src="https://www.openstreetmap.org/export/embed.html?bbox=<?= $absensi['lng_datang']-0.003 ?>,<?= $absensi['lat_datang']-0.003 ?>,<?= $absensi['lng_datang']+0.003 ?>,<?= $absensi['lat_datang']+0.003 ?>&layer=mapnik&marker=<?= $absensi['lat_datang'] ?>,<?= $absensi['lng_datang'] ?>" style="border:none;"></iframe>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="profil-card animate-fade-in-up animate-delay-3">
                    <div class="card-title"><i class="bi bi-clock-history"></i> Timeline Approval</div>
                    <div class="approval-timeline-detail">
                        <div class="approval-step">
                            <div class="step-dot done"></div>
                            <div class="step-title">Absen Dikirim</div>
                            <div class="step-date"><?= date('d F Y H:i', strtotime($absensi['jam_datang'])) ?></div>
                        </div>
                        <div class="approval-step">
                            <div class="step-dot <?= $absensi['status_datang'] == 'pending' ? 'active' : ($absensi['status_datang'] == 'approved' ? 'done' : ($absensi['status_datang'] == 'rejected' ? 'rejected' : '')) ?>"></div>
                            <div class="step-title">Review Pembimbing</div>
                            <div class="step-date">
                                <?php if ($absensi['status_datang'] == 'pending'): ?>
                                    Menunggu review
                                <?php elseif ($absensi['status_datang'] == 'approved'): ?>
                                    Disetujui
                                <?php elseif ($absensi['status_datang'] == 'rejected'): ?>
                                    Ditolak
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="approval-step">
                            <div class="step-dot <?= $absensi['status_datang'] == 'approved' ? 'done' : '' ?>"></div>
                            <div class="step-title">Selesai</div>
                            <div class="step-date">
                                <?php if ($absensi['status_datang'] == 'approved'): ?>
                                    Absensi telah disetujui
                                <?php elseif ($absensi['status_datang'] == 'rejected'): ?>
                                    Silakan absen ulang
                                <?php else: ?>
                                    Menunggu proses
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
