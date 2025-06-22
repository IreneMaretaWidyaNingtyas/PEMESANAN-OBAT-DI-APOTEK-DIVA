<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['keranjang'])) {
    die("Akses tidak sah.");
}

$user_id = $_SESSION['user_id'];
$keranjang = $_SESSION['keranjang'];
$tanggal = date('Y-m-d');

$metode_pembayaran = $_POST['metode_pembayaran'] ?? 'COD';
$metode_pengiriman = $_POST['metode_pengiriman'] ?? 'Ambil';

mysqli_begin_transaction($conn);

try {
    // ini codingan untuk mengecek stok
    foreach ($keranjang as $id_obat => $jumlah) {
        $result = mysqli_query($conn, "SELECT stok FROM obat WHERE id = $id_obat");
        $obat = mysqli_fetch_assoc($result);

        if (!$obat || $obat['stok'] < $jumlah) {
            throw new Exception("Stok tidak mencukupi untuk produk ID $id_obat.");
        }
    }

    // ini codingan untuk simpan transaksi
    $stmt = mysqli_prepare($conn, "INSERT INTO transaksi (user_id, tanggal, status, metode_pembayaran, metode_pengiriman) VALUES (?, ?, 'Menunggu', ?, ?)");
    mysqli_stmt_bind_param($stmt, 'isss', $user_id, $tanggal, $metode_pembayaran, $metode_pengiriman);
    mysqli_stmt_execute($stmt);
    $id_transaksi = mysqli_insert_id($conn);

    // ini codingan simpan detail & update stok
    foreach ($keranjang as $id_obat => $jumlah) {
        mysqli_query($conn, "INSERT INTO transaksi_detail (transaksi_id, obat_id, jumlah) VALUES ($id_transaksi, $id_obat, $jumlah)");
        mysqli_query($conn, "UPDATE obat SET stok = stok - $jumlah WHERE id = $id_obat");
    }

    mysqli_commit($conn);
    unset($_SESSION['keranjang']);
    header("Location: invoice.php?id=$id_transaksi");
    exit;

} catch (Exception $e) {
    mysqli_rollback($conn);
    echo "Checkout gagal: " . $e->getMessage();
}
?>
