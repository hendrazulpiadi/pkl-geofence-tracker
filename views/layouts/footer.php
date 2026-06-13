<?php if (in_array($_SESSION['role'] ?? '', ['admin', 'pembimbing_sekolah', 'pembimbing_pt', 'siswa'])): ?>
        </main>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var sidebar = document.getElementById('adminSidebar');
    var overlay = document.getElementById('sidebarOverlay');
    var toggle = document.getElementById('sidebarToggle');
    if (toggle) {
        toggle.addEventListener('click', function(e) {
            e.stopPropagation();
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        });
    }
    if (overlay) {
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        });
    }
});
</script>
<?php else: ?>
</div>
<?php endif; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>/assets/js/script.js"></script>
</body>
</html>
