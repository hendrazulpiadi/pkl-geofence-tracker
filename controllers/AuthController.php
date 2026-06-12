<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $userModel = new User();
            $user = $userModel->login($username, $password);

            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                require_once __DIR__ . '/../models/Siswa.php';
                require_once __DIR__ . '/../models/Pembimbing.php';

                if ($user['role'] == 'siswa') {
                    $siswaModel = new SiswaModel();
                    $siswa = $siswaModel->getByUserId($user['id']);
                    $_SESSION['siswa_id'] = $siswa['id'] ?? null;
                    $_SESSION['nama_lengkap'] = $siswa['nama_lengkap'] ?? $user['username'];
                    header('Location: index.php?page=siswa-dashboard');
                } elseif (in_array($user['role'], ['pembimbing_sekolah', 'pembimbing_pt'])) {
                    $pembimbingModel = new PembimbingModel();
                    $pembimbing = $pembimbingModel->getByUserId($user['id']);
                    $_SESSION['pembimbing_id'] = $pembimbing['id'] ?? null;
                    $_SESSION['jenis_pembimbing'] = $pembimbing['jenis'] ?? null;
                    $_SESSION['nama_lengkap'] = $pembimbing['nama_lengkap'] ?? $user['username'];
                    header('Location: index.php?page=pembimbing-dashboard');
                } else {
                    $_SESSION['nama_lengkap'] = 'Admin';
                    header('Location: index.php?page=admin-dashboard');
                }
                exit;
            } else {
                $error = 'Username atau password salah!';
                include __DIR__ . '/../views/auth/login.php';
                return;
            }
        }
        include __DIR__ . '/../views/auth/login.php';
    }

    public function logout() {
        session_destroy();
        header('Location: index.php?page=login');
        exit;
    }
}
