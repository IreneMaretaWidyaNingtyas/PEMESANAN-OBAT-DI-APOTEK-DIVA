<?php
session_start();
require_once '../includes/db.php';
// Cek jika admin sudah login, jika belum redirect ke login admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}
// Ambil semua transaksi terbaru
$conn = mysqli_connect('hopper.proxy.rlwy.net', 'root', 'kgrVBYlHaoXAsSUmoXFUpLGpRvlHfkyK', 'railway', 11750);
$transaksi = mysqli_query($conn, "SELECT t.*, u.nama AS nama_user, u.email FROM transaksi t JOIN users u ON t.user_id = u.id ORDER BY t.tanggal DESC");
// Update status jika ada request dari form
if (isset($_POST['update_status']) && isset($_POST['id']) && isset($_POST['status'])) {
    $id = intval($_POST['id']);
    $status = $_POST['status'] === 'Selesai' ? 'Selesai' : 'Menunggu';
    mysqli_query($conn, "UPDATE transaksi SET status='$status' WHERE id=$id");
    // Refresh data
    $transaksi = mysqli_query($conn, "SELECT t.*, u.nama AS nama_user, u.email FROM transaksi t JOIN users u ON t.user_id = u.id ORDER BY t.tanggal DESC");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Invoice Masuk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
<body class="bg-gray-100 font-sans">
<div class="flex min-h-screen">
    <aside class="w-64 bg-white shadow-md">
        <div class="p-6 text-blue-700 font-bold text-xl">Admin Diva</div>
        <nav class="mt-6">
            <a href="dashboard.php" class="block px-6 py-3 <?= $currentPage === 'dashboard.php' ? 'bg-blue-200 text-blue-700 font-semibold' : 'hover:bg-blue-100 text-gray-700' ?>">Dashboard</a>
            <a href="obat.php" class="block px-6 py-3 <?= $currentPage === 'obat.php' ? 'bg-blue-200 text-blue-700 font-semibold' : 'hover:bg-blue-100 text-gray-700' ?>">Kelola Obat</a>
            <a href="users.php" class="block px-6 py-3 <?= $currentPage === 'users.php' ? 'bg-blue-200 text-blue-700 font-semibold' : 'hover:bg-blue-100 text-gray-700' ?>">Kelola User</a>
            <a href="invoices.php" class="block px-6 py-3 <?= $currentPage === 'invoices.php' ? 'bg-blue-200 text-blue-700 font-semibold' : 'hover:bg-blue-100 text-gray-700' ?>">Invoices</a>
            <a href="../login.php" class="block px-6 py-3 text-red-500 hover:bg-red-100">Logout</a>
        </nav>
    </aside>
    <main class="flex-1 p-8">
        <div class="container mt-4">
            <h1 class="text-2xl font-bold mb-4">Daftar Invoice/Pesanan Masuk</h1>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = mysqli_fetch_assoc($transaksi)) : ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['nama_user']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= $row['tanggal'] ?></td>
                            <td>
                                <form method="POST" style="display:inline-block;">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <select name="status" class="form-select form-select-sm d-inline w-auto">
                                        <option value="Menunggu" <?= $row['status'] == 'Menunggu' ? 'selected' : '' ?>>Menunggu</option>
                                        <option value="Selesai" <?= $row['status'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                                    </select>
                                    <button type="submit" name="update_status" class="btn btn-sm btn-success">Update</button>
                                </form>
                            </td>
                            <td><a href="../invoice.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary" target="_blank">Lihat Invoice</a></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
</body>
</html>
