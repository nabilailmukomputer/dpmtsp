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
  </style>
</head>
<body class="bg-[#F5F5F5]">

  <div class="flex h-screen">
    <!-- Sidebar -->
   <aside class="w-64 bg-[#0D2B53] text-white flex flex-col min-h-screen">
      <!-- Logo dan SIMANTAP -->
      <div class="flex items-center px-6 py-6 text-xl font-bold space-x-2">
        <img src="assets/titik_tiga.png" alt="Logo" class="w-6 h-6"/>
        <span>SIMANTAP</span>
      </div>

      <!-- Tombol Dashboard -->
      <button class="flex items-center gap-2 mx-4 my-2 py-2 px-4 rounded-md text-white font-medium hover:bg-orange-500 w-[85%] text-left transition duration-300 hover:translate-x-1 hover:bg-opacity-90">
        <span class="material-icons text-black">menu_book</span>
        <span class="text-white">Dashboard</span>
      </button>

      <!-- Menu Admin -->
      <nav class="mt-2 px-4">
        <h2 class="text-sm font-bold text-gray-300 mb-2">MENU UNTUK ADMIN</h2>
        <ul class="space-y-2 text-sm">
          <li>
            <a href="detail.php" class="block py-1 px-2 rounded hover:bg-orange-500 hover:text-white transition duration-300 hover:translate-x-1">Detail Tugas</a>
          </li>
          <li>
            <a href="laporan.php" class="block py-1 px-2 rounded hover:bg-orange-500 hover:text-white transition duration-300 hover:translate-x-1">Laporan Harian</a>
          </li>
          <li>
            <a href="tenggat.php" class="block py-1 px-2 rounded hover:bg-orange-500 hover:text-white transition duration-300 hover:translate-x-1">Permohonan Tenggat</a>
          </li>
          <li>
            <a href="kinerja_pegawai.php" class="block py-1 px-2 rounded hover:bg-orange-500 hover:text-white transition duration-300 hover:translate-x-1">Kinerja Pegawai</a>
          </li>
          <li>
            <a href="kelola_admin.php" class="block py-1 px-2 rounded hover:bg-orange-500 hover:text-white transition duration-300 hover:translate-x-1">Kelola Pengguna</a>
          </li>
        </ul>
      </nav>

       <div class="mt-auto px-4 py-4">
        <a href="../logout.php" class="flex items-center gap-2 text-white-500 hover:underline text-sm transition duration-300">
          <span class="material-icons">logout</span>
          <span>Logout</span>
        </a>
      </div>

    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6">
      <!-- Header -->
      <div class="flex justify-between items-center bg-gray-200 py-3 px-6 rounded-md">
        <div class="flex items-center space-x-2">
          <span class="material-icons text-black">menu_book</span>
          <h1 class="text-xl font-semibold">Dashboard - Admin</h1>
        </div>
      
        <div class="flex items-center">
          <span class="mr-2 font-medium">Users</span>
          <img src="assets/lala/png" alt="User" class="w-8 h-8 rounded-full object-cover"/>
        </div>
      </div>

      <!-- Total Tugas -->
<div class="bg-blue-600 text-white p-2 rounded shadow-md">
    <p class="text-lg">Total Tugas</p>
    <h2 class="text-3xl font-bold"><?= $totalTugas ?></h2>
</div>

<!-- Tugas Selesai -->
<div class="bg-green-600 text-white p-2 rounded shadow-md">
    <p class="text-lg">Tugas Selesai</p>
    <h2 class="text-3xl font-bold"><?= $Selesai ?></h2>
</div>

<!-- Tugas Berlangsung -->
<div class="bg-yellow-500 text-white p-2 rounded shadow-md">
    <p class="text-lg">Tugas Berlangsung</p>
    <h2 class="text-3xl font-bold"><?= $dikerjakan ?></h2>
</div>


</body>
</html>
