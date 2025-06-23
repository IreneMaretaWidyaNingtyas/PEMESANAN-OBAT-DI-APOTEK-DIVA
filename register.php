<?php
require_once 'includes/db.php';
$pesan = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama     = htmlspecialchars($_POST['nama']);
    $email    = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $token    = bin2hex(random_bytes(32));

    $cek = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($cek) > 0) {
        $pesan = "Email sudah terdaftar!";
    } else {
        $query = "INSERT INTO users (nama, email, password, role, is_verified, verify_token) VALUES ('$nama', '$email', '$password', 'user', 0, '$token')";
        if (mysqli_query($conn, $query)) {
            // Kirim email verifikasi
            require_once __DIR__ . '/includes/PHPMailer/PHPMailer.php';
            require_once __DIR__ . '/includes/PHPMailer/SMTP.php';
            require_once __DIR__ . '/includes/PHPMailer/Exception.php';
            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'apotekdivaa@gmail.com'; // <-- di sini
                $mail->Password = 'ejem uvth umgd wmqq';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;
                $mail->setFrom('apotekdivaa@gmail.com', 'Toko Obat Diva'); // <-- di sini
                $mail->addAddress($email, $nama);
                $mail->isHTML(true);
                $mail->Subject = 'Verifikasi Email Akun';
                $mail->Body    = 'Halo ' . $nama . ',<br>Silakan klik link berikut untuk verifikasi akun Anda:<br><a href="http://localhost/toko-obat-online/verify.php?token=' . $token . '">Verifikasi Akun</a>';
                $mail->send();
                $pesan = "Registrasi berhasil! Silakan cek email untuk verifikasi akun.";
            } catch (\Exception $e) {
                $pesan = "Registrasi berhasil, tapi gagal mengirim email verifikasi.";
                $pesan .= '<br>Error: ' . $mail->ErrorInfo;
            }
        } else {
            $pesan = "Gagal mendaftar.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }
        .split {
            display: flex;
            height: 100vh;
        }
        .left {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }
        .form-container {
            width: 100%;
            max-width: 400px;
        }
        .right {
            flex: 1;
            background: url('https://plus.unsplash.com/premium_photo-1661769786626-8025c37907ae?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTd8fHBoYXJtYWN5fGVufDB8fDB8fHww') no-repeat center center;
            background-size: cover;
        }
        .form-control {
            border-radius: 8px;
        }
        .btn-register {
            background-color: #2563EB;
            color: white;
            border-radius: 8px;
            width: 100%;
        }
        .btn-register:hover {
            background-color: #2563EB;
        }
        .small-text {
            font-size: 0.9em;
            margin-top: 1em;
        }
    </style>
</head>
<body>
    <div class="split">
        <div class="left">
            <div class="form-container">
                <h2 class="mb-4">Get Started Now</h2>
                <?php if ($pesan): ?>
                    <div class="alert alert-warning"><?= $pesan ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label for="nama">Name</label>
                        <input type="text" name="nama" id="nama" class="form-control" placeholder="Enter your name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email">Email address</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Enter password" required>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="terms" required>
                        <label class="form-check-label" for="terms">I agree to the Terms & Policy</label>
                    </div>
                    <button type="submit" class="btn btn-register">Signup</button>
                    <div class="small-text">
                        Already have an account? <a href="login.php">Sign in</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="right"></div>
    </div>
</body>
</html>
