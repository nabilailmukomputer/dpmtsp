<?php
include '../../db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Ambil data permohonan tenggat
$query = "SELECT dr.*, 
       u.nama AS requested_by, 
       t.deadline AS deadline_awal, 
       t.judul AS judul_tugas
FROM deadline_request dr
JOIN user u ON dr.user_id = u.id
JOIN task t ON dr.task_id = t.id
WHERE dr.status in ('dikerjakan', 'selesai', 'terlambat')
ORDER BY dr.id DESC
";

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Permohonan Tenggat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <h1 class="text-2xl font-bold mb-4">Permohonan Tenggat Waktu</h1>
    <table class="min-w-full bg-white rounded shadow-md">
        <thead class="bg-gray-100 text-gray-800 font-semibold">
            <tr>
                <th class="py-2 px-4 border text-left">Nama Pegawai</th>
                <th class="py-2 px-4 border text-left">Judul Task</th>
                <th class="py-2 px-4 border text-left">Deadline Awal</th>
                <th class="py-2 px-4 border text-left">Deadline Diminta</th>
                <th class="py-2 px-4 border text-left">Alasan</th>
                <th class="py-2 px-4 border text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr class="hover:bg-gray-100">
                    <td class="py-2 px-4 border"><?= htmlspecialchars($row['requested_by']) ?></td>
                    <td class="py-2 px-4 border"><?= htmlspecialchars($row['judul_tugas']) ?></td>
                    <td class="py-2 px-4 border"><?= htmlspecialchars($row['deadline_awal']) ?></td>
                    <td class="py-2 px-4 border"><?= htmlspecialchars($row['requested_deadline']) ?></td>
                    <td class="py-2 px-4 border"><?= htmlspecialchars($row['alasan']) ?></td>
                    <td class="py-2 px-4 border">
                        <?php if ($row['status'] == 'dikerjakan'): ?>
                            <a href="proses_permohonan.php?id=<?= $row['id'] ?>&aksi=terima" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-700">Setujui</a>
                            <a href="proses_permohonan.php?id=<?= $row['id'] ?>&aksi=tolak" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-700">Tolak</a>
                        <?php else: ?>
                            <span class="text-gray-500">Selesai</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
