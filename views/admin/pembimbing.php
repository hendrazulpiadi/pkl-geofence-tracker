<div class="container-fluid">
    <?php displayFlash('pembimbing'); ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="bi bi-person-badge"></i> Data Pembimbing</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPembimbing"><i class="bi bi-plus"></i> Tambah Pembimbing</button>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-primary">
                        <tr><th>#</th><th>Nama</th><th>NIP/NIK</th><th>Jenis</th><th>Username</th><th>PT</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach ($pembimbing as $p): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($p['nama_lengkap']) ?></td>
                            <td><?= htmlspecialchars($p['nip_nik'] ?? '-') ?></td>
                            <td><span class="badge bg-<?= $p['jenis']=='sekolah'?'success':'info' ?>"><?= $p['jenis'] ?></span></td>
                            <td><?= htmlspecialchars($p['username']) ?></td>
                            <td><?= htmlspecialchars($p['nama_pt'] ?? '-') ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="editPembimbing(<?= htmlspecialchars(json_encode($p)) ?>)"><i class="bi bi-pencil"></i></button>
                                <form method="POST" style="display:inline" onsubmit="return confirm('Yakin hapus?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($pembimbing)): ?><tr><td colspan="7" class="text-center">Belum ada data</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPembimbing" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST">
            <div class="modal-content">
                <div class="modal-header"><h5>Tambah/Edit Pembimbing</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <input type="hidden" name="action" id="action_pb" value="create">
                    <input type="hidden" name="id" id="pb_edit_id">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" id="pb_nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">NIP/NIK</label>
                        <input type="text" name="nip_nik" id="pb_nip" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" id="pb_username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" id="pb_password" class="form-control" placeholder="Kosongkan jika tidak ganti">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis</label>
                        <select name="jenis" id="pb_jenis" class="form-control" required>
                            <option value="sekolah">Pembimbing Sekolah</option>
                            <option value="pt">Pembimbing PT</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Perusahaan PT</label>
                        <select name="perusahaan_id" id="pb_perusahaan" class="form-control">
                            <option value="">- Pilih -</option>
                            <?php foreach ($perusahaan as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nama_pt']) ?></option>
                            <?php endforeach; ?>
                        </select>
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
function editPembimbing(data) {
    document.getElementById('action_pb').value = 'update';
    document.getElementById('pb_edit_id').value = data.id;
    document.getElementById('pb_nama').value = data.nama_lengkap;
    document.getElementById('pb_nip').value = data.nip_nik || '';
    document.getElementById('pb_username').value = data.username;
    document.getElementById('pb_password').required = false;
    document.getElementById('pb_jenis').value = data.jenis;
    document.getElementById('pb_perusahaan').value = data.perusahaan_id || '';
    new bootstrap.Modal(document.getElementById('modalPembimbing')).show();
}
</script>
