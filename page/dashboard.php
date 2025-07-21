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
    <aside class="w-64 bg-[#0D2B53] text-white flex flex-col">
      <!-- Logo dan SIMANTAP -->
      <div class="flex items-center px-6 py-6 text-xl font-bold space-x-2">
        <img src="assets/titik_tiga.png" alt="Logo" class="w-6 h-6"/>
        <span>SIMANTAP</span>
      </div>

      <!-- Tombol Dashboard -->
      
      <button class="mx-4 my-2 py-2 px-4 bg-orange-400 rounded-md text-white font-semibold hover:bg-orange-500 w-[85%] text-left"><span class="material-icons text-black">menu_book</span>
        Dashboard 
      </button>

      <!-- Menu Admin -->
      <nav class="mt-2 px-4">
          
        <h2 class="text-sm font-bold text-gray-300 mb-2">MENU UNTUK ADMIN</h2>
        <ul class="space-y-2 text-sm">
          <li><a href="tugas/detail_tugas.php" class="block py-1 hover:underline">Detail Tugas</a></li>
          <li><a href="#" class="block py-1 hover:underline">Laporan Harian</a></li>
          <li><a href="#" class="block py-1 hover:underline">Permohonan Tenggat</a></li>
          <li><a href="#" class="block py-1 hover:underline">Kinerja Pegawai</a></li>
          <li><a href="#" class="block py-1 hover:underline">Kelola Pengguna</a></li>
        </ul>
      </nav>
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

      <!-- Kartu Status -->
      <div class="grid grid-cols-4 gap-4 mt-6">
        <div class="bg-blue-600 text-white p-4 rounded shadow">
          <p class="text-sm">Total Tugas</p>
          <p class="text-2xl font-bold">123</p>
        </div>
        <div class="bg-green-600 text-white p-4 rounded shadow">
          <p class="text-sm">Tugas Selesai</p>
          <p class="text-2xl font-bold">99</p>
        </div>
        <div class="bg-yellow-500 text-white p-4 rounded shadow">
          <p class="text-sm">Tugas Berlangsung</p>
          <p class="text-2xl font-bold">123</p>
        </div>
        <div class="bg-red-600 text-white p-4 rounded shadow">
          <p class="text-sm">Tugas Terlambat</p>
          <p class="text-2xl font-bold">0</p>
        </div>
      </div>
    </main>
  </div>

</body>
</html>
