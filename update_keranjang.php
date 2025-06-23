<?php
session_start();
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_obat = (int) $_POST['id_obat'];
    $action = $_POST['action'];
    $conn = mysqli_connect('hopper.proxy.rlwy.net', 'root', 'kgrVBYlHaoXAsSUmoXFUpLGpRvlHfkyK', 'railway', 11750);
    // ini codingan untuk Cek apakah ada di session keranjang
    if (isset($_SESSION['keranjang'][$id_obat])) {

        // ini codingan untuk ambil stok dari database
        $res = mysqli_query($conn, "SELECT stok FROM obat WHERE id = $id_obat");
        if ($row = mysqli_fetch_assoc($res)) {
            $stok = (int) $row['stok'];

            if ($action === 'increase') {
                // ini codingan untuk tambah hanya jika belum melebihi stok
                if ($_SESSION['keranjang'][$id_obat] < $stok) {
                    $_SESSION['keranjang'][$id_obat]++;
                }
            } elseif ($action === 'decrease') {
                $_SESSION['keranjang'][$id_obat]--;
                if ($_SESSION['keranjang'][$id_obat] <= 0) {
                    unset($_SESSION['keranjang'][$id_obat]);
                }
            }
        }
    }
}

header('Location: keranjang.php');
exit;
