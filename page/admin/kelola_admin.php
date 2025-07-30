<?php
include '../../db.php';
session_start();
if (!isset($_SESSION['user_id']) ) {
    // Jika belum, redirect ke halaman login
    header('Location: ../login.php');
    exit;
}
// Hapus admin
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM user WHERE id=$id");
    header('Location: kelola_admin.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
      <style>
    body {
      font-family: 'Segoe UI', sans-serif;
    }
    .sidebar-collapsed {
      width: 80px !important;
    }
    .sidebar-collapsed .menu-text,
    .sidebar-collapsed h2 {
      display: none;
    }
  </style>

</head>

<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
    <aside id="sidebar" class="w-64 bg-[#0D2B53] text-white flex flex-col min-h-screen transition-all duration-300">
      <div class="flex items-center justify-between px-6 py-6 text-xl font-bold">
        <div class="flex items-center space-x-2">
                <!-- <img src="../../assets/s.png" alt="Logo" class="w-6 h-6" /> -->
                <span class="menu-text">SIMANTAP</span>
            </div>

                    <!-- Tombol Toggle -->
        <button id="toggle-btn" class="text-white focus:outline-none">
          <span class="material-icons">menu</span>
        </button>
      </div>

     <!-- Menu -->
      <nav class="mt-2 px-4">
        <ul class="space-y-2 text-sm">
          <li>
            <a href="dashboard.php"
              class="flex items-center gap-2 py-2.5 px-2.5 font-bold rounded hover:bg-orange-500 transition duration-300">
              <span class="material-icons text-[#F7EDED]">menu_book</span>
              <span class="menu-text">Dashboard</span>
            </a>
          </li>
        </ul>
        <h2 class="text-[8px] font-bold text-gray-300 mb-2 ml-2">MENU UNTUK ADMIN</h2>
        <ul class="space-y-2 text-sm">
          <li>
            <a href="detail.php" class="flex items-center gap-2 py-1 px-2 rounded hover:bg-orange-500 transition">
              <span class="material-icons">assignment</span>
              <span class="menu-text">Detail Tugas</span>
            </a>
          </li>
          <li>
            <a href="laporan.php" class="flex items-center gap-2 py-1 px-2 rounded hover:bg-orange-500 transition">
              <span class="material-icons">description</span>
              <span class="menu-text">Laporan Harian</span>
            </a>
          </li>
          <li>
            <a href="tenggat.php" class="flex items-center gap-2 py-1 px-2 rounded hover:bg-orange-500 transition">
              <span class="material-icons">schedule</span>
              <span class="menu-text">Permohonan Tenggat</span>
            </a>
          </li>
          <li>
            <a href="kinerja_pegawai.php" class="flex items-center gap-2 py-1 px-2 rounded hover:bg-orange-500 transition">
              <span class="material-icons">bar_chart</span>
              <span class="menu-text">Kinerja Pegawai</span>
            </a>
          </li>
          <li>
            <a href="kelola_admin.php" class="flex items-center gap-2 py-1 px-2 rounded hover:bg-orange-500 transition">
              <span class="material-icons">manage_accounts</span>
              <span class="menu-text">Kelola Pengguna</span>
            </a>
          </li>
          <li>
            <a href="kelola_bidang.php" class="flex items-center gap-2 py-1 px-2 rounded hover:bg-orange-500 transition">
              <span class="material-icons">apartment</span>
              <span class="menu-text">Kelola Bidang</span>
            </a>
          </li>
        </ul>
      </nav>

      <div class="mt-auto px-4 py-4">
        <a href="../logout.php" class="flex items-center gap-2 text-sm hover:underline">
          <span class="material-icons">logout</span>
          <span class="menu-text">Logout</span>
        </a>
      </div>
    </aside>

        <!-- Konten -->
        <main class="flex-1 overflow-auto">
            <!-- Header -->
            <nav class="bg-blue-600 py-4 px-8 shadow-md">
                <h1 class="text-2xl md:text-3xl font-bold text-white">Kelola Pengguna</h1>
            </nav>

            <div class="p-6">
                <!-- Search dan Tambah -->
                <div class="flex justify-between items-center mb-4">
                    <a href="form_tambah_admin.php"
                        class="bg-green-500 text-white px-4 py-2 rounded shadow hover:bg-green-600 transition">+
                        Tambah Pengguna</a>
                    <input type="text" placeholder="Cari pengguna..."
                        class="border rounded px-4 py-2 w-64 focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>

                <!-- Table -->
                <div class="overflow-x-auto bg-white shadow rounded-lg p-4">
                    <table class="w-full table-auto border-collapse">
                        <thead class="bg-gray-200 text-gray-700">
                            <tr>
                                <th class="px-4 py-2 text-left">No</th>
                                <th class="px-4 py-2 text-left">Username</th>
                                <th class="px-4 py-2 text-left">Password</th>
                                <th class="px-4 py-2 text-left">Role</th>
                                <th class="px-4 py-2 text-left">Bidang</th>
                                <th class="px-4 py-2 text-left">NIP</th>
                                <th class="px-4 py-2 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $query = mysqli_query($conn, "SELECT * FROM user");
                            while ($data = mysqli_fetch_array($query)) :
                            ?>
                            <tr class="border-b hover:bg-gray-100">
                                <td class="px-4 py-2"><?= $no++ ?></td>
                                <td class="px-4 py-2"><?= $data['nama'] ?></td>
                                <td class="px-4 py-2"><?= $data['password'] ?></td>
                                <td class="px-4 py-2"><?= $data['role'] ?></td>
                                <td class="px-4 py-2"><?= $data['bidang_id'] ?></td>
                                <td class="px-4 py-2"><?= $data['NIP'] ?></td>
                                <td class="px-4 py-2 text-center space-x-2">
                                    <a href="form_edit_admin.php?id=<?= $data['id'] ?>"
                                        class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition">Edit</a>
                                    <a href="?hapus=<?= $data['id'] ?>"
                                        onclick="return confirm('Yakin hapus admin ini?')"
                                        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">Hapus</a>
                                </td>
                            </tr>
                            <?php endwhile ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
      <!-- JavaScript untuk Toggle Sidebar -->
  <script>
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggle-btn');

    toggleBtn.addEventListener('click', () => {
      sidebar.classList.toggle('sidebar-collapsed');
    });
  </script>
</body>

</html>
