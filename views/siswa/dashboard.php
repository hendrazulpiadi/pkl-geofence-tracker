<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4><i class="bi bi-speedometer2"></i> Dashboard Siswa</h4>
            </div>

            <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                Absen berhasil dikirim! Menunggu persetujuan pembimbing.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <i class="bi bi-person-circle fs-1"></i>
                            <h5><?= htmlspecialchars($siswa['nama_lengkap']) ?></h5>
                            <p class="mb-0">NISN: <?= htmlspecialchars($siswa['nisn']) ?> | Kelas: <?= htmlspecialchars($siswa['kelas']) ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5><i class="bi bi-building"></i> Tempat PKL</h5>
                            <p class="mb-1"><strong><?= htmlspecialchars($siswa['nama_pt'] ?? '-') ?></strong></p>
                            <small><?= htmlspecialchars($siswa['alamat'] ?? '') ?></small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5><i class="bi bi-person-check"></i> Status Hari Ini</h5>
                            <?php if ($absenHariIni): ?>
                                <p class="mb-1">Datang: <span class="badge bg-<?= $absenHariIni['status_datang']=='approved'?'success':($absenHariIni['status_datang']=='rejected'?'danger':'warning') ?>"><?= $absenHariIni['status_datang'] ?></span></p>
                                <?php if ($absenHariIni['status_datang'] == 'rejected'): ?>
                                <p class="mb-0 text-warning">Absen ditolak, silakan absen ulang</p>
                                <?php endif; ?>
                                <p class="mb-0">Pulang: <span class="badge bg-<?= $absenHariIni['status_pulang']=='approved'?'success':($absenHariIni['status_pulang']=='rejected'?'danger':'warning') ?>"><?= $absenHariIni['status_pulang'] ?: 'Belum absen' ?></span></p>
                                <?php if ($absenHariIni['status_pulang'] == 'rejected'): ?>
                                <p class="mb-0 text-warning">Absen pulang ditolak, silakan absen ulang</p>
                                <?php endif; ?>
                            <?php else: ?>
                                <p class="mb-0">Belum absen hari ini</p>
                            <?php endif; ?>
                            <a href="index.php?page=siswa-absen" class="btn btn-light btn-sm mt-2">Absen Sekarang</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header"><h5>Riwayat Absensi (5 Terakhir)</h5></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr><th>Tanggal</th><th>Jam Datang</th><th>Status Datang</th><th>Jam Pulang</th><th>Status Pulang</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($riwayat as $r): ?>
                                <tr>
                                    <td><?= $r['tanggal'] ?></td>
                                    <td><?= date('H:i:s', strtotime($r['jam_datang'])) ?></td>
                                    <td><span class="badge bg-<?= $r['status_datang']=='approved'?'success':($r['status_datang']=='rejected'?'danger':'warning') ?>"><?= $r['status_datang'] ?></span></td>
                                    <td><?= $r['jam_pulang'] ? date('H:i:s', strtotime($r['jam_pulang'])) : '-' ?></td>
                                    <td><span class="badge bg-<?= $r['status_pulang']=='approved'?'success':($r['status_pulang']=='rejected'?'danger':'warning') ?>"><?= $r['status_pulang'] ?: '-' ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($riwayat)): ?><tr><td colspan="5" class="text-center">Belum ada riwayat</td></tr><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
