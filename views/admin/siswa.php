<div class="container-fluid">
    <?php displayFlash('siswa'); ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="bi bi-people"></i> Data Siswa</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalSiswa"><i class="bi bi-plus"></i> Tambah Siswa</button>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>NISN</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Username</th>
                            <th>PT</th>
                            <th>Pemb. Sekolah</th>
                            <th>Pemb. PT</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach ($siswa as $s): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($s['nisn']) ?></td>
                            <td><?= htmlspecialchars($s['nama_lengkap']) ?></td>
                            <td><?= htmlspecialchars($s['kelas']) ?></td>
                            <td><?= htmlspecialchars($s['username']) ?></td>
                            <td><?= htmlspecialchars($s['nama_pt'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($s['pembimbing_sekolah'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($s['pembimbing_pt'] ?? '-') ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="editSiswa(<?= htmlspecialchars(json_encode($s)) ?>)"><i class="bi bi-pencil"></i></button>
                                <form method="POST" style="display:inline" onsubmit="return confirm('Yakin hapus?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $s['id'] ?>">
                                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($siswa)): ?><tr><td colspan="9" class="text-center">Belum ada data</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSiswa" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Tambah/Edit Siswa</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <input type="hidden" name="action" id="action_siswa" value="create">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" id="edit_nama" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NISN</label>
                            <input type="text" name="nisn" id="edit_nisn" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kelas</label>
                            <input type="text" name="kelas" id="edit_kelas" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" id="edit_username" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" id="edit_password" class="form-control" placeholder="Kosongkan jika tidak ganti">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Perusahaan PT</label>
                            <select name="perusahaan_id" id="edit_perusahaan" class="form-control">
                                <option value="">- Pilih -</option>
                                <?php foreach ($perusahaan as $p): ?>
                                <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nama_pt']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pembimbing Sekolah</label>
                            <select name="pembimbing_sekolah_id" id="edit_pemb_sekolah" class="form-control">
                                <option value="">- Pilih -</option>
                                <?php foreach ($pembimbingSekolah as $pb): if ($pb['jenis'] == 'sekolah'): ?>
                                <option value="<?= $pb['id'] ?>"><?= htmlspecialchars($pb['nama_lengkap']) ?></option>
                                <?php endif; endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pembimbing PT</label>
                            <select name="pembimbing_pt_id" id="edit_pemb_pt" class="form-control">
                                <option value="">- Pilih -</option>
                                <?php foreach ($pembimbingPt as $pb): ?>
                                <option value="<?= $pb['id'] ?>"><?= htmlspecialchars($pb['nama_lengkap']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
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
function editSiswa(data) {
    document.getElementById('action_siswa').value = 'update';
    document.getElementById('edit_id').value = data.id;
    document.getElementById('edit_nama').value = data.nama_lengkap;
    document.getElementById('edit_nisn').value = data.nisn;
    document.getElementById('edit_kelas').value = data.kelas;
    document.getElementById('edit_username').value = data.username;
    document.getElementById('edit_perusahaan').value = data.perusahaan_id || '';
    document.getElementById('edit_pemb_sekolah').value = data.pembimbing_sekolah_id || '';
    document.getElementById('edit_pemb_pt').value = data.pembimbing_pt_id || '';
    document.getElementById('edit_password').required = false;
    new bootstrap.Modal(document.getElementById('modalSiswa')).show();
}
</script>
