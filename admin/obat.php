<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

$currentPage = basename($_SERVER['PHP_SELF']);
$obat = mysqli_query($conn, "SELECT * FROM obat");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Obat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">

<div class="flex min-h-screen">
    <aside class="w-64 bg-white shadow-md">
        <div class="p-6 text-blue-700 font-bold text-xl">Admin Diva</div>
        <nav class="mt-6">
            <a href="dashboard.php" class="block px-6 py-3 <?= $currentPage === 'dashboard.php' ? 'bg-blue-200' : 'hover:bg-blue-100' ?>">Dashboard</a>
            <a href="obat.php" class="block px-6 py-3 <?= $currentPage === 'obat.php' ? 'bg-blue-200' : 'hover:bg-blue-100' ?>">Kelola Obat</a>
            <a href="users.php" class="block px-6 py-3 <?= $currentPage === 'users.php' ? 'bg-blue-200' : 'hover:bg-blue-100' ?>">Kelola User</a>
            <a href="invoices.php" class="block px-6 py-3 <?= $currentPage === 'invoices.php' ? 'bg-blue-200' : 'hover:bg-blue-100' ?>">Invoices</a>
            <a href="../login.php" class="block px-6 py-3 text-red-500 hover:bg-red-100">Logout</a>
        </nav>
    </aside>

    <main class="flex-1 p-8">
        <h2 class="text-2xl font-bold mb-4">Kelola Data Obat</h2>

        <div class="mb-6">
            <a href="tambah_obat.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">+ Tambah Obat</a>
        </div>

        <div class="mb-4">
            <input type="text" id="searchInput" placeholder="Cari nama, kegunaan atau jenis obat..." class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Kegunaan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>  <!-- kolom type -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gambar</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php while ($row = mysqli_fetch_assoc($obat)): ?>
                        <tr>
                            <td class="px-6 py-4 text-gray-700"><?= htmlspecialchars($row['nama']) ?></td>
                            <td class="px-6 py-4 text-justify text-gray-700"><?= htmlspecialchars($row['kegunaan']) ?></td>
                            <td class="px-6 py-4 text-gray-700">Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
                            <td class="px-6 py-4 text-gray-700"><?= $row['stok'] ?></td>
                            <td class="px-6 py-4 text-gray-700"><?= htmlspecialchars(ucwords($row['type'])) ?></td>  <!-- tampilkan type -->
                            <td class="px-6 py-4">
                                <?php if (!empty($row['gambar'])): ?>
                                    <img src="../uploads/<?= htmlspecialchars($row['gambar']) ?>" width="80" alt="<?= htmlspecialchars($row['nama']) ?>" class="rounded shadow">
                                <?php else: ?>
                                    <span class="text-sm text-gray-500 italic">Tidak ada gambar</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 space-x-20">
                                <span>
                                  <button onclick="openEditModal(<?= $row['id'] ?>)" class="text-white bg-yellow-500 hover:bg-yellow-600 px-4 py-1 rounded text-xs">Edit</button>
                                  <br><br>
                                  <button class="text-white bg-red-600 hover:bg-red-700 px-3 py-1 rounded text-xs" onclick="confirmDelete(<?= $row['id'] ?>)">Hapus</button>
                                </span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<!-- pop-up edit -->
<div id="editModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white p-6 rounded shadow-lg w-96">
        <h3 class="text-xl font-semibold mb-4">Edit Obat</h3>
        <form id="editForm" method="POST">
            <input type="hidden" id="editId" name="id" />
            <div class="mb-4">
                <label for="editNama" class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" id="editNama" name="nama" class="mt-1 p-2 w-full border border-gray-300 rounded" />
            </div>
            <div class="mb-4">
                <label for="editKegunaan" class="block text-sm font-medium text-gray-700">Kegunaan</label>
                <textarea name="kegunaan" id="editKegunaan" class="mt-1 p-2 w-full border border-gray-300 rounded"></textarea>
            </div>
            <div class="mb-4">
                <label for="editHarga" class="block text-sm font-medium text-gray-700">Harga</label>
                <input type="number" id="editHarga" name="harga" class="mt-1 p-2 w-full border border-gray-300 rounded" />
            </div>
            <div class="mb-4">
                <label for="editStok" class="block text-sm font-medium text-gray-700">Stok</label>
                <input type="number" id="editStok" name="stok" class="mt-1 p-2 w-full border border-gray-300 rounded" />
            </div>
            <div class="mb-4">
                <label for="editType" class="block text-sm font-medium text-gray-700">Jenis</label>
                <select id="editType" name="type" class="mt-1 p-2 w-full border border-gray-300 rounded">
                    <option value="alkes">Alkes</option>
                    <option value="cairan infus">Cairan Infus</option>
                    <option value="salep gel">Salep Gel</option>
                    <option value="syrup">Syrup</option>
                    <option value="tablet/kapsul sachet">Tablet/Kapsul Sachet</option>
                    <option value="tetes mata/telinga">Tetes Mata/Telinga</option>
                </select>
            </div>
            <div class="flex justify-end space-x-4">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 text-black rounded">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- pop-up konfirmasi edit -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog"><div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmationModalLabel">Konfirmasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Obat berhasil diperbarui! Apakah Anda ingin kembali ke halaman utama?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="button" id="confirmBtn" class="btn btn-primary">Ya, Kembali</button>
      </div>
  </div></div>
</div>

<!-- pop-up hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog"><div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Apakah Anda yakin ingin menghapus obat ini?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <a href="#" id="deleteConfirmBtn" class="btn btn-danger">Ya, Hapus</a>
      </div>
  </div></div>
</div>

<script>
    function openEditModal(id) {
        fetch('get_obat.php?id=' + id)
            .then(res => res.json())
            .then(data => {
                document.getElementById('editId').value = data.id;
                document.getElementById('editNama').value = data.nama;
                document.getElementById('editKegunaan').value = data.kegunaan;
                document.getElementById('editHarga').value = data.harga;
                document.getElementById('editStok').value = data.stok;
                document.getElementById('editType').value = data.type;
                document.getElementById('editModal').classList.remove('hidden');
            });
    }
    function closeModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
    document.getElementById('editForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch('update_obat.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                let cm = new bootstrap.Modal(document.getElementById('confirmationModal'));
                cm.show();
                document.getElementById('confirmBtn').onclick = () => {
                    cm.hide();
                    location.reload();
                };
            } else {
                alert('Gagal memperbarui obat!');
            }
        });
    });
    document.getElementById('searchInput').addEventListener('input', function() {
        const term = this.value.toLowerCase();
        document.querySelectorAll('tbody tr').forEach(row => {
            const nama    = row.children[0].textContent.toLowerCase();
            const kegunaan= row.children[1].textContent.toLowerCase();
            const type    = row.children[4].textContent.toLowerCase();
            row.style.display = (nama.includes(term) || kegunaan.includes(term) || type.includes(term)) ? '' : 'none';
        });
    });
    function confirmDelete(id) {
        const d = new bootstrap.Modal(document.getElementById('deleteModal'));
        document.getElementById('deleteConfirmBtn').href = 'hapus_obat.php?id=' + id;
        d.show();
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
