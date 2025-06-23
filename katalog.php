<?php
session_start();
require_once 'includes/db.php';
$conn = mysqli_connect('hopper.proxy.rlwy.net', 'root', 'kgrVBYlHaoXAsSUmoXFUpLGpRvlHfkyK', 'railway', 11750);
$obat = mysqli_query($conn, "SELECT * FROM obat");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Katalog Obat</title>
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
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

<header class="bg-white shadow p-4 flex justify-between items-center sticky top-0 z-10">
  <h1 class="text-2xl font-bold text-blue-600">Diva Pharmacy</h1>
  <nav>
    <?php if (isset($_SESSION['user_id'])): ?>
      <a href="keranjang.php" class="text-blue-500 px-4 text-[20px]">🛒 Checkout</a>
      <button id="btnRiwayat" class="mr-4 text-blue-600">Riwayat Pemesanan</button>
      <a href="login.php" class="text-red-500 text-[20px]">Logout</a>
    <?php else: ?>
      <a href="login.php" class="text-blue-500">Login</a>
    <?php endif; ?>
  </nav>
</header>

<section class="relative bg-blue-100">
  <img src="https://images.unsplash.com/photo-1642055514517-7b52288890ec?q=80&w=1974&auto=format&fit=crop" alt="Hero Obat" class="w-full h-[300px] object-cover brightness-75">
  <div class="absolute top-0 left-0 w-full h-full flex flex-col justify-center items-center text-white text-center">
    <h2 class="text-4xl md:text-5xl font-bold">Cari Obat Dengan Mudah</h2>
    <p class="mt-2 text-lg">Solusi kesehatan cepat & terpercaya</p>
  </div>
</section>

<section class="px-6 mt-6 max-w-4xl mx-auto">
  <!-- Search input -->
  <input id="searchInput" type="text" placeholder="Cari nama obat..." class="w-full p-3 rounded-md border border-gray-300 focus:outline-blue-500">

  <!-- Filter buttons -->
  <div class="mt-4 flex flex-wrap gap-2">
    <button data-type="all" class="filter-btn bg-blue-600 text-white px-3 py-1 rounded">Semua</button>
    <button data-type="alkes" class="filter-btn bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded">Alkes</button>
    <button data-type="cairan infus" class="filter-btn bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded">Cairan Infus</button>
    <button data-type="salep gel" class="filter-btn bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded">Salep Gel</button>
    <button data-type="syrup" class="filter-btn bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded">Syrup</button>
    <button data-type="tablet/kapsul sachet" class="filter-btn bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded">Tablet/Kapsul Sachet</button>
    <button data-type="tetes mata/telinga" class="filter-btn bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded">Tetes Mata/Telinga</button>
  </div>
</section>

<section class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 p-6 max-w-7xl mx-auto" id="katalog">
  <?php while($row = mysqli_fetch_assoc($obat)): ?>
    <div 
      class="obat-card cursor-pointer bg-white rounded-lg shadow hover:shadow-md transition p-4 text-center"
      onclick="bukaModal(this)"
      data-id="<?= $row['id'] ?>"
      data-nama="<?= htmlspecialchars($row['nama']) ?>"
      data-harga="<?= number_format($row['harga']) ?>"
      data-rawharga="<?= $row['harga'] ?>"
      data-stok="<?= $row['stok'] ?>"
      data-kegunaan="<?= htmlspecialchars($row['kegunaan']) ?>"
      data-gambar="uploads/<?= $row['gambar'] ?>"
      data-type="<?= htmlspecialchars(strtolower($row['type'])) ?>"
    >
      <img src="uploads/<?= $row['gambar'] ?>" alt="<?= $row['nama'] ?>" class="w-full h-32 object-cover rounded-md mb-3">
      <h3 class="text-sm font-semibold text-gray-800"><?= $row['nama'] ?></h3>
      <p class="text-blue-600 font-bold text-sm mt-1">Rp<?= number_format($row['harga']) ?></p>
    </div>
  <?php endwhile; ?>
</section>

<!-- Modal detail obat -->
<div id="modalObat" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
  <div class="bg-white px-10 py-10 rounded-lg max-w-sm w-full relative">
    <button onclick="tutupModalObat()" class="absolute top-2 right-2 text-gray-500 hover:text-red-500 text-xl">&times;</button>
    <img id="modalGambar" src="" alt="" class="w-full h-40 object-cover rounded mb-4">
    <h2 id="modalNama" class="text-xl font-bold text-gray-800 mb-1"></h2>
    <p id="modalHarga" class="text-blue-600 font-semibold mb-1"></p>
    <p id="modalKegunaan" class="text-sm text-justify text-gray-700 mb-1"></p>
    <p id="modalStok" class="text-sm text-center text-gray-600 mb-4 pt-2 font-bold"></p>
    <form id="formTambahKeranjang" class="flex gap-2 items-center" onsubmit="return tambahKeKeranjang(event)">
      <input type="hidden" name="id_obat" id="modalIdObat">
      <input type="number" name="jumlah" id="modalJumlah" value="1" min="1" class="border rounded p-1 w-16">
      <button type="submit" class="bg-blue-600 text-white px-4 py-1 rounded hover:bg-blue-700">+ Tambah ke Keranjang</button>
    </form>
    <p id="notifStok" class="text-sm text-red-500 mt-2 hidden">Stok tidak mencukupi!</p>
    <p id="notifBerhasil" class="text-sm text-green-600 mt-2 hidden">Berhasil ditambahkan ke keranjang!</p>
  </div>
</div>

<!-- Footer -->
<footer class="bg-white text-center p-4 shadow-inner mt-10">
  <p class="text-sm text-gray-500">&copy; <?= date('Y') ?> Diva Pharmacy. All rights reserved.</p>
</footer>

<!-- JS -->
<script>
  const searchInput = document.getElementById('searchInput');
  const cards = document.querySelectorAll('.obat-card');
  let currentType = 'all';

  function filterCards() {
    const keyword = searchInput.value.toLowerCase();
    cards.forEach(card => {
      const nama = card.dataset.nama.toLowerCase();
      const type = card.dataset.type;
      const matchesSearch = nama.includes(keyword);
      const matchesType = (currentType === 'all' || type === currentType);
      card.style.display = (matchesSearch && matchesType) ? 'block' : 'none';
    });
  }

  // Search by name
  searchInput.addEventListener('input', filterCards);

  // Filter by type buttons
  document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      // styling active button
      document.querySelectorAll('.filter-btn').forEach(b => b.classList.replace('bg-blue-600','bg-gray-200') && b.classList.replace('text-white','text-gray-800'));
      btn.classList.replace('bg-gray-200','bg-blue-600');
      btn.classList.replace('text-gray-800','text-white');
      currentType = btn.dataset.type;
      filterCards();
    });
  });

  // Modal detail
  let currentStok = 0;
  function bukaModal(el) {
    document.getElementById('modalIdObat').value = el.dataset.id;
    document.getElementById('modalNama').textContent = el.dataset.nama;
    document.getElementById('modalHarga').textContent = 'Rp' + el.dataset.harga;
    document.getElementById('modalKegunaan').textContent = 'Kegunaan: ' + el.dataset.kegunaan;
    document.getElementById('modalStok').textContent = 'Stok tersedia: ' + el.dataset.stok;
    document.getElementById('modalGambar').src = el.dataset.gambar;
    currentStok = parseInt(el.dataset.stok);
    document.getElementById('modalJumlah').value = 1;
    document.getElementById('notifStok').classList.add('hidden');
    document.getElementById('modalObat').classList.remove('hidden');
  }
  function tutupModalObat() {
    document.getElementById('modalObat').classList.add('hidden');
  }
  function cekStok() {
    const jumlah = parseInt(document.getElementById('modalJumlah').value);
    if (jumlah > currentStok) {
      document.getElementById('notifStok').classList.remove('hidden');
      return false;
    }
    return true;
  }

  // Tambah ke keranjang AJAX
  function tambahKeKeranjang(e) {
    e.preventDefault();
    const id_obat = document.getElementById('modalIdObat').value;
    const jumlah = document.getElementById('modalJumlah').value;
    const notifStok = document.getElementById('notifStok');
    const notifBerhasil = document.getElementById('notifBerhasil');
    notifStok.classList.add('hidden');
    notifBerhasil.classList.add('hidden');
    fetch('add_to_cart.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `id_obat=${id_obat}&jumlah=${jumlah}`
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        notifBerhasil.classList.remove('hidden');
        setTimeout(() => { notifBerhasil.classList.add('hidden'); tutupModalObat(); }, 1200);
      } else {
        notifStok.textContent = data.message;
        notifStok.classList.remove('hidden');
      }
    })
    .catch(() => {
      notifStok.textContent = 'Gagal menambah ke keranjang.';
      notifStok.classList.remove('hidden');
    });
    return false;
  }

  // Riwayat modal AJAX
  document.getElementById('btnRiwayat').addEventListener('click', function() {
    bukaModalAjax('modalRiwayat','kontenRiwayat','riwayat.php');
  });

  function bukaModalAjax(modalId, kontenId, url) {
    document.getElementById(modalId).classList.remove('hidden');
    document.getElementById(kontenId).innerHTML = 'Memuat...';
    fetch(url)
      .then(r => r.text())
      .then(html => document.getElementById(kontenId).innerHTML = html)
      .catch(() => document.getElementById(kontenId).innerHTML = 'Gagal memuat data.');
  }
</script>

<!-- Riwayat modal HTML (tetap di bawah) -->
<div id="modalRiwayat" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
  <div class="bg-white max-w-xl w-full rounded-lg p-6 relative overflow-auto max-h-[80vh]">
    <button onclick="document.getElementById('modalRiwayat').classList.add('hidden')" class="absolute top-2 right-4 text-xl text-gray-500 hover:text-red-500">&times;</button>
    <h3 class="text-lg font-semibold mb-4">Riwayat Pemesanan</h3>
    <div id="kontenRiwayat">Memuat...</div>
  </div>
</div>

</body>
</html>
