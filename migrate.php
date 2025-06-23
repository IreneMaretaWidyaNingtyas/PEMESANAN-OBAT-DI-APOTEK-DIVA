<?php
$koneksi = mysqli_connect('hopper.proxy.rlwy.net', 'root', 'kgrVBYlHaoXAsSUmoXFUpLGpRvlHfkyK', 'railway', 11750);

// Periksa koneksi
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// SQL migrasi
$sql = "
CREATE TABLE IF NOT EXISTS obat (
  id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  type VARCHAR(50) DEFAULT NULL,
  kegunaan TEXT NOT NULL,
  harga DECIMAL(10,2) NOT NULL,
  stok INT(11) NOT NULL,
  gambar VARCHAR(255) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS transaksi (
  id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  user_id INT(11) DEFAULT NULL,
  tanggal DATE DEFAULT NULL,
  status ENUM('Menunggu','Diproses','Selesai','Dibatalkan') DEFAULT 'Menunggu',
  metode_pembayaran VARCHAR(50) DEFAULT NULL,
  metode_pengiriman VARCHAR(50) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS transaksi_detail (
  id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  transaksi_id INT(11) DEFAULT NULL,
  obat_id INT(11) DEFAULT NULL,
  jumlah INT(11) DEFAULT NULL
);
";

// Jalankan multi-query
if (mysqli_multi_query($koneksi, $sql)) {
    echo "Migrasi berhasil dijalankan!";
} else {
    echo "Error migrasi: " . mysqli_error($koneksi);
}
?>
