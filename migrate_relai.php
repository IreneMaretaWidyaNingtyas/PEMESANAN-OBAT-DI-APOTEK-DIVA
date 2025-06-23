<?php
$koneksi = mysqli_connect('hopper.proxy.rlwy.net', 'root', 'kgrVBYlHaoXAsSUmoXFUpLGpRvlHfkyK', 'railway', 11750);

// Periksa koneksi
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Perintah SQL: ALTER & CONSTRAINTS
$sql = "
-- FOREIGN KEY CONSTRAINTS
ALTER TABLE keranjang
  ADD CONSTRAINT keranjang_ibfk_1 FOREIGN KEY (user_id) REFERENCES users (id),
  ADD CONSTRAINT keranjang_ibfk_2 FOREIGN KEY (obat_id) REFERENCES obat (id);

ALTER TABLE transaksi
  ADD CONSTRAINT transaksi_ibfk_1 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE;

ALTER TABLE transaksi_detail
  ADD CONSTRAINT transaksi_detail_ibfk_1 FOREIGN KEY (transaksi_id) REFERENCES transaksi (id) ON DELETE CASCADE,
  ADD CONSTRAINT transaksi_detail_ibfk_2 FOREIGN KEY (obat_id) REFERENCES obat (id) ON DELETE CASCADE;
";

// Jalankan SQL dalam multi-query
if (mysqli_multi_query($koneksi, $sql)) {
    echo "Alter & constraints berhasil dijalankan!";
    do {
        mysqli_store_result($koneksi);
    } while (mysqli_next_result($koneksi));
} else {
    echo "Error migrasi: " . mysqli_error($koneksi);
}
?>
