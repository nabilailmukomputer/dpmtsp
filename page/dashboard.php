<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin - SIMANTAP</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex min-h-screen bg-gray-100">

  <!-- Sidebar -->
  <aside class="w-64 bg-blue-800 text-white p-4 space-y-4">
    <h1 class="text-2xl font-bold mb-6">SIMANTAP</h1>
    <nav class="space-y-2">
      <a href="#" class="block py-2 px-4 hover:bg-blue-700 rounded">Dashboard</a>
      <a href="#" class="block py-2 px-4 hover:bg-blue-700 rounded">Data User</a>
      <a href="#" class="block py-2 px-4 hover:bg-blue-700 rounded">Data Tugas</a>
    </nav>
    <a href="../logout.php" class="block mt-6 text-sm text-red-300 hover:text-white">Logout</a>
  </aside>

  <!-- Content -->
  <main class="flex-1 p-6">
    <h2 class="text-2xl font-bold text-gray-700 mb-4">Selamat Datang, <?= $_SESSION['user']['username'] ?>!</h2>
    <div class="bg-white p-4 rounded shadow-md">
      <p>Ini adalah dashboard admin sistem monitoring tugas pegawai (SIMANTAP).</p>
    </div>
  </main>

</body>
</html>
