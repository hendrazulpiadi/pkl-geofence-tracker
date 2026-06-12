<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4><i class="bi bi-check-circle"></i> Approval Absensi & Izin</h4>
            </div>

            <ul class="nav nav-tabs mb-3" id="approvalTab">
                <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#absen">Absensi Pending</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#izin">Izin Pending</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="absen">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-warning">
                                        <tr><th>#</th><th>Nama</th><th>NISN</th><th>Tanggal</th><th>Tipe</th><th>Foto</th><th>Aksi</th></tr>
                                    </thead>
                                    <tbody>
                                        <?php $no=1; foreach ($pendingAbsensi as $a): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= htmlspecialchars($a['nama_lengkap']) ?></td>
                                            <td><?= htmlspecialchars($a['nisn']) ?></td>
                                            <td><?= $a['tanggal'] ?></td>
                                            <td>
                                                <?php if ($a['status_datang'] == 'pending'): ?>
                                                <span class="badge bg-info">Datang</span>
                                                <?php endif; ?>
                                                <?php if ($a['status_pulang'] == 'pending'): ?>
                                                <span class="badge bg-secondary">Pulang</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($a['foto_datang']): ?>
                                                <a href="uploads/foto/<?= $a['foto_datang'] ?>" target="_blank" class="btn btn-sm btn-outline-info">Lihat Foto Datang</a>
                                                <?php endif; ?>
                                                <?php if ($a['foto_pulang']): ?>
                                                <a href="uploads/foto/<?= $a['foto_pulang'] ?>" target="_blank" class="btn btn-sm btn-outline-info">Lihat Foto Pulang</a>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($a['status_datang'] == 'pending'): ?>
                                                <form method="POST" style="display:inline">
                                                    <input type="hidden" name="id" value="<?= $a['id'] ?>">
                                                    <input type="hidden" name="tipe" value="absen_datang">
                                                    <button type="submit" name="aksi" value="approve" class="btn btn-sm btn-success"><i class="bi bi-check"></i></button>
                                                    <button type="submit" name="aksi" value="reject" class="btn btn-sm btn-danger"><i class="bi bi-x"></i></button>
                                                </form>
                                                <?php endif; ?>
                                                <?php if ($a['status_pulang'] == 'pending'): ?>
                                                <form method="POST" style="display:inline">
                                                    <input type="hidden" name="id" value="<?= $a['id'] ?>">
                                                    <input type="hidden" name="tipe" value="absen_pulang">
                                                    <button type="submit" name="aksi" value="approve" class="btn btn-sm btn-success"><i class="bi bi-check"></i></button>
                                                    <button type="submit" name="aksi" value="reject" class="btn btn-sm btn-danger"><i class="bi bi-x"></i></button>
                                                </form>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <?php if (empty($pendingAbsensi)): ?><tr><td colspan="7" class="text-center">Tidak ada pending absensi</td></tr><?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="izin">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-info">
                                        <tr><th>#</th><th>Nama</th><th>Jenis</th><th>Tanggal</th><th>Keterangan</th><th>Bukti</th><th>Aksi</th></tr>
                                    </thead>
                                    <tbody>
                                        <?php $no=1; foreach ($pendingIzin as $i): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= htmlspecialchars($i['nama_lengkap']) ?></td>
                                            <td><span class="badge bg-<?= $i['jenis']=='sakit'?'danger':'info' ?>"><?= $i['jenis'] ?></span></td>
                                            <td><?= $i['tgl_mulai'] ?> s/d <?= $i['tgl_selesai'] ?></td>
                                            <td><?= htmlspecialchars($i['keterangan'] ?? '-') ?></td>
                                            <td>
                                                <?php if ($i['bukti_foto']): ?>
                                                <a href="uploads/foto/<?= $i['bukti_foto'] ?>" target="_blank" class="btn btn-sm btn-outline-info">Lihat</a>
                                                <?php else: ?>
                                                -
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <form method="POST" style="display:inline">
                                                    <input type="hidden" name="id" value="<?= $i['id'] ?>">
                                                    <input type="hidden" name="tipe" value="izin">
                                                    <button type="submit" name="aksi" value="approve" class="btn btn-sm btn-success"><i class="bi bi-check"></i></button>
                                                    <button type="submit" name="aksi" value="reject" class="btn btn-sm btn-danger"><i class="bi bi-x"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <?php if (empty($pendingIzin)): ?><tr><td colspan="7" class="text-center">Tidak ada pending izin</td></tr><?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
