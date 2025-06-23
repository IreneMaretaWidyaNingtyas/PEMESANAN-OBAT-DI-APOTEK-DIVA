<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

$currentPage = basename($_SERVER['PHP_SELF']);
$total_obat = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM obat"));
$total_user = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users WHERE role = 'user'"));
$data_obat = mysqli_query($conn, "SELECT * FROM obat");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin Obat</title>
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
</head>
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
        <h1 class="text-2xl font-bold mb-4">
            Selamat Datang, <?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Admin' ?>!
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-10">
            <div class="bg-white shadow-md p-6 rounded-lg border-l-4 border-blue-500">
                <p class="text-gray-600">Total Obat</p>
                <p class="text-3xl font-semibold text-blue-700"><?= $total_obat ?></p>
            </div>
            <div class="bg-white shadow-md p-6 rounded-lg border-l-4 border-green-500">
                <p class="text-gray-600">Total Pengguna</p>
                <p class="text-3xl font-semibold text-green-700"><?= $total_user ?></p>
            </div>
        </div>

        <!-- Table list obat dengan kolom Jenis -->
        <div class="bg-white shadow-md rounded-lg overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gambar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th> <!-- kolom baru -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kegunaan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php while($obat = mysqli_fetch_assoc($data_obat)) : ?>
                        <tr>
                            <td class="px-6 py-4">
                                <img src="../uploads/<?= htmlspecialchars($obat['gambar']) ?>" alt="<?= htmlspecialchars($obat['nama']) ?>" class="w-32 h-16 object-cover rounded">
                            </td>
                            <td class="px-6 py-4 text-gray-700"><?= htmlspecialchars($obat['nama']) ?></td>
                            <td class="px-6 py-4 text-gray-700"><?= htmlspecialchars(ucwords($obat['type'])) ?></td> <!-- tampilkan type -->
                            <td class="px-6 py-4 text-gray-700"><?= htmlspecialchars($obat['kegunaan']) ?></td>
                            <td class="px-6 py-4 text-gray-700">Rp <?= number_format($obat['harga'], 0, ',', '.') ?></td>
                            <td class="px-6 py-4 text-gray-700"><?= htmlspecialchars($obat['stok']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

</body>
</html>
