<?php
$koneksi = mysqli_connect('hopper.proxy.rlwy.net', 'root', 'kgrVBYlHaoXAsSUmoXFUpLGpRvlHfkyK', 'railway', 11750);

$sql = "
ALTER TABLE users
  MODIFY COLUMN username VARCHAR(100),
  MODIFY COLUMN password VARCHAR(100),
  MODIFY COLUMN email VARCHAR(100)
";
if (mysqli_query($koneksi, $sql)) {
    echo "Kolom berhasil diubah.";
} else {
    echo "Error: " . mysqli_error($koneksi);
}
?>
