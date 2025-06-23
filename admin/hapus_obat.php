<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
$conn = mysqli_connect('hopper.proxy.rlwy.net', 'root', 'kgrVBYlHaoXAsSUmoXFUpLGpRvlHfkyK', 'railway', 11750);
    // ini query untuk hapus data dari database
    $query = mysqli_query($conn, "DELETE FROM obat WHERE id = $id");

    if ($query) {
        header("Location: obat.php?hapus=berhasil");
        exit;
    } else {
        header("Location: obat.php?hapus=gagal");
        exit;
    }
} else {
    header("Location: obat.php");
    exit;
}
