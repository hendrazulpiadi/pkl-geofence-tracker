<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4><i class="bi bi-file-earmark-text"></i> Pengajuan Izin / Sakit</h4>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalIzin"><i class="bi bi-plus"></i> Ajukan</button>
            </div>

            <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">Pengajuan berhasil dikirim, menunggu approval pembimbing.</div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr><th>#</th><th>Jenis</th><th>Tanggal</th><th>Keterangan</th><th>Status</th></tr>
                            </thead>
                            <tbody>
                                <?php $no=1; foreach ($riwayatIzin as $i): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><span class="badge bg-<?= $i['jenis']=='sakit'?'danger':'info' ?>"><?= $i['jenis'] ?></span></td>
                                    <td><?= $i['tgl_mulai'] ?> s/d <?= $i['tgl_selesai'] ?></td>
                                    <td><?= htmlspecialchars($i['keterangan'] ?? '-') ?></td>
                                    <td><span class="badge bg-<?= $i['status_approval']=='approved'?'success':($i['status_approval']=='rejected'?'danger':'warning') ?>"><?= $i['status_approval'] ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($riwayatIzin)): ?><tr><td colspan="5" class="text-center">Belum ada pengajuan</td></tr><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalIzin" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header"><h5>Form Pengajuan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Jenis</label>
                        <select name="jenis" class="form-control" required>
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tgl_mulai" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tgl_selesai" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bukti Foto (surat dokter/izin)</label>
                        <input type="file" name="bukti_foto" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Ajukan</button>
                </div>
            </div>
        </form>
    </div>
</div>
