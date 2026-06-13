<?php
header("HTTP/1.0 403 Forbidden");
?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Akses Ditolak</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container text-center mt-5">
    <h1 class="text-danger"><i class="bi bi-shield-exclamation"></i> 403</h1>
    <h3>Akses Ditolak</h3>
    <p>Anda tidak memiliki izin untuk mengakses halaman ini.</p>
    <a href="<?= defined('BASE_URL') ? BASE_URL : '' ?>/index.php?page=login" class="btn btn-primary">Kembali ke Login</a>
</div>
</body>
</html>
