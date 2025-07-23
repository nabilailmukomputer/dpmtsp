<?php
include '../../db.php';
session_start();

$query = "SELECT t.*, 
                u1.nama AS penerima, 
                u2.nama AS pemberi
          FROM task t 
          LEFT JOIN user u1 ON t.assigned_to = u1.id 
          LEFT JOIN user u2 ON t.created_by = u2.id 
          ORDER BY t.deadline ASC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Tugas</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=search" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-[#0D2B53] text-white flex flex-col min-h-screen">
      <!-- Logo dan SIMANTAP -->
      <div class="flex items-center px-6 py-6 text-xl font-bold space-x-2">
        <img src="assets/titik_tiga.png" alt="Logo" class="w-6 h-6"/>
        <span>SIMANTAP</span>
      </div>

      <!-- Menu Admin -->
      <nav class="mt-2 px-4">
        <ul class="space-y-2 text-sm">
          <li>
            <a href="dashboard.php" class="block py-2.5 px-2.5 font-bold rounded hover:bg-orange-500 hover:text-white transition duration-300 hover:translate-x-1 mb-2">
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
            <a href="detail.php" class="block py-2 px-2 rounded hover:bg-orange-500 hover:text-white transition duration-300 hover:translate-x-1">Detail Tugas</a>
          </li>
          <li>
            <a href="laporan.php" class="block py-2 px-2 rounded hover:bg-orange-500 hover:text-white transition duration-300 hover:translate-x-1">Laporan Harian</a>
          </li>
          <li>
            <a href="tenggat.php" class="block py-2 px-2 rounded hover:bg-orange-500 hover:text-white transition duration-300 hover:translate-x-1">Permohonan Tenggat</a>
          </li>
          <li>
            <a href="kinerja_pegawai.php" class="block py-2 px-2 rounded hover:bg-orange-500 hover:text-white transition duration-300 hover:translate-x-1">Kinerja Pegawai</a>
          </li>
          <li>
            <a href="kelola_admin.php" class="block py-2 px-2 rounded hover:bg-orange-500 hover:text-white transition duration-300 hover:translate-x-1">Kelola Pengguna</a>
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

    <main class="flex-1 p-6 overflow-auto">
    <!-- Header dan Form -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-4 md:mb-0">Detail Tugas Pegawai</h1>
        <form action="search.php" method="GET" class="relative w-full md:w-1/3">
            <input type="text" name="q" placeholder="Cari..."
                class="w-full bg-white border border-gray-300 text-gray-700 px-4 py-2 pr-10 rounded-lg focus:outline-none focus:border-blue-400 shadow-sm" />
            <button type="submit"
                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                <span class="material-symbols-outlined">search</span>
            </button>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded shadow-md">
            <thead class="bg-gray-200 text-gray-600">
                <tr>
                    <th class="py-3 px-4 border">Nama</th>
                    <th class="py-3 px-4 border">Judul Tugas</th>
                    <th class="py-3 px-4 border">Deskripsi</th>
                    <th class="py-3 px-4 border">Kategori</th>
                    <th class="py-3 px-4 border">Tanggal Tugas</th>
                    <th class="py-3 px-4 border">Deadline</th>
                    <th class="py-3 px-4 border">Diberikan Oleh</th>
                    <th class="py-3 px-4 border">Ditugaskan Kepada</th>
                    <th class="py-3 px-4 border">Status</th>
                    <th class="py-3 px-4 border">Sisa Hari</th>

                </tr>
            </thead>
            <tbody>
                <?php
                $bulan_indo = [
                    'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
                    'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
                    'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
                    'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
                ];
       
    while ($row = mysqli_fetch_assoc($result)) {
    $deadline_date = new DateTime($row['deadline']);
    $now = new DateTime();
    $interval = $now->diff($deadline_date);
    $selisih_hari = (int)$interval->format('%r%a');
    $selisih_detik = $deadline_date->getTimestamp() - $now->getTimestamp();

    $formatted_tanggal = $deadline_date->format('d F Y');
    $formatted_tanggal = strtr($formatted_tanggal, $bulan_indo);

    // Inisialisasi default
    $keterangan = "Waktu tidak valid";
    $statusColor = "bg-gray-500 text-white";

    if ($selisih_detik <= 0) {
    // Sudah lewat deadline
    $keterangan = "Lewat waktu! ($formatted_tanggal)";
    $statusColor = "bg-red-500 text-white";
} elseif ($selisih_hari >= 3) {
    // 3+ hari
    $keterangan = "$selisih_hari hari lagi ($formatted_tanggal)";
    $statusColor = "bg-green-500 text-white"; 
} elseif ($selisih_hari == 2) {
    // 2 hari
    $keterangan = "$selisih_hari hari lagi ($formatted_tanggal)";
    $statusColor = "bg-yellow-400 text-black";
} else {
        // Less than 1 day (display hours:minutes:seconds)
        $jam = floor(abs($selisih_detik) / 3600);
        $sisa_detik = abs($selisih_detik) % 3600;
        $menit = floor($sisa_detik / 60);
        $detik = $sisa_detik % 60;
        // Ensure minutes are double digits (02) but keep seconds as normal (1 or 2 digits)
        $keterangan = "Tersisa " . sprintf("%d:%d:%d", $jam, $menit, $detik); 
        $statusColor = "bg-red-500 text-white";
}
    // Tampilkan di HTML
    ?>

                <tr class="hover:bg-gray-100">
                    <td class="py-2 px-4 border"><?= htmlspecialchars($row['nama']) ?></td>
                    <td class="py-2 px-4 border"><?= htmlspecialchars($row['judul']) ?></td>
                    <td class="py-2 px-4 border"><?= htmlspecialchars($row['deskripsi']) ?></td>
                    <td class="py-2 px-4 border"><?= htmlspecialchars($row['kategori']) ?></td>
                    <td class="py-2 px-4 border"><?= htmlspecialchars($row['tanggal_tugas']) ?></td>
                    <td class="py-2 px-4 border"><?= htmlspecialchars($row['deadline']) ?></td>
                    <td class="py-2 px-4 border"><?= htmlspecialchars($row['created_by']) ?></td>
                    <td class="py-2 px-4 border"><?= htmlspecialchars($row['assigned_to']) ?></td>
                    <td class="py-2 px-4 border"><?= htmlspecialchars($row['status']) ?></td>
                    <td class="py-2 px-4 border">
                        <span class="px-2 py-1 rounded-full text-sm font-semibold <?= $statusColor ?>">
                            <?= $keterangan ?>
                        </span>

                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>
