<?php
require_once 'includes/db.php';

// ini codingan untuk mengambil ID dari query string (GET)
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

$status = null;
if ($id) {
    $result = mysqli_query($conn, "SELECT * FROM transaksi WHERE id = $id");
    if ($result && mysqli_num_rows($result) > 0) {
        $status = mysqli_fetch_assoc($result);
    }
}
?>

<!-- ini codingan untuk untuk form input id transaksi -->
<form onsubmit="return cekStatusTransaksi(event)" class="mb-4">
    <input type="number" id="inputIdTransaksi" name="id" placeholder="Masukkan ID Transaksi"
           class="border p-2 w-full rounded mb-2" value="<?= htmlspecialchars($id) ?>" required>
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded w-full">Cek Status</button>
</form>

<!-- ini codingan untuk menampilkan hasil statur transaksi -->
<div id="hasilStatus">
<?php if ($status): ?>
    <div class="p-4 bg-gray-50 rounded mt-4">
        <p><strong>ID Transaksi:</strong> <?= $status['id'] ?></p>
        <p><strong>Tanggal:</strong> <?= $status['tanggal'] ?></p>
        <p><strong>Status:</strong> 
            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-sm"><?= htmlspecialchars($status['status']) ?></span>
        </p>
        <p><strong>Metode Pembayaran:</strong> <?= htmlspecialchars($status['metode_pembayaran']) ?></p>
        <p><strong>Metode Pengiriman:</strong> <?= htmlspecialchars($status['metode_pengiriman']) ?></p>
        <a href="invoice.php?id=<?= $status['id'] ?>" class="text-blue-600 underline mt-2 inline-block">📄 Lihat Invoice</a>
    </div>
<?php elseif ($id): ?>
    <p class="text-red-500 mt-2">❌ Transaksi dengan ID <?= htmlspecialchars($id) ?> tidak ditemukan.</p>
<?php else: ?>
    <p class="text-gray-500 text-sm">Masukkan ID transaksi untuk melihat status pemesanan Anda.</p>
<?php endif; ?>
</div>

<!-- ini codingan untuk script ajax -->
<script>
function cekStatusTransaksi(e) {
    e.preventDefault();
    const id = document.getElementById('inputIdTransaksi').value;
    if (!id) return false;

    fetch('cek_status.php?id=' + encodeURIComponent(id))
      .then(res => res.text())
      .then(html => {
        document.getElementById('kontenStatus').innerHTML = html;
      })
      .catch(() => {
        document.getElementById('kontenStatus').innerHTML = '<p class="text-red-500">Gagal memuat status transaksi.</p>';
      });

    return false;
}
</script>
