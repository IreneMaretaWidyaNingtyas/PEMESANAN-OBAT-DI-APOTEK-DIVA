<?php
session_start();
$conn = mysqli_connect('hopper.proxy.rlwy.net', 'root', 'kgrVBYlHaoXAsSUmoXFUpLGpRvlHfkyK', 'railway', 11750);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_obat = (int) $_POST['id_obat'];

    if (isset($_SESSION['keranjang'][$id_obat])) {
        unset($_SESSION['keranjang'][$id_obat]);
    }
}

header('Location: keranjang.php');
exit;
