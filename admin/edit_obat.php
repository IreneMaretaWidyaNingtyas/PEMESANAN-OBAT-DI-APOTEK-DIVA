<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM obat WHERE id=$id"));

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $kegunaan = $_POST['kegunaan'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    mysqli_query($conn, "UPDATE obat SET nama='$nama', kegunaan='$kegunaan', harga='$harga', stok='$stok' WHERE id=$id");
    header("Location: obat.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Obat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Edit Obat</h2>
    <form method="POST">
        <div class="mb-3">
            <label>Nama Obat</label>
            <input type="text" name="nama" class="form-control" value="<?= $data['nama'] ?>" required>
        </div>
        <div class="mb-4">
    <label for="editType" class="block text-sm font-medium text-gray-700">Type</label>
    <input type="text" id="editType" name="type" class="mt-1 p-2 w-full border border-gray-300 rounded" />
</div>

        <div class="mb-3">
            <label>Kegunaan</label>
            <textarea name="kegunaan" class="form-control" required><?= $data['kegunaan'] ?></textarea>
        </div>
        <div class="mb-3">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control" value="<?= $data['harga'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Stok</label>
            <input type="number" name="stok" class="form-control" value="<?= $data['stok'] ?>" required>
        </div>
        <button class="btn btn-success">Update</button>
        <a href="obat.php" class="btn btn-secondary">Kembali</a>
    </form>
</body>
</html>
