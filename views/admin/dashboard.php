<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4><i class="bi bi-speedometer2"></i> Dashboard Admin</h4>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h1><i class="bi bi-people"></i></h1>
                            <h3><?= $totalSiswa ?></h3>
                            <p class="mb-0">Total Siswa</p>
                        </div>
                        <div class="card-footer bg-transparent border-0 text-center">
                            <a href="index.php?page=admin-siswa" class="text-white">Kelola <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h1><i class="bi bi-person-badge"></i></h1>
                            <h3><?= $totalPembimbing ?></h3>
                            <p class="mb-0">Total Pembimbing</p>
                        </div>
                        <div class="card-footer bg-transparent border-0 text-center">
                            <a href="index.php?page=admin-pembimbing" class="text-white">Kelola <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h1><i class="bi bi-building"></i></h1>
                            <h3><?= $totalPerusahaan ?></h3>
                            <p class="mb-0">Total Perusahaan</p>
                        </div>
                        <div class="card-footer bg-transparent border-0 text-center">
                            <a href="index.php?page=admin-perusahaan" class="text-white">Kelola <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
