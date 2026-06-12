<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4><i class="bi bi-geo-alt"></i> Absensi</h4>
            </div>

            <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-info-circle"></i> Informasi</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>PT:</strong> <?= htmlspecialchars($siswa['nama_pt'] ?? '-') ?></p>
                            <p><strong>Alamat:</strong> <?= htmlspecialchars($siswa['alamat'] ?? '-') ?></p>
                            <p><strong>Radius:</strong> <?= $siswa['radius_toleransi'] ?? 50 ?> meter</p>
                            <hr>
                            <p><strong>Lokasi Anda:</strong></p>
                            <p id="lokasi_status" class="text-muted">Menunggu tombol absen diklik...</p>
                            <button id="btnCekLokasi" class="btn btn-sm btn-outline-secondary d-none" onclick="mulaiAbsen()"><i class="bi bi-geo-alt"></i> Cek Lokasi Ulang</button>
                            <div id="jarak_info" class="alert alert-info d-none"></div>
                            <hr>
                            <h6>Jadwal Absen:</h6>
                            <?php if ($jadwal): ?>
                            <p>Masuk: <?= $jadwal['jam_masuk_mulai'] ?> - <?= $jadwal['jam_masuk_selesai'] ?></p>
                            <p>Pulang: <?= $jadwal['jam_pulang_mulai'] ?> - <?= $jadwal['jam_pulang_selesai'] ?></p>
                            <?php else: ?>
                            <p class="text-muted">Belum diatur</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="bi bi-camera"></i> Selfie & Absen</h5>
                        </div>
                        <div class="card-body">
                            <div id="kamera_container">
                                <div class="border rounded bg-light text-center p-5">
                                    <i class="bi bi-camera fs-1 text-muted"></i>
                                    <p class="mt-2 text-muted">Klik tombol untuk membuka kamera</p>
                                </div>
                                <div class="mt-2">
                                    <button id="btnAmbilFoto" class="btn btn-primary w-100" disabled>
                                        <i class="bi bi-camera"></i> Ambil Foto
                                    </button>
                                </div>
                            </div>
                            <div id="foto_container" class="d-none">
                                <img id="foto_hasil" class="w-100 rounded">
                                <div class="mt-2">
                                    <button id="btnUlang" class="btn btn-warning">Ulang</button>
                                    <button id="btnKirim" class="btn btn-success">Kirim Absen</button>
                                </div>
                            </div>

                            <form id="formAbsen" method="POST" enctype="multipart/form-data" class="mt-3">
                                <input type="hidden" name="latitude" id="latitude" value="">
                                <input type="hidden" name="longitude" id="longitude" value="">
                                <input type="hidden" name="foto_data" id="foto_data">
                                <input type="hidden" name="tipe" id="tipe_absen" value="datang">
                                <input type="file" id="fileInput" accept="image/*" capture="user" class="d-none">
                                <div class="d-grid gap-2">
                                    <?php if (!$absenHariIni || $absenHariIni['status_datang'] == 'rejected'): ?>
                                    <?php if ($absenHariIni['status_datang'] == 'rejected'): ?>
                                    <div class="alert alert-danger">Absen datang Anda ditolak. Silakan absen ulang.</div>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-success btn-lg" onclick="setTipe('datang')" id="btnDatang" <?= $jadwal && !$bolehDatang ? 'disabled' : '' ?>>
                                        <i class="bi bi-box-arrow-in-right"></i> Absen Datang
                                    </button>
                                    <?php if ($jadwal && !$bolehDatang): ?>
                                    <small class="text-muted">Jadwal masuk: <?= $jadwal['jam_masuk_mulai'] ?> - <?= $jadwal['jam_masuk_selesai'] ?></small>
                                    <?php endif; ?>
                                    <?php elseif ($absenHariIni && $absenHariIni['status_datang'] != 'rejected' && (!$absenHariIni['jam_pulang'] || $absenHariIni['status_pulang'] == 'rejected')): ?>
                                    <?php if ($absenHariIni['status_pulang'] == 'rejected'): ?>
                                    <div class="alert alert-danger">Absen pulang Anda ditolak. Silakan absen ulang.</div>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-warning btn-lg" onclick="setTipe('pulang')" id="btnPulang" <?= $jadwal && !$bolehPulang ? 'disabled' : '' ?>>
                                        <i class="bi bi-box-arrow-right"></i> Absen Pulang
                                    </button>
                                    <?php if ($jadwal && !$bolehPulang): ?>
                                    <small class="text-muted">Jadwal pulang: <?= $jadwal['jam_pulang_mulai'] ?> - <?= $jadwal['jam_pulang_selesai'] ?></small>
                                    <?php endif; ?>
                                    <?php else: ?>
                                    <div class="alert alert-success">Anda sudah absen datang & pulang hari ini.</div>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const latPT = <?= $siswa['latitude'] ?? 0 ?>;
const lngPT = <?= $siswa['longitude'] ?? 0 ?>;
const radius = <?= $siswa['radius_toleransi'] ?? 50 ?>;
let currentLat = 0, currentLng = 0;
let fotoDataUrl = '';

function setTipe(tipe) {
    document.getElementById('tipe_absen').value = tipe;
    mulaiAbsen();
}

function mulaiAbsen() {
    const btnDatang = document.getElementById('btnDatang');
    const btnPulang = document.getElementById('btnPulang');
    if (btnDatang) btnDatang.disabled = true;
    if (btnPulang) btnPulang.disabled = true;

    document.getElementById('btnCekLokasi').classList.add('d-none');

    if (latPT == 0 && lngPT == 0) {
        document.getElementById('lokasi_status').textContent = 'Koordinat PT belum diatur oleh admin. Hubungi admin.';
        document.getElementById('btnAmbilFoto').disabled = false;
        if (btnDatang) btnDatang.disabled = false;
        if (btnPulang) btnPulang.disabled = false;
        return;
    }

    if (navigator.geolocation) {
        document.getElementById('lokasi_status').textContent = 'Mendapatkan lokasi...';
        navigator.geolocation.getCurrentPosition(
            function(pos) {
                currentLat = pos.coords.latitude;
                currentLng = pos.coords.longitude;
                document.getElementById('latitude').value = currentLat;
                document.getElementById('longitude').value = currentLng;
                document.getElementById('lokasi_status').textContent =
                    'Lat: ' + currentLat.toFixed(6) + ', Lng: ' + currentLng.toFixed(6);

                const jarak = hitungJarak(currentLat, currentLng, latPT, lngPT);
                const jarakInfo = document.getElementById('jarak_info');
                jarakInfo.classList.remove('d-none');

                if (jarak <= radius) {
                    jarakInfo.className = 'alert alert-success';
                    jarakInfo.textContent = 'Anda berada dalam radius (' + jarak.toFixed(1) + ' m)';
                } else {
                    jarakInfo.className = 'alert alert-danger';
                    jarakInfo.innerHTML = 'Anda di luar radius! Jarak: ' + jarak.toFixed(1) + ' m (maks: ' + radius + ' m)';
                }
                document.getElementById('btnAmbilFoto').disabled = false;
            },
            function(err) {
                let msg = 'Lokasi tidak terbaca otomatis. ';
                if (err.code == 1) msg += 'Izinkan akses lokasi di browser.';
                else if (err.code == 2) msg += 'Sinyal GPS tidak tersedia.';
                else if (err.code == 3) msg += 'Waktu habis.';
                else msg += err.message;
                msg += ' Silakan lanjutkan absen tanpa GPS.';
                document.getElementById('lokasi_status').textContent = msg;
                document.getElementById('btnCekLokasi').classList.remove('d-none');
                document.getElementById('btnAmbilFoto').disabled = false;
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    } else {
        document.getElementById('lokasi_status').textContent = 'GPS tidak didukung. Lanjutkan tanpa GPS.';
        document.getElementById('btnAmbilFoto').disabled = false;
    }
}

document.getElementById('btnAmbilFoto').addEventListener('click', function() {
    document.getElementById('fileInput').click();
});

document.getElementById('fileInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(ev) {
        fotoDataUrl = ev.target.result;
        document.getElementById('foto_data').value = fotoDataUrl;
        document.getElementById('foto_hasil').src = fotoDataUrl;
        document.getElementById('kamera_container').classList.add('d-none');
        document.getElementById('foto_container').classList.remove('d-none');
    };
    reader.readAsDataURL(file);
});

document.getElementById('btnUlang').addEventListener('click', function() {
    document.getElementById('foto_container').classList.add('d-none');
    document.getElementById('kamera_container').classList.remove('d-none');
    document.getElementById('fileInput').value = '';
});

document.getElementById('btnKirim').addEventListener('click', function() {
    document.getElementById('btnKirim').disabled = true;
    document.getElementById('btnKirim').textContent = 'Mengirim...';
    document.getElementById('formAbsen').submit();
});

function hitungJarak(lat1, lon1, lat2, lon2) {
    const R = 6371000;
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
              Math.sin(dLon/2) * Math.sin(dLon/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}
</script>
