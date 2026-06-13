<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4><i class="bi bi-speedometer2"></i> Dashboard <?= $_SESSION['jenis_pembimbing'] == 'sekolah' ? 'Pembimbing Sekolah' : 'Pembimbing PT' ?></h4>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h1><i class="bi bi-people"></i></h1>
                            <h3><?= count($siswaBimbingan) ?></h3>
                            <p class="mb-0"><?= $_SESSION['jenis_pembimbing'] == 'sekolah' ? 'Siswa Bimbingan' : 'Siswa di PT' ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h1><i class="bi bi-clock-history"></i></h1>
                            <h3><?= count($pendingAbsensi) ?></h3>
                            <p class="mb-0">Pending Absensi</p>
                        </div>
                        <div class="card-footer bg-transparent border-0 text-center">
                            <a href="<?= BASE_URL ?>/index.php?page=pembimbing-approval" class="text-white">Review <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h1><i class="bi bi-file-earmark-text"></i></h1>
                            <h3><?= count($pendingIzin) ?></h3>
                            <p class="mb-0">Pending Izin</p>
                        </div>
                        <div class="card-footer bg-transparent border-0 text-center">
                            <a href="<?= BASE_URL ?>/index.php?page=pembimbing-approval" class="text-white">Review <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header"><h5><?= $_SESSION['jenis_pembimbing'] == 'sekolah' ? 'Siswa Bimbingan' : 'Siswa PKL di Perusahaan' ?></h5></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>NISN</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>PT</th>
                                    <th>Datang</th>
                                    <th>Pulang</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($siswaBimbingan as $s): ?>
                                <tr>
                                    <td><?= htmlspecialchars($s['nisn']) ?></td>
                                    <td><?= htmlspecialchars($s['nama_lengkap']) ?></td>
                                    <td><?= htmlspecialchars($s['kelas']) ?></td>
                                    <td><?= htmlspecialchars($s['nama_pt'] ?? '-') ?></td>
                                    <td>
                                        <?php if ($s['absen_id'] && $s['jam_datang']): ?>
                                            <span class="badge bg-<?= $s['status_datang']=='approved'?'success':($s['status_datang']=='rejected'?'danger':'warning') ?>">
                                                <?= date('H:i', strtotime($s['jam_datang'])) ?>
                                            </span>
                                            <small class="d-block"><?= $s['status_datang'] ?></small>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($s['absen_id'] && $s['jam_pulang']): ?>
                                            <span class="badge bg-<?= $s['status_pulang']=='approved'?'success':($s['status_pulang']=='rejected'?'danger':'warning') ?>">
                                                <?= date('H:i', strtotime($s['jam_pulang'])) ?>
                                            </span>
                                            <small class="d-block"><?= $s['status_pulang'] ?></small>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($siswaBimbingan)): ?><tr><td colspan="6" class="text-center">Belum ada siswa</td></tr><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
