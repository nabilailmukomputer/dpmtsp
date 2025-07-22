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
</head>
<body class="bg-gray-100 p-6">
    <h1 class="text-2xl font-bold mb-6">Laporan Harian Pegawai</h1>

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
</body>
</html>
