<div class="bento-grid">
    <div class="bento-full animate-fade-in-up">
        <h3 style="margin:0;font-size:20px;font-weight:700;">Pengajuan Izin / Sakit</h3>
    </div>

    <?php if (isset($_GET['success'])): ?>
    <div class="bento-full">
        <div class="alert alert-success alert-dismissible fade show">Pengajuan berhasil dikirim, menunggu approval pembimbing.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    </div>
    <?php endif; ?>

    <div class="bento-full">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
            <div class="izin-form-card animate-fade-in-up animate-delay-1">
                <form method="POST" enctype="multipart/form-data" id="formIzin">
                    <div class="form-group">
                        <label>Jenis Pengajuan</label>
                        <div class="jenis-radio">
                            <label class="radio-card active" onclick="pilihJenis('izin')">
                                <input type="radio" name="jenis" value="izin" checked>
                                <span class="radio-icon">📋</span>
                                <span class="radio-label">Izin</span>
                            </label>
                            <label class="radio-card" onclick="pilihJenis('sakit')">
                                <input type="radio" name="jenis" value="sakit">
                                <span class="radio-icon">🤒</span>
                                <span class="radio-label">Sakit</span>
                            </label>
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div class="form-group">
                            <label>Tanggal Mulai</label>
                            <input type="date" name="tgl_mulai" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Selesai</label>
                            <input type="date" name="tgl_selesai" class="form-input" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-textarea" rows="4" required placeholder="Jelaskan alasan pengajuan izin/sakit..."></textarea>
                    </div>

                    <div class="form-group">
                        <label>Upload Bukti</label>
                        <div class="upload-area" id="uploadArea" onclick="document.getElementById('fileBukti').click()">
                            <div class="upload-icon"><i class="bi bi-cloud-arrow-up"></i></div>
                            <div class="upload-text">Seret file ke sini atau <strong>klik untuk upload</strong></div>
                            <div class="upload-hint">Format: JPG, PNG (maks. 2MB) — Surat Dokter / Surat Izin</div>
                        </div>
                        <input type="file" id="fileBukti" name="bukti_foto" accept="image/*" style="display:none;" onchange="previewFile(this)">
                        <div class="upload-preview" id="uploadPreview">
                            <img id="previewImg">
                            <button type="button" class="upload-remove" onclick="removeFile()"><i class="bi bi-x"></i></button>
                        </div>
                    </div>

                    <button type="submit" class="btn-absen-cta" style="margin-top:8px;">
                        <i class="bi bi-send"></i> Kirim Pengajuan
                    </button>
                </form>
            </div>

            <div>
                <div class="recent-timeline animate-fade-in-up animate-delay-2">
                    <div class="timeline-header">
                        <h3>Riwayat Pengajuan</h3>
                    </div>
                    <div class="timeline-list">
                        <?php foreach ($riwayatIzin as $i):
                            $dotClass = $i['status_approval'] == 'approved' ? 'dot-success' : ($i['status_approval'] == 'rejected' ? 'dot-danger' : 'dot-warning');
                            $statusLabel = $i['status_approval'] == 'approved' ? 'Disetujui' : ($i['status_approval'] == 'rejected' ? 'Ditolak' : 'Menunggu');
                        ?>
                        <div class="timeline-item">
                            <div class="timeline-dot <?= $dotClass ?>"></div>
                            <div class="timeline-content">
                                <div class="timeline-top">
                                    <div>
                                        <div class="timeline-type">
                                            <span class="badge bg-<?= $i['jenis']=='sakit'?'danger':'info' ?>"><?= $i['jenis'] ?></span>
                                        </div>
                                        <div class="timeline-date"><?= date('d M', strtotime($i['tgl_mulai'])) ?> - <?= date('d M Y', strtotime($i['tgl_selesai'])) ?></div>
                                    </div>
                                    <span class="badge bg-<?= $i['status_approval']=='approved'?'success':($i['status_approval']=='rejected'?'danger':'warning') ?>" style="font-size:11px;padding:3px 10px;"><?= $statusLabel ?></span>
                                </div>
                                <div class="timeline-meta">
                                    <span><?= htmlspecialchars($i['keterangan'] ?? '') ?></span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php if (empty($riwayatIzin)): ?>
                        <p style="text-align:center;color:var(--text-muted);padding:40px 0;margin:0;">Belum ada pengajuan</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function pilihJenis(jenis) {
    document.querySelectorAll('.radio-card').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.radio-card input[value="' + jenis + '"]').forEach(el => {
        el.checked = true;
        el.closest('.radio-card').classList.add('active');
    });
}

function previewFile(input) {
    const file = input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('uploadPreview').style.display = 'block';
        document.getElementById('uploadArea').style.display = 'none';
    };
    reader.readAsDataURL(file);
}

function removeFile() {
    document.getElementById('uploadPreview').style.display = 'none';
    document.getElementById('uploadArea').style.display = 'block';
    document.getElementById('fileBukti').value = '';
}
</script>
