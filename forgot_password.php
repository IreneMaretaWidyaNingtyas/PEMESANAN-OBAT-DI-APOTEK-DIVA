<?php
require_once 'includes/db.php';
$pesan = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($cek) > 0) {
        $user = mysqli_fetch_assoc($cek);
        $token = bin2hex(random_bytes(32));
        mysqli_query($conn, "UPDATE users SET reset_token='$token' WHERE email='$email'");
        // Kirim email reset password
        require_once __DIR__ . '/includes/PHPMailer/PHPMailer.php';
        require_once __DIR__ . '/includes/PHPMailer/SMTP.php';
        require_once __DIR__ . '/includes/PHPMailer/Exception.php';
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'apotekdivaa@gmail.com';
            $mail->Password = 'ejem uvth umgd wmqq';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->setFrom('apotekdivaa@gmail.com', 'Toko Obat Diva Reset Password');
            $mail->addAddress($email, $user['nama']);
            $mail->isHTML(true);
            $mail->Subject = 'Reset Password Akun';
            $mail->Body    = 'Klik link berikut untuk reset password akun Anda:<br><a href="http://localhost/toko-obat-online/reset_password.php?token=' . $token . '">Reset Password</a>';
            $mail->send();
            $pesan = 'Link reset password sudah dikirim ke email Anda.';
        } catch (\Exception $e) {
            $pesan = 'Gagal mengirim email reset password.<br>Error: ' . $mail->ErrorInfo;
        }
    } else {
        $pesan = 'Email tidak ditemukan.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lupa Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card p-4">
                    <h3 class="mb-3">Lupa Password</h3>
                    <?php if ($pesan): ?>
                        <div class="alert alert-info"><?= $pesan ?></div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Kirim Link Reset</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
