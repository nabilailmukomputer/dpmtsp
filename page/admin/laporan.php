<?php
include '../../db.php';
session_start();

// Ambil semua laporan harian
$query = "SELECT dr.*, u.nama AS nama_pegawai 
          FROM daily_report dr 
          JOIN user u ON dr.user_id = u.id 
          ORDER BY dr.tanggal DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Laporan Harian Pegawai</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=search" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
</head>

<body class="bg-gray-100">
    <div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-[#0D2B53] text-white flex flex-col min-h-screen">
        <!-- Logo dan SIMANTAP -->
        <div class="flex items-center px-6 py-6 text-xl font-bold space-x-2">
            <img src="assets/titik_tiga.png" alt="Logo" class="w-6 h-6" />
            <span>SIMANTAP</span>
        </div>

        <!-- Menu Admin -->
        <nav class="mt-2 px-4">
            <ul class="space-y-2 text-sm">
                <li>
                    <a href="dashboard.php"
                        class="block py-2.5 px-2.5 font-bold rounded hover:bg-orange-500 hover:text-white transition duration-300 hover:translate-x-1 mb-2">
                        <span class="flex items-center gap-2">
                            <span class="material-icons text-[#F7EDED]">menu_book</span>
                            Dashboard
                        </span>
                    </a>
                </li>
            </ul>
            <h2 class="text-[8px] font-bold text-gray-300 mb-2 ml-2">MENU UNTUK ADMIN</h2>
            <ul class="space-y-2 text-sm">
                <li>
                    <a href="detail.php"
                        class="block py-2 px-2 rounded hover:bg-orange-500 hover:text-white transition duration-300 hover:translate-x-1">Detail
                        Tugas</a>
                </li>
                <li>
                    <a href="laporan.php"
                        class="block py-2 px-2 rounded hover:bg-orange-500 hover:text-white transition duration-300 hover:translate-x-1">Laporan
                        Harian</a>
                </li>
                <li>
                    <a href="tenggat.php"
                        class="block py-2 px-2 rounded hover:bg-orange-500 hover:text-white transition duration-300 hover:translate-x-1">Permohonan
                        Tenggat</a>
                </li>
                <li>
                    <a href="kinerja_pegawai.php"
                        class="block py-2 px-2 rounded hover:bg-orange-500 hover:text-white transition duration-300 hover:translate-x-1">Kinerja
                        Pegawai</a>
                </li>
                <li>
                    <a href="kelola_admin.php"
                        class="block py-2 px-2 rounded hover:bg-orange-500 hover:text-white transition duration-300 hover:translate-x-1">Kelola
                        Pengguna</a>
                </li>
            </ul>
        </nav>

        <div class="mt-auto px-4 py-4">
            <a href="../logout.php"
                class="flex items-center gap-2 text-white-500 hover:underline text-sm transition duration-300">
                <span class="material-icons">logout</span>
                <span>Logout</span>
            </a>
        </div>
    </aside>

    <main class="flex-1 p-6 overflow-auto">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-4 md:mb-0">Laporan Harian Pegawai</h1>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded shadow-md">
                <thead class="bg-gray-200 text-gray-600">
                    <tr>
                        <th class="py-3 px-4 border">Nama Pegawai</th>
                        <th class="py-3 px-4 border">Tanggal</th>
                        <th class="py-3 px-4 border">Laporan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr class="hover:bg-gray-100">
                        <td class="py-2 px-4 border"><?= htmlspecialchars($row['nama_pegawai']) ?></td>
                        <td class="py-2 px-4 border"><?= htmlspecialchars($row['tanggal']) ?></td>
                        <td class="py-2 px-4 border"><?= nl2br(htmlspecialchars($row['laporan'])) ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
