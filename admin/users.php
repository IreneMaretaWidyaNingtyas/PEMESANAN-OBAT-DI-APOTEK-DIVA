<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

$currentPage = basename($_SERVER['PHP_SELF']);
// Ambil semua user, baik user maupun admin
$conn = mysqli_connect('hopper.proxy.rlwy.net', 'root', 'kgrVBYlHaoXAsSUmoXFUpLGpRvlHfkyK', 'railway', 11750);
$users = mysqli_query($conn, "SELECT * FROM users");

// ini codingan untuk menambah User
if (isset($_POST['tambah'])) {
    $nama = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    mysqli_query($conn, "INSERT INTO users (nama, email, password, role) VALUES ('$nama', '$email', '$password', 'user')");
    header("Location: users.php");
    exit;
}

// ini codingan untuk edit User
// Edit user juga update role dan password jika diisi
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nama = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = isset($_POST['password']) && $_POST['password'] !== '' ? password_hash($_POST['password'], PASSWORD_DEFAULT) : false;
    if ($password) {
        mysqli_query($conn, "UPDATE users SET nama='$nama', email='$email', role='$role', password='$password' WHERE id=$id");
    } else {
        mysqli_query($conn, "UPDATE users SET nama='$nama', email='$email', role='$role' WHERE id=$id");
    }
    header("Location: users.php");
    exit;
}

// ini codingan untuk menghapus User
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM users WHERE id=$id");
    header("Location: users.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola User</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
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
        <h1 class="text-2xl font-bold mb-4">Kelola Pengguna</h1>

        <form method="POST" class="bg-white p-4 rounded shadow mb-6 flex gap-4">
            <input type="text" name="username" placeholder="Username" required class="border p-2 rounded w-1/4">
            <input type="email" name="email" placeholder="Email" required class="border p-2 rounded w-1/4">
            <input type="password" name="password" placeholder="Password" required class="border p-2 rounded w-1/4">
            <button type="submit" name="tambah" class="bg-green-600 text-white px-4 py-2 rounded">Tambah</button>
        </form>

        <div class="bg-white shadow rounded overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Username</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php while ($user = mysqli_fetch_assoc($users)) : ?>
                        <tr>
                            <td class="px-6 py-4 text-gray-700"><?= htmlspecialchars($user['nama']) ?></td>
                            <td class="px-6 py-4 text-gray-700"><?= htmlspecialchars($user['email']) ?></td>
                            <td class="px-6 py-4 text-gray-700"><?= htmlspecialchars($user['role']) ?></td>
                            <td class="px-6 py-4">
                                <button
                                    onclick="openEditModal(<?= $user['id'] ?>, '<?= htmlspecialchars($user['nama']) ?>', '<?= htmlspecialchars($user['email']) ?>', '<?= htmlspecialchars($user['role']) ?>')"
                                    class="text-white-500 bg-yellow-500 hover:bg-yellow-600 px-4 py-1 rounded  mr-2">Edit</button>
                                <button
                                    onclick="openDeleteModal(<?= $user['id'] ?>)"
                                    class="text-white-600 bg-red-500 hover:bg-red-600 px-4 py-1 rounded">Hapus</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<!-- ini codingan untuk menampilkan pop-up edit -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h2 class="text-lg font-semibold mb-4">Edit Pengguna</h2>
        <form method="POST">
            <input type="hidden" name="id" id="editId">
            <div class="mb-3">
                <label class="block mb-1 text-sm text-gray-600">Username</label>
                <input type="text" name="username" id="editUsername" class="border p-2 rounded w-full" required>
            </div>
            <div class="mb-3">
                <label class="block mb-1 text-sm text-gray-600">Role</label>
                <select name="role" id="editRole" class="border p-2 rounded w-full" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block mb-1 text-sm text-gray-600">Email</label>
                <input type="email" name="email" id="editEmail" class="border p-2 rounded w-full" required>
            </div>
            <div class="mb-3">
                <label class="block mb-1 text-sm text-gray-600">Password (kosongkan jika tidak ingin mengubah)</label>
                <input type="password" name="password" id="editPassword" class="border p-2 rounded w-full">
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeEditModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                <button type="submit" name="edit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- ini codingan untuk menampilkan pop-up Konfirmasi Hapus -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-sm text-center">
        <h2 class="text-lg font-semibold mb-4 text-red-600">Konfirmasi Hapus</h2>
        <p class="mb-6 text-gray-700">Yakin ingin menghapus user ini?</p>
        <div class="flex justify-center gap-4">
            <button onclick="closeDeleteModal()" class="bg-gray-400 text-white px-4 py-2 rounded">Batal</button>
            <a id="confirmDeleteBtn" href="#" class="bg-red-600 text-white px-4 py-2 rounded">Hapus</a>
        </div>
    </div>
</div>

<script>
    function openEditModal(id, username, email, role) {
        document.getElementById('editId').value = id;
        document.getElementById('editUsername').value = username;
        document.getElementById('editEmail').value = email;
        document.getElementById('editRole').value = role;
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editModal').classList.add('flex');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('editModal').classList.remove('flex');
    }

    function openDeleteModal(id) {
        const deleteBtn = document.getElementById('confirmDeleteBtn');
        deleteBtn.href = `?hapus=${id}`;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.getElementById('deleteModal').classList.remove('flex');
    }
</script>

</body>
</html>
