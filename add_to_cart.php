<?php
session_start();
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_obat = (int) $_POST['id_obat'];
    $jumlah = (int) $_POST['jumlah'];
    $response = ['success' => false, 'message' => 'Gagal menambah ke keranjang'];

    $res = mysqli_query($conn, "SELECT stok FROM obat WHERE id = $id_obat");
    if ($row = mysqli_fetch_assoc($res)) {
        $stok = (int) $row['stok'];
        if ($jumlah > 0 && $jumlah <= $stok) {
            if (!isset($_SESSION['keranjang'])) {
                $_SESSION['keranjang'] = [];
            }
            if (isset($_SESSION['keranjang'][$id_obat])) {
                $new_jumlah = $_SESSION['keranjang'][$id_obat] + $jumlah;
                $_SESSION['keranjang'][$id_obat] = min($new_jumlah, $stok);
            } else {
                $_SESSION['keranjang'][$id_obat] = $jumlah;
            }
            $response = ['success' => true, 'message' => 'Berhasil ditambahkan ke keranjang'];
        } else {
            $response = ['success' => false, 'message' => 'Jumlah melebihi stok'];
        }
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
// Jika bukan POST
http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan']);
