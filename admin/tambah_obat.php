<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $kegunaan = $_POST['kegunaan'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $type = $_POST['type']; // Tambahan untuk type

    // Handle gambar
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    $folder = "../uploads/" . $gambar;

    if (move_uploaded_file($tmp, $folder)) {
        // Insert data ke database termasuk gambar dan type
        mysqli_query($conn, "INSERT INTO obat (nama, kegunaan, harga, stok, gambar, type) 
            VALUES ('$nama','$kegunaan', '$harga', '$stok', '$gambar', '$type')");
        header("Location: obat.php");
        exit;
    } else {
        echo "Gagal upload gambar!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Obat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Tambah Obat</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Nama Obat</label>
            <input placeholder="Masukkan Nama Obat" type="text" name="nama" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Kegunaan</label>
            <textarea placeholder="Masukkan Kegunaan Obat" name="kegunaan" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label>Harga</label>
            <input placeholder="Masukkan Harga Obat" type="number" name="harga" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Stok</label>
            <input placeholder="Masukkan Jumlah Stok" type="number" name="stok" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Jenis (Type)</label>
            <select name="type" class="form-control" required>
                <option value="">-- Pilih Jenis Obat --</option>
                <option value="alkes">Alkes</option>
                <option value="cairan infus">Cairan Infus</option>
                <option value="salep gel">Salep Gel</option>
                <option value="syrup">Syrup</option>
                <option value="tablet/kapsul sachet">Tablet/Kapsul Sachet</option>
                <option value="tetes mata/telinga">Tetes Mata/Telinga</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Gambar Obat</label>
            <input type="file" name="gambar" class="form-control" required>
        </div>

        <button class="btn btn-success">Simpan</button>
        <a href="obat.php" class="btn btn-secondary">Kembali</a>
    </form>
</body>
</html>
