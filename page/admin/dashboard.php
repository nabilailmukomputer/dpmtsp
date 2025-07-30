<?php
// Ambil koneksi database
include '../../db.php';


// Total semua tugas
$result = mysqli_query($conn, "SELECT COUNT(*) AS status FROM task");
$data = mysqli_fetch_assoc($result);
$totalTugas = $data['status'];

// Total tugas selesai
$result = mysqli_query($conn, "SELECT COUNT(*) AS status FROM task WHERE status = 'selesai'");
$data = mysqli_fetch_assoc($result);
$Selesai = $data['status'];

// Total tugas berlangsung
$result = mysqli_query($conn, "SELECT COUNT(*) AS status FROM task WHERE status = 'dikerjakan'");
$data = mysqli_fetch_assoc($result);
$dikerjakan = $data['status'];

// total tugas terlambat 
$result = mysqli_query($conn, "SELECT COUNT(*) AS status FROM task WHERE status = 'terlambat'");
$data = mysqli_fetch_assoc($result);
$terlambat = $data['status'];
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
      <!-- Header -->
      <div class="flex justify-between items-center bg-gray-200 py-3 px-6 rounded-md mb-5 shadow-md">
        <div class="flex items-center space-x-2">
          <span class="material-icons text-black">menu_book</span>
          <h1 class="text-xl font-semibold">Dashboard - Admin</h1>
        </div>
        <div class="flex items-center">
          <span class="mr-2 font-medium">Users</span>
          <img src="../../assets/lala.jpeg" alt="User" class="w-8 h-8 rounded-full object-cover"/>
        </div>
      </div>

      <!-- Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
        <!-- Total Tugas -->
        <div class="bg-blue-600 text-white rounded-xl shadow-lg p-6 cursor-pointer hover:scale-105 transition"
             onclick="window.location.href='detail_tugas.php?status=all'">
          <h2 class="text-lg font-semibold">Total Tugas</h2>
          <p class="text-3xl font-bold mt-2"><?= $totalTugas; ?></p>
        </div>

        <!-- Tugas Selesai -->
        <div class="bg-green-600 text-white rounded-xl shadow-lg p-6 cursor-pointer hover:scale-105 transition"
             onclick="window.location.href='detail_tugas.php?status=selesai'">
          <h2 class="text-lg font-semibold">Tugas Selesai</h2>
          <p class="text-3xl font-bold mt-2"><?= $Selesai; ?></p>
        </div>

        <!-- Tugas Berlangsung -->
        <div class="bg-yellow-500 text-white rounded-xl shadow-lg p-6 cursor-pointer hover:scale-105 transition"
             onclick="window.location.href='detail_tugas.php?status=dikerjakan'">
          <h2 class="text-lg font-semibold">Tugas Berlangsung</h2>
          <p class="text-3xl font-bold mt-2"><?= $dikerjakan; ?></p>
        </div>

        <!-- Tugas Terlambat -->
        <div class="bg-red-600 text-white rounded-xl shadow-lg p-6 cursor-pointer hover:scale-105 transition"
             onclick="window.location.href='detail_tugas.php?status=terlambat'">
          <h2 class="text-lg font-semibold">Tugas Terlambat</h2>
          <p class="text-3xl font-bold mt-2"><?= $terlambat; ?></p>
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
