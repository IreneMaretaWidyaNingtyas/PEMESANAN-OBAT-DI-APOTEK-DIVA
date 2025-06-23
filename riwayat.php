<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    die("<p class='text-red-500'>Silakan login terlebih dahulu.</p>");
}

$user_id = $_SESSION['user_id'];

$riwayat = mysqli_query($conn, "
    SELECT t.id, t.tanggal, t.status, 
           SUM(d.jumlah * o.harga) AS total
    FROM transaksi t
    JOIN transaksi_detail d ON t.id = d.transaksi_id
    JOIN obat o ON o.id = d.obat_id
    WHERE t.user_id = $user_id
    GROUP BY t.id, t.tanggal, t.status
    ORDER BY t.tanggal DESC
");
?>

<table class="w-full table-auto border-collapse">
    <thead>
        <tr class="bg-gray-200 text-gray-700">
            <th class="p-2">ID</th>
            <th class="p-2">Tanggal</th>
            <th class="p-2">Status</th>
            <th class="p-2">Total</th>
            <th class="p-2">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (mysqli_num_rows($riwayat) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($riwayat)) : ?>
            <tr class="border-t">
                <td class="p-2 text-center"><?= $row['id'] ?></td>
                <td class="p-2"><?= $row['tanggal'] ?></td>
                <td class="p-2"><?= $row['status'] ?></td>
                <td class="p-2">Rp<?= number_format($row['total'], 0, ',', '.') ?></td>
                <td class="p-2">
                    <a href="invoice.php?id=<?= $row['id'] ?>" target="_blank" class="text-blue-600 underline">Lihat Invoice</a>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="text-center text-gray-500 p-4">Belum ada riwayat pemesanan.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
