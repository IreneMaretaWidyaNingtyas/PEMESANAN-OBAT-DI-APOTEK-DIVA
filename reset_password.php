<?php
require_once 'includes/db.php';
$pesan = '';
$show_form = false;
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE reset_token='$token'");
    if (mysqli_num_rows($cek) == 1) {
        $show_form = true;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            mysqli_query($conn, "UPDATE users SET password='$password', reset_token=NULL WHERE reset_token='$token'");
            $pesan = 'Password berhasil direset. Silakan login.';
            $show_form = false;
        }
    } else {
        $pesan = 'Token tidak valid atau sudah digunakan.';
    }
} else {
    $pesan = 'Token tidak ditemukan.';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card p-4">
                    <h3 class="mb-3">Reset Password</h3>
                    <?php if ($pesan): ?>
                        <div class="alert alert-info"><?= $pesan ?></div>
                    <?php endif; ?>
                    <?php if ($show_form): ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-success">Reset Password</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
