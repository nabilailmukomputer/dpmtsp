<?php
include '../../db.php';
session_start();

// Ambil semua permohonan beserta nama pegawai & judul tugas
$query = "SELECT dr.*, t.judul, u.nama AS pemohon
          FROM deadline_request dr
          JOIN task t ON dr.task_id = t.id
          JOIN user u ON dr.requested_by = u.id
          ORDER BY dr.requested_deadline DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Permohonan Tenggat</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
<h1 class="text-2xl font-bold mb-6">Permohonan Pengunduran Deadline</h1>
<table class="min-w-full bg-white border rounded shadow">
<thead class="bg-gray-200 text-gray-600">
<tr>
<th class="p-3 border">Tugas</th>
<th class="p-3 border">Pemohon</th>
<th class="p-3 border">Alasan</th>
<th class="p-3 border">Deadline Baru</th>
<th class="p-3 border">Status</th>
<th class="p-3 border">Aksi</th>
</tr>
</thead>
<tbody>
<?php while ($row = mysqli_fetch_assoc($result)): ?>
<tr class="hover:bg-gray-100">
<td class="p-3 border"><?= htmlspecialchars($row['judul']) ?></td>
<td class="p-3 border"><?= htmlspecialchars($row['pemohon']) ?></td>
<td class="p-3 border"><?= htmlspecialchars($row['alasan']) ?></td>
<td class="p-3 border"><?= htmlspecialchars($row['requested_deadline']) ?></td>
<td class="p-3 border font-bold"><?= htmlspecialchars($row['status']) ?></td>
<td class="p-3 border">
<?php if ($row['status'] == 'Menunggu'): ?>
<a href="proses_permohonan.php?id=<?= $row['id'] ?>&aksi=terima" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-700">Setujui</a>
<a href="proses_permohonan.php?id=<?= $row['id'] ?>&aksi=tolak" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-700">Tolak</a>
<?php else: ?>
<span class="text-gray-500">Selesai</span>
<?php endif; ?>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</body>
</html>
