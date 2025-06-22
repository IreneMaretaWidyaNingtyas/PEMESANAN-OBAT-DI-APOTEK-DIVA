<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_obat = (int) $_POST['id_obat'];

    if (isset($_SESSION['keranjang'][$id_obat])) {
        unset($_SESSION['keranjang'][$id_obat]);
    }
}

header('Location: keranjang.php');
exit;
