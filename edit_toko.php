<?php
$koneksi = mysqli_connect("hopper.proxy.rlwy.net", "root", "passwordmu", "namadb", 11750);

$sql = "ALTER TABLE users MODIFY COLUMN nama VARCHAR(100)";
if (mysqli_query($koneksi, $sql)) {
    echo "Kolom berhasil diubah.";
} else {
    echo "Error: " . mysqli_error($koneksi);
}
?>
