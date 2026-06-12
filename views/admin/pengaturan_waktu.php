<div class="container-fluid">
    <?php displayFlash('waktu'); ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="bi bi-clock"></i> Pengaturan Waktu Absensi</h4>
    </div>
    <div class="card">
        <div class="card-body">
            <form method="GET" class="mb-4">
                <input type="hidden" name="page" value="admin-pengaturan-waktu">
                <div class="row g-2 align-items-end">
                    <div class="col-md-6">
                        <label class="form-label">Pilih Perusahaan PT</label>
                        <select name="perusahaan_id" class="form-control" onchange="this.form.submit()">
                            <option value="">- Pilih Perusahaan -</option>
                            <?php foreach ($perusahaan as $p): ?>
                            <option value="<?= $p['id'] ?>" <?= ($selectedPerusahaanId ?? '') == $p['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($p['nama_pt']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </form>

            <?php if ($selectedPerusahaanId): ?>
            <form method="POST">
                <input type="hidden" name="perusahaan_id" value="<?= $selectedPerusahaanId ?>">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jam Masuk (Mulai)</label>
                        <input type="time" name="jam_masuk_mulai" class="form-control" required
                               value="<?= $pengaturan['jam_masuk_mulai'] ?? '' ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jam Masuk (Selesai)</label>
                        <input type="time" name="jam_masuk_selesai" class="form-control" required
                               value="<?= $pengaturan['jam_masuk_selesai'] ?? '' ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jam Pulang (Mulai)</label>
                        <input type="time" name="jam_pulang_mulai" class="form-control" required
                               value="<?= $pengaturan['jam_pulang_mulai'] ?? '' ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jam Pulang (Selesai)</label>
                        <input type="time" name="jam_pulang_selesai" class="form-control" required
                               value="<?= $pengaturan['jam_pulang_selesai'] ?? '' ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>
