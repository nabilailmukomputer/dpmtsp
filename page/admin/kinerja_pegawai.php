<?php
include '../../db.php';
session_start();

if (!isset($_SESSION['user_id']) ) {
    // Jika belum, redirect ke halaman login
    header('Location: ../login.php');
    exit;
}
// Ambil bidang dari GET
$bidang = isset($_GET['bidang']) ? mysqli_real_escape_string($conn, $_GET['bidang']) : '';

// Base query
$query = "SELECT
        u.id,
        u.nama,
        u.role,
        SUM(CASE WHEN t.status = 'selesai' THEN 1 ELSE 0 END) AS selesai,
        SUM(CASE WHEN t.status = 'dikerjakan' THEN 1 ELSE 0 END) AS dikerjakan,
        SUM(CASE WHEN t.status = 'terlambat' THEN 1 ELSE 0 END) AS terlambat,
        COUNT(t.id) AS total
    FROM user u
    LEFT JOIN task t ON u.nama = t.assigned_to AND u.role = t.role_user
";

// Jika ada filter bidang
if ($bidang !== '') {
    $query .= " WHERE u.bidang_id = '$bidang'";
}

// Tambahkan GROUP BY & ORDER BY
$query .= " GROUP BY u.id, u.nama, u.role ORDER BY u.nama ASC";

$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SIMANTAP Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
      <!-- Logo dan SIMANTAP -->
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

        <!-- Main Content -->
    <main class="flex-1 p-6">

        <div class="ml-2 mt-4 w-full p-4">
            <h1 class="text-2xl font-bold mb-4">Kinerja Pegawai</h1>
            
            <table class="w-full table-auto border border-gray-300 rounded shadow-md">
                <thead class="bg-gray-100 text-gray-800 font-semibold">
                    <tr>
                        <th class="border px-4 py-2">No</th>
                        <th class="border px-4 py-2">Nama</th>
                        <th class="border px-4 py-2">Jabatan</th>
                        <th class="border px-4 py-2">Tugas Selesai</th>
                        <th class="border px-4 py-2">Sedang Dikerjakan</th>
                        <th class="border px-4 py-2">Terlambat</th>
                        <th class="border px-4 py-2">Total Tugas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while($row = mysqli_fetch_assoc($result)): ?>
                        <tr class="text-center">
                            <td class="border px-4 py-2"><?= $no++ ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($row['nama']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($row['role']) ?></td>
                            <td class="border px-4 py-2 text-green-600 font-bold"><?= $row['selesai'] ?></td>
                            <td class="border px-4 py-2 text-blue-600 font-bold"><?= $row['dikerjakan'] ?></td>
                            <td class="border px-4 py-2 text-red-600 font-bold"><?= $row['terlambat'] ?></td>
                            <td class="border px-4 py-2 font-bold"><?= $row['total'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
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
