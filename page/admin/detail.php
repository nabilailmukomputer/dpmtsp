<?php
include '../../db.php';
session_start();

$query = "SELECT t.*, 
                u1.nama AS penerima, 
                u2.nama AS pemberi
          FROM task t 
          LEFT JOIN user u1 ON t.asigned_to = u1.id 
          LEFT JOIN user u2 ON t.created_by = u2.id 
          ORDER BY t.deadline ASC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Tugas</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
    <h1 class="text-3xl font-bold mb-6">Detail Tugas Pegawai</h1>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded shadow-md">
            <thead class="bg-gray-200 text-gray-600">
                <tr>
                    <th class="py-3 px-4 border">Judul Tugas</th>
                    <th class="py-3 px-4 border">Deskripsi</th>
                    <th class="py-3 px-4 border">Kategori</th>
                    <th class="py-3 px-4 border">Deadline</th>
                    <th class="py-3 px-4 border">Diberikan Oleh</th>
                    <th class="py-3 px-4 border">Ditugaskan Kepada</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr class="hover:bg-gray-100">
                    <td class="py-2 px-4 border"><?= htmlspecialchars($row['judul']) ?></td>
                    <td class="py-2 px-4 border"><?= htmlspecialchars($row['deskripsi']) ?></td>
                    <td class="py-2 px-4 border"><?= htmlspecialchars($row['kategori']) ?></td>
                    <td class="py-2 px-4 border"><?= htmlspecialchars($row['deadline']) ?></td>
                    <td class="py-2 px-4 border"><?= htmlspecialchars($row['pemberi'] ?? 'Tidak diketahui') ?></td>
                    <td class="py-2 px-4 border"><?= htmlspecialchars($row['penerima'] ?? 'Belum ditugaskan') ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
