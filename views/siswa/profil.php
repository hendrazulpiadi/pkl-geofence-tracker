<div class="bento-grid">
    <div class="bento-full animate-fade-in-up">
        <div class="profil-hero">
            <div class="profil-avatar"><?= strtoupper(substr($siswa['nama_lengkap'], 0, 1)) ?></div>
            <div class="profil-info">
                <h2><?= htmlspecialchars($siswa['nama_lengkap']) ?></h2>
                <p>NISN: <?= htmlspecialchars($siswa['nisn'] ?? '-') ?> | Kelas: <?= htmlspecialchars($siswa['kelas'] ?? '-') ?></p>
            </div>
        </div>
    </div>

    <div class="bento-full">
        <div class="profil-grid">
            <div class="profil-card animate-fade-in-up animate-delay-1">
                <div class="card-title"><i class="bi bi-building"></i> Informasi PKL</div>
                <div class="info-row">
                    <span class="info-label">Perusahaan</span>
                    <span class="info-value"><?= htmlspecialchars($siswa['nama_pt'] ?? '-') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Alamat</span>
                    <span class="info-value" style="max-width:200px;text-align:right;"><?= htmlspecialchars($siswa['alamat'] ?? '-') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Koordinat</span>
                    <span class="info-value"><?= $siswa['latitude'] ? $siswa['latitude'] . ', ' . $siswa['longitude'] : '-' ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Radius Absen</span>
                    <span class="info-value"><?= $siswa['radius_toleransi'] ?? 50 ?> meter</span>
                </div>
            </div>

            <div class="profil-card animate-fade-in-up animate-delay-2">
                <div class="card-title"><i class="bi bi-people"></i> Pembimbing</div>
                <div class="info-row">
                    <span class="info-label">Pembimbing Sekolah</span>
                    <span class="info-value"><?= htmlspecialchars($pembimbingSekolah['nama_lengkap'] ?? '-') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Pembimbing PT</span>
                    <span class="info-value"><?= htmlspecialchars($pembimbingPt['nama_lengkap'] ?? '-') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">NIP/NIK</span>
                    <span class="info-value"><?= htmlspecialchars($pembimbingSekolah['nip_nik'] ?? $pembimbingPt['nip_nik'] ?? '-') ?></span>
                </div>
            </div>

            <div class="profil-card animate-fade-in-up animate-delay-3">
                <div class="card-title"><i class="bi bi-graph-up"></i> Ringkasan Kehadiran</div>
                <div style="display:flex;align-items:center;gap:24px;margin-bottom:16px;">
                    <div class="ring-chart" style="width:80px;height:80px;">
                        <svg viewBox="0 0 120 120">
                            <circle class="ring-bg" cx="60" cy="60" r="52"></circle>
                            <circle class="ring-fill" cx="60" cy="60" r="52" stroke-dasharray="326.73" stroke-dashoffset="<?= 326.73 - (326.73 * $persentase / 100) ?>" style="stroke:<?= $persentase >= 75 ? 'var(--success)' : ($persentase >= 50 ? 'var(--warning)' : 'var(--danger)') ?>;"></circle>
                        </svg>
                        <div class="ring-text" style="font-size:18px;"><?= $persentase ?>%</div>
                    </div>
                    <div>
                        <div style="font-size:13px;margin-bottom:4px;"><span style="color:var(--success);font-weight:600;"><?= $totalHadir ?></span> Hadir</div>
                        <div style="font-size:13px;margin-bottom:4px;"><span style="color:var(--warning);font-weight:600;"><?= $totalIzin ?></span> Izin</div>
                        <div style="font-size:13px;"><span style="color:var(--danger);font-weight:600;"><?= $totalAlpa ?></span> Alpa</div>
                    </div>
                </div>
                <div style="width:100%;height:8px;border-radius:100px;background:var(--bg);overflow:hidden;">
                    <div style="height:100%;width:<?= $persentase ?>%;border-radius:100px;background:<?= $persentase >= 75 ? 'var(--success)' : ($persentase >= 50 ? 'var(--warning)' : 'var(--danger)') ?>;transition:width 0.8s ease;"></div>
                </div>
            </div>

            <div class="profil-card animate-fade-in-up animate-delay-4">
                <div class="card-title"><i class="bi bi-gear"></i> Pengaturan</div>
                <div style="display:flex;flex-direction:column;gap:12px;">
                    <a href="#" style="display:flex;align-items:center;gap:12px;padding:12px;border-radius:var(--radius-sm);background:var(--bg);text-decoration:none;color:var(--text-primary);font-weight:500;font-size:14px;">
                        <i class="bi bi-key" style="font-size:18px;"></i> Ubah Password
                    </a>
                    <a href="#" style="display:flex;align-items:center;gap:12px;padding:12px;border-radius:var(--radius-sm);background:var(--bg);text-decoration:none;color:var(--text-primary);font-weight:500;font-size:14px;">
                        <i class="bi bi-bell" style="font-size:18px;"></i> Pengaturan Notifikasi
                    </a>
                    <div style="display:flex;align-items:center;gap:12px;padding:12px;border-radius:var(--radius-sm);background:var(--bg);font-weight:500;font-size:14px;">
                        <i class="bi bi-moon-stars" style="font-size:18px;"></i> Mode Gelap
                        <label class="switch" style="margin-left:auto;">
                            <input type="checkbox" id="darkModeToggle">
                            <span class="switch-slider"></span>
                        </label>
                    </div>
                    <a href="<?= BASE_URL ?>/index.php?page=logout" style="display:flex;align-items:center;gap:12px;padding:12px;border-radius:var(--radius-sm);background:var(--danger-light);text-decoration:none;color:var(--danger);font-weight:600;font-size:14px;">
                        <i class="bi bi-box-arrow-right" style="font-size:18px;"></i> Keluar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.switch {
    position: relative;
    display: inline-block;
    width: 44px;
    height: 24px;
}
.switch input { opacity: 0; width: 0; height: 0; }
.switch-slider {
    position: absolute;
    cursor: pointer;
    inset: 0;
    background: var(--border);
    border-radius: 24px;
    transition: 0.3s;
}
.switch-slider::before {
    content: '';
    position: absolute;
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background: #fff;
    border-radius: 50%;
    transition: 0.3s;
}
.switch input:checked + .switch-slider { background: var(--primary); }
.switch input:checked + .switch-slider::before { transform: translateX(20px); }
</style>
