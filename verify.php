<?php
// File ini untuk verifikasi email user
require_once 'includes/db.php';

$pesan = '';
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $result = mysqli_query($conn, "SELECT * FROM users WHERE verify_token='$token' AND is_verified=0");
    if (mysqli_num_rows($result) == 1) {
        mysqli_query($conn, "UPDATE users SET is_verified=1, verify_token=NULL WHERE verify_token='$token'");
        $pesan = 'Verifikasi berhasil! Silakan login.';
    } else {
        $pesan = 'Token tidak valid atau akun sudah diverifikasi.';
    }
} else {
    $pesan = 'Token verifikasi tidak ditemukan.';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Email</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="alert alert-info">
            <?= $pesan ?>
        </div>
        <a href="login.php" class="btn btn-primary">Login</a>
    </div>
</body>
</html>
