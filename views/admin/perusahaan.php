<div class="container-fluid">
    <?php displayFlash('perusahaan'); ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="bi bi-building"></i> Data Perusahaan PT</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPerusahaan"><i class="bi bi-plus"></i> Tambah PT</button>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-primary">
                        <tr><th>#</th><th>Nama PT</th><th>Alamat</th><th>Latitude</th><th>Longitude</th><th>Radius (m)</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach ($perusahaan as $p): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($p['nama_pt']) ?></td>
                            <td><?= htmlspecialchars($p['alamat'] ?? '-') ?></td>
                            <td><?= $p['latitude'] ?></td>
                            <td><?= $p['longitude'] ?></td>
                            <td><?= $p['radius_toleransi'] ?> m</td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="editPerusahaan(<?= htmlspecialchars(json_encode($p)) ?>)"><i class="bi bi-pencil"></i></button>
                                <form method="POST" style="display:inline" onsubmit="return confirm('Yakin hapus?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($perusahaan)): ?><tr><td colspan="7" class="text-center">Belum ada data</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPerusahaan" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST">
            <div class="modal-content">
                <div class="modal-header"><h5>Tambah/Edit Perusahaan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <input type="hidden" name="action" id="action_pt" value="create">
                    <input type="hidden" name="id" id="pt_edit_id">
                    <div class="mb-3">
                        <label class="form-label">Nama PT</label>
                        <input type="text" name="nama_pt" id="pt_nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" id="pt_alamat" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Latitude</label>
                            <input type="text" name="latitude" id="pt_lat" class="form-control" placeholder="-6.2088">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Longitude</label>
                            <input type="text" name="longitude" id="pt_lng" class="form-control" placeholder="106.8456">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Radius Toleransi (meter)</label>
                        <input type="number" name="radius_toleransi" id="pt_radius" class="form-control" value="50" min="1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function editPerusahaan(data) {
    document.getElementById('action_pt').value = 'update';
    document.getElementById('pt_edit_id').value = data.id;
    document.getElementById('pt_nama').value = data.nama_pt;
    document.getElementById('pt_alamat').value = data.alamat || '';
    document.getElementById('pt_lat').value = data.latitude;
    document.getElementById('pt_lng').value = data.longitude;
    document.getElementById('pt_radius').value = data.radius_toleransi;
    new bootstrap.Modal(document.getElementById('modalPerusahaan')).show();
}
</script>
