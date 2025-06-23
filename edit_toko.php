<?php
$koneksi = mysqli_connect('hopper.proxy.rlwy.net', 'root', 'kgrVBYlHaoXAsSUmoXFUpLGpRvlHfkyK', 'railway', 11750);

$sql = "
ALTER TABLE users
  MODIFY COLUMN role VARCHAR(100),
  MODIFY COLUMN verify_token VARCHAR(100),
  MODIFY COLUMN reset_token VARCHAR(100)
";
if (mysqli_query($koneksi, $sql)) {
    echo "Kolom berhasil diubah.";
} else {
    echo "Error: " . mysqli_error($koneksi);
}
?>
