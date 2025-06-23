<?php
require_once '../includes/db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $result = mysqli_query($conn, "SELECT * FROM obat WHERE id = $id");

    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode([
            'id' => $row['id'],
            'nama' => $row['nama'],
            'type' => $row['type'],
            'kegunaan' => $row['kegunaan'],
            'harga' => $row['harga'],
            'stok' => $row['stok'],
        ]);
    } else {
        echo json_encode(null);
    }
}
?>
