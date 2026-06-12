<?php
function cekLogin() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php?page=login');
        exit;
    }
}

function cekRole($roles) {
    if (!isset($_SESSION['role'])) {
        header('Location: index.php?page=login');
        exit;
    }
    if (!in_array($_SESSION['role'], (array)$roles)) {
        header('Location: index.php?page=forbidden');
        exit;
    }
}

function setFlash($key, $message, $type = 'danger') {
    $_SESSION['flash'][$key] = ['message' => $message, 'type' => $type];
}

function getFlash($key) {
    if (isset($_SESSION['flash'][$key])) {
        $flash = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $flash;
    }
    return null;
}

function displayFlash($key = null) {
    if ($key) {
        $flash = getFlash($key);
        if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show">
                <?= htmlspecialchars($flash['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif;
    } else {
        if (isset($_SESSION['flash'])) {
            foreach ($_SESSION['flash'] as $k => $f) {
                $flash = getFlash($k); ?>
                <div class="alert alert-<?= $f['type'] ?> alert-dismissible fade show">
                    <?= htmlspecialchars($f['message']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php }
        }
    }
}
