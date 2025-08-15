<?php
include '../../db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Ambil bidang dari GET
$bidang = isset($_GET['bidang']) ? mysqli_real_escape_string($conn, $_GET['bidang']) : '';

// Pagination
$limit = 10; // 10 row per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Hitung total user untuk pagination
$countQuery = "SELECT COUNT(*) as total_users FROM user";
if ($bidang !== '') {
    $countQuery .= " WHERE bidang_id = '$bidang'";
}
$countResult = mysqli_query($conn, $countQuery);
$totalUsers = mysqli_fetch_assoc($countResult)['total_users'];
$totalPages = ceil($totalUsers / $limit);

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
        LEFT JOIN task t ON u.id = t.assigned_to";

// Jika ada filter bidang
if ($bidang !== '') {
    $query .= " WHERE u.bidang_id = '$bidang'";
}

// Tambahkan GROUP BY dan LIMIT untuk pagination
$query .= " GROUP BY u.id, u.nama, u.role ORDER BY u.nama ASC LIMIT $limit OFFSET $offset";

// Jalankan query
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query gagal: " . mysqli_error($conn));
}
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
    /* Supaya kolom Nama tetap 1 baris */
/* Supaya Nama dan Jabatan tetap 1 baris */
td.nama-col, td.jabatan-col {
  max-width: 200px; /* atur sesuai kebutuhan */
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
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
    <thead class="bg-blue-50 text-gray-800 font-semibold">
        <tr>
            <th class="border border-gray-300 px-4 py-2">No</th>
            <th class="border border-gray-300 px-4 py-2">Nama</th>
            <th class="border border-gray-300 px-4 py-2">Jabatan</th>
            <th class="border border-gray-300 px-4 py-2">Tugas Selesai</th>
            <th class="border border-gray-300 px-4 py-2">Sedang Dikerjakan</th>
            <th class="border border-gray-300 px-4 py-2">Terlambat</th>
            <th class="border border-gray-300 px-4 py-2">Total Tugas</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = $offset + 1; while($row = mysqli_fetch_assoc($result)): ?>
            <tr class="text-center hover:bg-blue-100 <?= $no % 2 == 0 ? 'bg-gray-50' : 'bg-white' ?>">
                <td class="border border-gray-300 px-4 py-2"><?= $no++ ?></td>
               <td class="border border-gray-300 px-4 py-2 nama-col"><?= htmlspecialchars($row['nama']) ?></td>
<td class="border border-gray-300 px-4 py-2 jabatan-col"><?= htmlspecialchars($row['role']) ?></td>

                <td class="border border-gray-300 px-4 py-2 text-green-600 font-bold"><?= $row['selesai'] ?></td>
                <td class="border border-gray-300 px-4 py-2 text-blue-600 font-bold"><?= $row['dikerjakan'] ?></td>
                <td class="border border-gray-300 px-4 py-2 text-red-600 font-bold"><?= $row['terlambat'] ?></td>
                <td class="border border-gray-300 px-4 py-2 font-bold"><?= $row['total'] ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<!-- Pagination -->
<div class="mt-4 flex justify-center space-x-1">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?page=<?= $i ?><?= $bidang ? '&bidang='.$bidang : '' ?>"
           class="px-3 py-1 border border-gray-300 rounded 
                  <?= $i == $page ? 'bg-blue-500 text-white font-bold' : 'bg-white text-gray-700 hover:bg-blue-100' ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>
</div>


        </div>
    </main>

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
