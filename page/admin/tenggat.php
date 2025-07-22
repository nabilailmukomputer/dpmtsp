<?php
include '../../db.php';
session_start();

// Ambil data permohonan tenggat
$query = "SELECT dr.*, u.nama AS nama_pengaju, t.deadline AS deadline_awal
          FROM deadline_request dr
          JOIN user u ON dr.user_id = u.id
          JOIN task t ON dr.task_id = t.id
          WHERE dr.status = 'Menunggu'
          ORDER BY dr.id DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Permohonan Tenggat</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 p-6">
    <h1 class="text-2xl font-bold mb-4">Permohonan Tenggat Waktu</h1>

    <table class="min-w-full bg-white rounded shadow-md">
        <thead>
            <tr>
                <th class="py-2 px-4 border">Nama Pegawai</th>
                <th class="py-2 px-4 border">Deadline Awal</th>
                <th class="py-2 px-4 border">Deadline Diminta</th>
                <th class="py-2 px-4 border">Alasan</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr class="hover:bg-gray-100">
                <td class="py-2 px-4 border"><?= htmlspecialchars($row['nama_pengaju']) ?></td>
                <td class="py-2 px-4 border"><?= htmlspecialchars($row['deadline_awal']) ?></td>
                <td class="py-2 px-4 border"><?= htmlspecialchars($row['requested_deadline']) ?></td>
                <td class="py-2 px-4 border"><?= htmlspecialchars($row['alasan']) ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
