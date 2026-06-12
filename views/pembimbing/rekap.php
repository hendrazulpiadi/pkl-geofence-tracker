<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4><i class="bi bi-file-text"></i> Rekap Absensi</h4>
                <div class="d-flex gap-2 align-items-center">
                    <form method="GET" class="d-flex gap-2">
                        <input type="hidden" name="page" value="pembimbing-rekap">
                        <select name="bulan" class="form-control form-control-sm" style="width:auto">
                            <?php for ($m=1; $m<=12; $m++): ?>
                            <option value="<?= str_pad($m,2,'0',STR_PAD_LEFT) ?>" <?= $bulan==str_pad($m,2,'0',STR_PAD_LEFT)?'selected':'' ?>><?= date('F', mktime(0,0,0,$m,1)) ?></option>
                            <?php endfor; ?>
                        </select>
                        <select name="tahun" class="form-control form-control-sm" style="width:auto">
                            <?php for ($y=date('Y'); $y>=2020; $y--): ?>
                            <option value="<?= $y ?>" <?= $tahun==$y?'selected':'' ?>><?= $y ?></option>
                            <?php endfor; ?>
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                    </form>
                    <div class="dropdown">
                        <button class="btn btn-success btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-printer"></i> Cetak PDF
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?page=pembimbing-rekap-pdf&mode=bulan&bulan=<?= $bulan ?>&tahun=<?= $tahun ?>">Per Bulan (<?= date('F', mktime(0,0,0,(int)$bulan,1)) ?>)</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><h6 class="dropdown-header">Per Minggu</h6></li>
                            <?php
                            $minggu = 1;
                            $maxMinggu = date('W', mktime(0,0,0,12,31,(int)$tahun));
                            for ($w = 1; $w <= $maxMinggu; $w++):
                            ?>
                            <li><a class="dropdown-item" href="?page=pembimbing-rekap-pdf&mode=minggu&minggu_ke=<?= $w ?>&tahun=<?= $tahun ?>">Minggu ke-<?= $w ?></a></li>
                            <?php endfor; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-success">
                                <tr><th>#</th><th>Tanggal</th><th>NISN</th><th>Nama</th><th>PT</th><th>Jam Datang</th><th>Status</th><th>Jam Pulang</th><th>Status</th></tr>
                            </thead>
                            <tbody>
                                <?php $no=1; foreach ($filteredRekap as $r): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $r['tanggal'] ?></td>
                                    <td><?= htmlspecialchars($r['nisn']) ?></td>
                                    <td><?= htmlspecialchars($r['nama_lengkap']) ?></td>
                                    <td><?= htmlspecialchars($r['nama_pt'] ?? '-') ?></td>
                                    <td><?= $r['jam_datang'] ? date('H:i:s', strtotime($r['jam_datang'])) : '-' ?></td>
                                    <td><span class="badge bg-<?= $r['status_datang']=='approved'?'success':($r['status_datang']=='rejected'?'danger':'warning') ?>"><?= $r['status_datang'] ?></span></td>
                                    <td><?= $r['jam_pulang'] ? date('H:i:s', strtotime($r['jam_pulang'])) : '-' ?></td>
                                    <td><span class="badge bg-<?= $r['status_pulang']=='approved'?'success':($r['status_pulang']=='rejected'?'danger':'warning') ?>"><?= $r['status_pulang'] ?: '-' ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($filteredRekap)): ?><tr><td colspan="9" class="text-center">Tidak ada data untuk periode ini</td></tr><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
