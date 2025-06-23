<?php
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $kegunaan = $_POST['kegunaan'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $conn = mysqli_connect('hopper.proxy.rlwy.net', 'root', 'kgrVBYlHaoXAsSUmoXFUpLGpRvlHfkyK', 'railway', 11750);
    $query = "UPDATE obat SET nama = '$nama', kegunaan = '$kegunaan', harga = '$harga', stok = '$stok' WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
