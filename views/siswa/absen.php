<div class="bento-grid">
    <?php if (isset($error)): ?>
    <div class="bento-full">
        <div class="alert alert-danger alert-dismissible fade show"><?= $error ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    </div>
    <?php endif; ?>

    <div class="bento-full">
        <div class="absen-hero-status animate-fade-in-up">
            <div class="status-left">
                <h2><?= htmlspecialchars($siswa['nama_lengkap']) ?></h2>
                <p><?= htmlspecialchars($siswa['nama_pt'] ?? 'Belum ditempatkan') ?></p>
            </div>
            <div class="status-badge">
                <?php if ($absenHariIni): ?>
                    <?php if ($absenHariIni['jam_datang'] && $absenHariIni['jam_pulang']): ?>
                        Selesai Hari Ini
                    <?php else: ?>
                        Sudah Absen Datang
                    <?php endif; ?>
                <?php else: ?>
                    Belum Absen
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="bento-full">
        <div class="split-layout">
            <div>
                <div class="gps-card animate-fade-in-up animate-delay-1">
                    <div class="gps-header">
                        <div class="gps-icon"><i class="bi bi-geo-alt-fill"></i></div>
                        <h3>Verifikasi Lokasi</h3>
                    </div>
                    <div class="gps-detail">
                        <div class="gps-item">
                            <div class="gps-label">Latitude</div>
                            <div class="gps-value" id="gpsLat">-</div>
                        </div>
                        <div class="gps-item">
                            <div class="gps-label">Longitude</div>
                            <div class="gps-value" id="gpsLng">-</div>
                        </div>
                        <div class="gps-item">
                            <div class="gps-label">Akurasi</div>
                            <div class="gps-value" id="gpsAccuracy">-</div>
                        </div>
                        <div class="gps-item">
                            <div class="gps-label">Jarak ke PT</div>
                            <div class="gps-value" id="gpsDistance">-</div>
                        </div>
                    </div>
                    <div id="radiusStatus" class="gps-radius-status">Memeriksa lokasi...</div>
                    <div id="mapPreview">
                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:var(--text-muted);font-size:14px;">Peta akan muncul saat GPS aktif</div>
                    </div>
                    <button id="btnRefreshGPS" class="btn-absen-cta" style="background:var(--bg);color:var(--text-primary);font-size:14px;padding:10px;">
                        <i class="bi bi-arrow-clockwise"></i> Perbarui Lokasi
                    </button>
                </div>

                <div class="verification-checklist animate-fade-in-up animate-delay-2" style="margin-top:20px;">
                    <div class="check-item">
                        <div class="check-icon check-pending" id="checkGps"><i class="bi bi-question"></i></div>
                        <span class="check-label">GPS Aktif</span>
                        <span class="check-status" id="checkGpsStatus">Memeriksa...</span>
                    </div>
                    <div class="check-item">
                        <div class="check-icon check-pending" id="checkRadius"><i class="bi bi-question"></i></div>
                        <span class="check-label">Dalam Radius Absen</span>
                        <span class="check-status" id="checkRadiusStatus">Memeriksa...</span>
                    </div>
                    <div class="check-item">
                        <div class="check-icon check-pending" id="checkFoto"><i class="bi bi-question"></i></div>
                        <span class="check-label">Foto Selfie Tersedia</span>
                        <span class="check-status" id="checkFotoStatus">Belum ada</span>
                    </div>
                </div>
            </div>

            <div>
                <div class="selfie-card animate-fade-in-up animate-delay-1">
                    <div class="selfie-header">
                        <div class="selfie-icon"><i class="bi bi-camera-fill"></i></div>
                        <h3>Selfie Absensi</h3>
                    </div>
                    <div class="selfie-preview" id="cameraContainer">
                        <video id="videoPreview" autoplay playsinline style="display:none;"></video>
                        <canvas id="canvasCapture" style="display:none;"></canvas>
                        <img id="fotoHasil" style="display:none;">
                        <div class="selfie-placeholder" id="placeholderSelfie">
                            <i class="bi bi-camera"></i>
                            <p>Klik tombol di bawah untuk mengambil foto</p>
                        </div>
                    </div>
                    <div class="selfie-actions">
                        <button id="btnAmbilFoto" class="btn btn-absen-cta" disabled>
                            <i class="bi bi-camera"></i> Ambil Foto
                        </button>
                        <button id="btnUlang" class="btn" style="background:var(--bg);color:var(--text-primary);display:none;">
                            <i class="bi bi-arrow-counterclockwise"></i> Ulang
                        </button>
                    </div>
                </div>

                <form id="formAbsen" method="POST" style="margin-top:20px;">
                    <input type="hidden" name="latitude" id="latitude" value="">
                    <input type="hidden" name="longitude" id="longitude" value="">
                    <input type="hidden" name="foto_data" id="foto_data" value="">
                    <input type="hidden" name="tipe" id="tipe_absen" value="datang">
                    <input type="file" id="fileInput" accept="image/*" capture="user" style="display:none;">

                    <?php if (!$absenHariIni || $absenHariIni['status_datang'] == 'rejected'): ?>
                        <?php if ($absenHariIni && $absenHariIni['status_datang'] == 'rejected'): ?>
                        <div class="alert alert-danger">Absen datang ditolak. Silakan absen ulang.</div>
                        <?php endif; ?>
                        <button type="button" class="btn-absen-cta" onclick="setTipe('datang')" id="btnKirimAbsen" disabled>
                            <i class="bi bi-box-arrow-in-right"></i> Kirim Absen Datang
                        </button>
                        <?php if ($jadwal && !$bolehDatang): ?>
                        <small style="display:block;text-align:center;margin-top:8px;color:var(--text-muted);">Jadwal masuk: <?= $jadwal['jam_masuk_mulai'] ?> - <?= $jadwal['jam_masuk_selesai'] ?></small>
                        <?php endif; ?>
                    <?php elseif ($absenHariIni && $absenHariIni['status_datang'] != 'rejected' && (!$absenHariIni['jam_pulang'] || $absenHariIni['status_pulang'] == 'rejected')): ?>
                        <?php if ($absenHariIni && $absenHariIni['status_pulang'] == 'rejected'): ?>
                        <div class="alert alert-danger">Absen pulang ditolak. Silakan absen ulang.</div>
                        <?php endif; ?>
                        <button type="button" class="btn-absen-cta" onclick="setTipe('pulang')" id="btnKirimAbsen" disabled>
                            <i class="bi bi-box-arrow-right"></i> Kirim Absen Pulang
                        </button>
                        <?php if ($jadwal && !$bolehPulang): ?>
                        <small style="display:block;text-align:center;margin-top:8px;color:var(--text-muted);">Jadwal pulang: <?= $jadwal['jam_pulang_mulai'] ?> - <?= $jadwal['jam_pulang_selesai'] ?></small>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="alert alert-success text-center" style="margin:0;">Anda sudah absen datang & pulang hari ini.</div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <div class="bento-full">
        <div class="recent-timeline animate-fade-in-up animate-delay-3">
            <div class="timeline-header">
                <h3>Riwayat Terbaru</h3>
                <a href="<?= BASE_URL ?>/index.php?page=siswa-riwayat" style="font-size:13px;color:var(--primary);font-weight:500;text-decoration:none;">Lihat Semua</a>
            </div>
            <div class="timeline-list">
                <?php
                $recent = $this->absensiModel->getRiwayat($_SESSION['siswa_id'], 5);
                foreach ($recent as $r):
                    $dotClass = $r['status_datang'] == 'approved' ? 'dot-success' : ($r['status_datang'] == 'rejected' ? 'dot-danger' : 'dot-warning');
                    $statusLabel = $r['status_datang'] == 'approved' ? 'Disetujui' : ($r['status_datang'] == 'rejected' ? 'Ditolak' : 'Menunggu');
                    $fotoSrc = $r['foto_datang'] ? BASE_URL . '/uploads/foto/' . $r['foto_datang'] : null;
                ?>
                <div class="timeline-item">
                    <div class="timeline-dot <?= $dotClass ?>"></div>
                    <div class="timeline-content">
                        <div class="timeline-top">
                            <div>
                                <div class="timeline-type">Absen Datang</div>
                                <div class="timeline-date"><?= date('d F Y', strtotime($r['tanggal'])) ?></div>
                            </div>
                            <span class="badge bg-<?= $r['status_datang']=='approved'?'success':($r['status_datang']=='rejected'?'danger':'warning') ?>" style="font-size:11px;padding:3px 10px;"><?= $statusLabel ?></span>
                        </div>
                        <div class="timeline-meta">
                            <span><i class="bi bi-clock"></i> <?= date('H:i', strtotime($r['jam_datang'])) ?></span>
                            <?php if ($r['lat_datang']): ?><span><i class="bi bi-geo"></i> <?= round(hitungJarak($r['lat_datang'], $r['lng_datang'], $siswa['latitude'], $siswa['longitude'])) ?> m</span><?php endif; ?>
                        </div>
                    </div>
                    <?php if ($fotoSrc): ?>
                    <div class="timeline-thumb"><img src="<?= $fotoSrc ?>" alt="selfie"></div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
                <?php if (empty($recent)): ?>
                <p style="text-align:center;color:var(--text-muted);padding:20px 0;margin:0;">Belum ada riwayat absensi</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="success-modal-overlay" id="successModal">
    <div class="success-modal">
        <div class="success-icon"><i class="bi bi-check-lg"></i></div>
        <h3>Absensi Berhasil!</h3>
        <p>Data absensi Anda telah dikirim dan menunggu persetujuan pembimbing.</p>
        <button class="btn btn-primary" onclick="closeModal()">Tutup</button>
    </div>
</div>

<script>
const latPT = <?= $siswa['latitude'] ?? 0 ?>;
const lngPT = <?= $siswa['longitude'] ?? 0 ?>;
const radius = <?= $siswa['radius_toleransi'] ?? 50 ?>;
let currentLat = 0, currentLng = 0, currentAccuracy = 0;
let fotoDataUrl = '';
let fotoCapture = false;
let gpsCapture = false;
let stream = null;

<?php if (isset($_GET['success'])): ?>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('successModal').classList.add('active');
});
<?php endif; ?>

function closeModal() {
    document.getElementById('successModal').classList.remove('active');
}

function setTipe(tipe) {
    document.getElementById('tipe_absen').value = tipe;
    mulaiAbsen();
}

function mulaiAbsen() {
    const btnKirim = document.getElementById('btnKirimAbsen');
    if (btnKirim) btnKirim.disabled = true;

    if (latPT == 0 && lngPT == 0) {
        updateStatus('checkGps', 'checkGpsStatus', 'check-pass', 'check-fail', 'GPS tidak diperlukan', 'Tidak ada PT');
        updateStatus('checkRadius', 'checkRadiusStatus', 'check-pass', 'check-fail', 'Radius tidak dicek', 'Tidak ada PT');
        gpsCapture = true;
        document.getElementById('btnAmbilFoto').disabled = false;
        cekSemuaSiap();
        return;
    }

    if (navigator.geolocation) {
        document.getElementById('radiusStatus').textContent = 'Mendapatkan lokasi...';
        document.getElementById('radiusStatus').className = 'gps-radius-status';
        navigator.geolocation.getCurrentPosition(
            function(pos) {
                currentLat = pos.coords.latitude;
                currentLng = pos.coords.longitude;
                currentAccuracy = pos.coords.accuracy;

                document.getElementById('latitude').value = currentLat;
                document.getElementById('longitude').value = currentLng;
                document.getElementById('gpsLat').textContent = currentLat.toFixed(6);
                document.getElementById('gpsLng').textContent = currentLng.toFixed(6);
                document.getElementById('gpsAccuracy').textContent = currentAccuracy.toFixed(0) + ' m';

                const jarak = hitungJarak(currentLat, currentLng, latPT, lngPT);
                document.getElementById('gpsDistance').textContent = jarak.toFixed(1) + ' m';

                const radiusStatus = document.getElementById('radiusStatus');
                if (jarak <= radius) {
                    radiusStatus.textContent = 'Dalam Radius Absen';
                    radiusStatus.className = 'gps-radius-status in-radius';
                    updateStatus('checkGps', 'checkGpsStatus', 'check-pass', 'check-fail', 'GPS Aktif', 'Aktif');
                    updateStatus('checkRadius', 'checkRadiusStatus', 'check-pass', 'check-fail', 'Dalam Radius', 'Dalam Radius (' + jarak.toFixed(0) + ' m)');
                } else {
                    radiusStatus.textContent = 'Di Luar Radius (' + jarak.toFixed(0) + ' m dari PT)';
                    radiusStatus.className = 'gps-radius-status out-radius';
                    updateStatus('checkGps', 'checkGpsStatus', 'check-pass', 'check-fail', 'GPS Aktif', 'Aktif');
                    updateStatus('checkRadius', 'checkRadiusStatus', 'check-pass', 'check-fail', 'Di Luar Radius', 'Terlalu jauh (' + jarak.toFixed(0) + ' m)');
                }

                updateMap(currentLat, currentLng, latPT, lngPT);
                gpsCapture = true;
                document.getElementById('btnAmbilFoto').disabled = false;
                cekSemuaSiap();
            },
            function(err) {
                let msg = 'Lokasi tidak terbaca. ';
                if (err.code == 1) msg += 'Izinkan akses lokasi.';
                else if (err.code == 2) msg += 'Sinyal GPS tidak tersedia.';
                else if (err.code == 3) msg += 'Waktu habis.';
                else msg += err.message;
                document.getElementById('radiusStatus').textContent = msg;
                document.getElementById('radiusStatus').className = 'gps-radius-status out-radius';
                updateStatus('checkGps', 'checkGpsStatus', 'check-pass', 'check-fail', 'GPS Error', 'Gagal');
                document.getElementById('btnAmbilFoto').disabled = false;
            },
            { enableHighAccuracy: true, timeout: 15000 }
        );
    } else {
        document.getElementById('radiusStatus').textContent = 'GPS tidak didukung. Lanjutkan tanpa GPS.';
        updateStatus('checkGps', 'checkGpsStatus', 'check-pass', 'check-fail', 'GPS Error', 'Tidak didukung');
        document.getElementById('btnAmbilFoto').disabled = false;
    }
}

function updateMap(lat, lng, ptLat, ptLng) {
    const mapDiv = document.getElementById('mapPreview');
    const url = 'https://www.openstreetmap.org/export/embed.html?bbox=' + (lng - 0.005) + ',' + (lat - 0.005) + ',' + (lng + 0.005) + ',' + (lat + 0.005) + '&layer=mapnik&marker=' + lat + ',' + lng;
    mapDiv.innerHTML = '<iframe width="100%" height="100%" src="' + url + '" style="border:none;"></iframe>';
}

function updateStatus(iconId, statusId, passClass, failClass, passText, failText) {
    const icon = document.getElementById(iconId);
    const status = document.getElementById(statusId);
    if (passText === 'Aktif' || passText === 'Dalam Radius') {
        icon.className = 'check-icon ' + passClass;
        icon.innerHTML = '<i class="bi bi-check-lg"></i>';
        status.textContent = passText;
        status.style.color = 'var(--success)';
    } else {
        icon.className = 'check-icon ' + failClass;
        icon.innerHTML = '<i class="bi bi-x-lg"></i>';
        status.textContent = failText;
        status.style.color = 'var(--danger)';
    }
}

document.getElementById('btnAmbilFoto').addEventListener('click', function() {
    if (stream) {
        stream.getTracks().forEach(t => t.stop());
        stream = null;
    }
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } } })
            .then(function(s) {
                stream = s;
                const video = document.getElementById('videoPreview');
                video.srcObject = s;
                video.style.display = 'block';
                document.getElementById('placeholderSelfie').style.display = 'none';
                document.getElementById('fotoHasil').style.display = 'none';
                document.getElementById('btnAmbilFoto').innerHTML = '<i class="bi bi-camera"></i> Jepret';
                document.getElementById('btnAmbilFoto').onclick = jepret;
            })
            .catch(function(err) {
                document.getElementById('fileInput').click();
            });
    } else {
        document.getElementById('fileInput').click();
    }
});

function jepret() {
    const video = document.getElementById('videoPreview');
    const canvas = document.getElementById('canvasCapture');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);
    fotoDataUrl = canvas.toDataURL('image/jpeg', 0.85);
    document.getElementById('foto_data').value = fotoDataUrl;

    document.getElementById('fotoHasil').src = fotoDataUrl;
    document.getElementById('fotoHasil').style.display = 'block';
    video.style.display = 'none';

    if (stream) {
        stream.getTracks().forEach(t => t.stop());
        stream = null;
    }

    document.getElementById('btnAmbilFoto').style.display = 'none';
    document.getElementById('btnUlang').style.display = 'block';

    fotoCapture = true;
    updateStatusCheckFoto(true);
    cekSemuaSiap();
}

document.getElementById('btnUlang').addEventListener('click', function() {
    fotoCapture = false;
    document.getElementById('fotoHasil').style.display = 'none';
    document.getElementById('btnAmbilFoto').style.display = 'block';
    document.getElementById('btnUlang').style.display = 'none';
    document.getElementById('btnAmbilFoto').innerHTML = '<i class="bi bi-camera"></i> Ambil Foto';
    document.getElementById('btnAmbilFoto').onclick = function() { document.getElementById('btnAmbilFoto').click(); };
    updateStatusCheckFoto(false);
    cekSemuaSiap();
});

document.getElementById('fileInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(ev) {
        fotoDataUrl = ev.target.result;
        document.getElementById('foto_data').value = fotoDataUrl;
        document.getElementById('fotoHasil').src = fotoDataUrl;
        document.getElementById('fotoHasil').style.display = 'block';
        document.getElementById('placeholderSelfie').style.display = 'none';
        document.getElementById('btnAmbilFoto').style.display = 'none';
        document.getElementById('btnUlang').style.display = 'block';
        fotoCapture = true;
        updateStatusCheckFoto(true);
        cekSemuaSiap();
    };
    reader.readAsDataURL(file);
});

document.getElementById('btnRefreshGPS').addEventListener('click', function() {
    gpsCapture = false;
    cekSemuaSiap();
    mulaiAbsen();
});

function updateStatusCheckFoto(ok) {
    const icon = document.getElementById('checkFoto');
    const status = document.getElementById('checkFotoStatus');
    if (ok) {
        icon.className = 'check-icon check-pass';
        icon.innerHTML = '<i class="bi bi-check-lg"></i>';
        status.textContent = 'Tersedia';
        status.style.color = 'var(--success)';
    } else {
        icon.className = 'check-icon check-pending';
        icon.innerHTML = '<i class="bi bi-question"></i>';
        status.textContent = 'Belum ada';
        status.style.color = 'var(--warning)';
    }
}

function cekSemuaSiap() {
    const btn = document.getElementById('btnKirimAbsen');
    if (!btn) return;
    const checkGps = document.getElementById('checkGpsStatus');
    const checkRadius = document.getElementById('checkRadiusStatus');
    const gpsOk = checkGps && (checkGps.textContent === 'Aktif' || checkGps.textContent === 'Tidak didukung');
    const radiusOk = !checkRadius || checkRadius.textContent === 'Dalam Radius' || checkRadius.textContent === 'Tidak dicek';
    if (gpsOk && radiusOk && fotoCapture) {
        btn.disabled = false;
    } else {
        btn.disabled = true;
    }
}

document.getElementById('btnKirimAbsen')?.addEventListener('click', function() {
    this.disabled = true;
    this.textContent = 'Mengirim...';
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
