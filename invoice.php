<?php
session_start();
require_once 'includes/db.php';
$id = $_GET['id'];
$conn = mysqli_connect('hopper.proxy.rlwy.net', 'root', 'kgrVBYlHaoXAsSUmoXFUpLGpRvlHfkyK', 'railway', 11750);
$trans = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT t.*, u.nama AS nama_user, u.email 
    FROM transaksi t 
    JOIN users u ON t.user_id = u.id 
    WHERE t.id = $id
"));

$details = mysqli_query($conn, "
    SELECT o.nama, o.harga, d.jumlah 
    FROM transaksi_detail d 
    JOIN obat o ON o.id = d.obat_id 
    WHERE d.transaksi_id = $id
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice #<?= $id ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-md">
        <!-- Pemberitahuan waktu operasional -->
        <div class="mt-4 mb-4 p-4 bg-yellow-100 text-yellow-800 rounded text-sm">
            <strong>Catatan:</strong> Pengambilan obat hanya bisa dilakukan pada jam operasional antara <strong>08:00 - 21:00 WIB</strong> setiap harinya.
        </div>
        <div class="mt-2 mb-8 p-4 bg-yellow-100 text-yellow-800 rounded text-sm">
            <strong>Catatan:</strong> Tunjukkan invoice pembelian ini ke kasir agar pembelian mu dapat diproses oleh pihak Apotek Diva, Terimakasih 🙏
        </div>
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Invoice #<?= $id ?></h1>
                <p class="text-sm text-gray-500">Tanggal: <?= $trans['tanggal'] ?></p>
                <p class="text-sm text-gray-500">Status: 
                    <span class="px-2 py-1 bg-yellow-200 text-yellow-800 rounded text-xs"><?= $trans['status'] ?></span>
                </p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500 font-semibold">Pelanggan:</p>
                <p class="text-gray-800"><?= $trans['nama_user'] ?></p>
                <p class="text-gray-600 text-sm"><?= $trans['email'] ?></p>
            </div>
        </div>
        <table class="w-full text-sm mb-6">
            <thead>
                <tr class="bg-gray-100 text-gray-600">
                    <th class="text-left p-2">Obat</th>
                    <th class="text-left p-2">Harga</th>
                    <th class="text-left p-2">Jumlah</th>
                    <th class="text-left p-2">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0; while($row = mysqli_fetch_assoc($details)) : ?>
                <?php $subtotal = $row['harga'] * $row['jumlah']; $total += $subtotal; ?>
                <tr class="border-b">
                    <td class="p-2"><?= $row['nama'] ?></td>
                    <td class="p-2">Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
                    <td class="p-2"><?= $row['jumlah'] ?></td>
                    <td class="p-2">Rp<?= number_format($subtotal, 0, ',', '.') ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="flex justify-end">
            <div class="w-1/2">
                <div class="flex justify-between text-gray-700 mb-1">
                    <span class="font-semibold">Metode Pembayaran:</span>
                    <span><?= $trans['metode_pembayaran'] ?></span>
                </div>
                <div class="flex justify-between text-gray-700 mb-1">
                    <span class="font-semibold">Metode Pengiriman:</span>
                    <span><?= $trans['metode_pengiriman'] ?></span>
                </div>
                <div class="flex justify-between mt-4 text-xl font-bold text-gray-900 border-t pt-4">
                    <span>Total:</span>
                    <span>Rp<?= number_format($total, 0, ',', '.') ?></span>
                </div>
            </div>
        </div>
        <?php if (strtolower($trans['metode_pengiriman']) === 'gojek' || strtolower($trans['metode_pengiriman']) === 'goshop'): ?>
        <div class="mt-8 text-center">
            <button id="btnGoShop" class="bg-green-600 text-white px-6 py-2 rounded text-lg hover:bg-green-700">Buka GoShop di Gojek</button>
            <div id="notifGoShop"></div>
        </div>
        <script>
        document.getElementById('btnGoShop').addEventListener('click', function() {
            var gojekAppUrl = 'gojek://goshop';
            var gojekWebUrl = 'https://gojek.onelink.me/NOe5/GoShop';
            var playStoreUrl = 'https://play.google.com/store/apps/details?id=com.gojek.app';
            var appStoreUrl = 'https://apps.apple.com/id/app/gojek/id944875099';
            var userAgent = navigator.userAgent || navigator.vendor || window.opera;
            var fallbackShown = false;
            var iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.src = gojekAppUrl;
            document.body.appendChild(iframe);
            setTimeout(function() {
                document.body.removeChild(iframe);
                if (!fallbackShown) {
                    fallbackShown = true;
                    var msg = 'Aplikasi Gojek tidak ditemukan. Silakan buka GoShop di browser atau install aplikasi Gojek.';
                    msg += '<br><a href="' + gojekWebUrl + '" target="_blank" class="text-blue-600 underline">Buka GoShop Web</a>';
                    if (/android/i.test(userAgent)) {
                        msg += '<br><a href="' + playStoreUrl + '" target="_blank" class="text-green-600 underline">Install Gojek di Play Store</a>';
                    } else if (/iPad|iPhone|iPod/.test(userAgent)) {
                        msg += '<br><a href="' + appStoreUrl + '" target="_blank" class="text-green-600 underline">Install Gojek di App Store</a>';
                    }
                    document.getElementById('notifGoShop').innerHTML = msg;
                }
            }, 1200);
        });
        </script>
        <?php endif; ?>
    </div>
</body>
</html>
