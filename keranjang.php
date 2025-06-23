<?php
session_start();
require_once 'includes/db.php';
$conn = mysqli_connect('hopper.proxy.rlwy.net', 'root', 'kgrVBYlHaoXAsSUmoXFUpLGpRvlHfkyK', 'railway', 11750);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_obat = (int) $_POST['id_obat'];
    $jumlah = (int) $_POST['jumlah'];

    if (!isset($_SESSION['keranjang'])) {
        $_SESSION['keranjang'] = [];
    }

    if (isset($_SESSION['keranjang'][$id_obat])) {
        $_SESSION['keranjang'][$id_obat] += $jumlah;
    } else {
        $_SESSION['keranjang'][$id_obat] = $jumlah;
    }

    header('Location: keranjang.php');
    exit;
}

$items = [];
$total = 0;

if (isset($_SESSION['keranjang'])) {
    foreach ($_SESSION['keranjang'] as $id => $qty) {
        $res = mysqli_query($conn, "SELECT * FROM obat WHERE id = $id");
        if ($item = mysqli_fetch_assoc($res)) {
            $qty = min($qty, $item['stok']); // batas maksimal sesuai stok
            $_SESSION['keranjang'][$id] = $qty; // update session juga

            $item['jumlah'] = $qty;
            $item['subtotal'] = $item['harga'] * $qty;
            $items[] = $item;
            $total += $item['subtotal'];
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Keranjang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
</head>
<body class="p-10 bg-gray-100 font-[Poppins]">
    <h1 class="text-2xl font-bold mb-6">🛒 Keranjang Belanja</h1>

    <?php if (empty($items)): ?>
        <p class="text-center text-gray-600">Keranjang Anda kosong.</p>
    <?php else: ?>
        <table class="w-full bg-white rounded shadow">
    <thead>
        <tr>
            <th class="text-left p-2">Nama</th>
            <th class="text-left p-2">Harga</th>
            <th class="text-left p-2">Jumlah</th>
            <th class="text-left p-2">Subtotal</th>
            <th class="text-left p-2">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $item): ?>
        <tr class="border-t">
            <td class="p-2"><?= htmlspecialchars($item['nama']) ?></td>
            <td class="p-2">Rp<?= number_format($item['harga']) ?></td>
            <td class="p-2">
                <form action="update_keranjang.php" method="post" class="flex items-center gap-2">
                    <input type="hidden" name="id_obat" value="<?= $item['id'] ?>">
                    <button name="action" value="decrease" class="px-2 bg-red-500 text-white rounded">-</button>
                    <span><?= $item['jumlah'] ?></span>
                    <button name="action" value="increase" class="px-2 bg-blue-500 text-white rounded">+</button>
                </form>
            </td>
            <td class="p-2">Rp<?= number_format($item['subtotal']) ?></td>
            <td class="p-2">
                <form action="hapus_item.php" method="post" onsubmit="return confirm('Yakin ingin menghapus item ini?')">
                    <input type="hidden" name="id_obat" value="<?= $item['id'] ?>">
                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>


        <div class="mt-6 text-right text-lg font-semibold">
            Total: Rp<?= number_format($total) ?>
        </div>

        <form method="post" action="checkout.php" class="mt-4 space-y-3 bg-white p-4 rounded shadow max-w-md" id="formCheckout">
  <div>
    <label class="font-semibold">Metode Pembayaran:</label>
    <select name="metode_pembayaran" class="w-full border rounded p-2">
      <option value="CASH">CASH</option>
<<<<<<< HEAD
      <option disabled="true" value="VA">Virtual Account (Maintenance)</option>
      <option disabled="true" value="QRIS">QRIS (Maintenance)</option>
=======
>>>>>>> eb17f924baed30d9abfd4082946e1acf6279a5a3
    </select>
  </div>
  <div>
    <label class="font-semibold">Metode Pengiriman:</label>
    <select name="metode_pengiriman" class="w-full border rounded p-2" id="metodePengiriman">
      <option value="Ambil">Ambil ke Outlet</option>
      <option value="Gojek">GoShop</option>
    </select>
  </div>
  <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Checkout</button>
</form>
<script>
document.getElementById('formCheckout').addEventListener('submit', function(e) {
  var metode = document.getElementById('metodePengiriman').value;
  if (metode === 'Gojek') {
    
  }
});
</script>

    <?php endif; ?>
</body>
</html>
