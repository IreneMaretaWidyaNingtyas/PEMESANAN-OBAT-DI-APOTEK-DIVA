<?php
session_start();
require_once 'includes/db.php';

$pesan = isset($_GET['pesan']) && $_GET['pesan'] == 'berhasil-daftar' ? "Pendaftaran berhasil, silakan login." : "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $conn = mysqli_connect('hopper.proxy.rlwy.net', 'root', 'kgrVBYlHaoXAsSUmoXFUpLGpRvlHfkyK', 'railway', 11750);
    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $user  = mysqli_fetch_assoc($query);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama']    = $user['nama'];
        $_SESSION['role']    = $user['role'];

        if ($user['role'] == 'admin') {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: katalog.php");
        }
        exit;
    } else {
        $pesan = "Email atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap & Font -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            display: flex;
            height: 100vh;
        }
        .form-section {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }
        .image-section {
            flex: 1;
            background: url('https://images.unsplash.com/photo-1603706580932-6befcf7d8521?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OHx8cGhhcm1hY3l8ZW58MHx8MHx8fDA%3D') no-repeat center center;
            background-size: cover;
        }
        .form-box {
            width: 100%;
            max-width: 400px;
        }
        .form-box h2 {
            font-weight: 600;
        }
        .btn-login {
            background-color: #2563EB;
            color: white;
        }
        .btn-login:hover {
            background-color: #2563EB;
        }
        .divider {
            text-align: center;
            margin: 1.5rem 0;
            position: relative;
        }
        .divider::before, .divider::after {
            content: '';
            position: absolute;
            width: 45%;
            height: 1px;
            background: #ccc;
            top: 50%;
        }
        .divider::before {
            left: 0;
        }
        .divider::after {
            right: 0;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="form-section">
            <div class="form-box">
                <h2 class="mb-4">Welcome Back</h2>

                <?php if ($pesan): ?>
                    <div class="alert alert-warning"><?= $pesan ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label>Email address</label>
                        <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                    </div>
                    <div class="mb-3 text-end">
                        <a href="forgot_password.php" class="text-decoration-none text-muted">Forgot password?</a>
                    </div>
                    <button type="submit" class="btn btn-login w-100">Login</button>

                </form>

                <p class="mt-4 text-center">
                    Don't have an account? <a href="register.php" class="text-decoration-none">Sign up</a>
                </p>
            </div>
        </div>
        <div class="image-section"></div>
    </div>
</body>
</html>
