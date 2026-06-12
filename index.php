<?php
session_start();

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/helpers/session.php';
require_once __DIR__ . '/helpers/haversine.php';

spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/models/' . $class . '.php',
        __DIR__ . '/controllers/' . $class . '.php',
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) { require_once $path; break; }
    }
});

$page = $_GET['page'] ?? 'login';

ob_start();

switch ($page) {
    case 'login':
        require_once 'controllers/AuthController.php';
        $ctrl = new AuthController();
        $ctrl->login();
        break;
    case 'logout':
        require_once 'controllers/AuthController.php';
        $ctrl = new AuthController();
        $ctrl->logout();
        break;
    case 'admin-dashboard':
        require_once 'controllers/AdminController.php';
        $ctrl = new AdminController();
        $ctrl->dashboard();
        break;
    case 'admin-siswa':
        require_once 'controllers/AdminController.php';
        $ctrl = new AdminController();
        $ctrl->siswa();
        break;
    case 'admin-pembimbing':
        require_once 'controllers/AdminController.php';
        $ctrl = new AdminController();
        $ctrl->pembimbing();
        break;
    case 'admin-perusahaan':
        require_once 'controllers/AdminController.php';
        $ctrl = new AdminController();
        $ctrl->perusahaan();
        break;
    case 'admin-pengaturan-waktu':
        require_once 'controllers/AdminController.php';
        $ctrl = new AdminController();
        $ctrl->pengaturanWaktu();
        break;
    case 'siswa-dashboard':
        require_once 'controllers/SiswaController.php';
        $ctrl = new SiswaController();
        $ctrl->dashboard();
        break;
    case 'siswa-absen':
        require_once 'controllers/SiswaController.php';
        $ctrl = new SiswaController();
        $ctrl->absen();
        break;
    case 'siswa-izin':
        require_once 'controllers/SiswaController.php';
        $ctrl = new SiswaController();
        $ctrl->izin();
        break;
    case 'pembimbing-dashboard':
        require_once 'controllers/PembimbingController.php';
        $ctrl = new PembimbingController();
        $ctrl->dashboard();
        break;
    case 'pembimbing-approval':
        require_once 'controllers/PembimbingController.php';
        $ctrl = new PembimbingController();
        $ctrl->approval();
        break;
    case 'pembimbing-rekap':
        require_once 'controllers/PembimbingController.php';
        $ctrl = new PembimbingController();
        $ctrl->rekap();
        break;
    case 'pembimbing-rekap-pdf':
        require_once 'controllers/PembimbingController.php';
        $ctrl = new PembimbingController();
        $ctrl->rekapPdf();
        break;
    case 'forbidden':
        include 'views/forbidden.php';
        break;
    default:
        header('Location: index.php?page=login');
        exit;
}

$content = ob_get_clean();

if (!in_array($page, ['login'])) {
    include 'views/layouts/header.php';
    echo $content;
    include 'views/layouts/footer.php';
} else {
    echo $content;
}
