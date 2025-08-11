<?php
session_start();
include '../../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil info ketua + divisinya
$qUser = mysqli_query($conn, "SELECT u.nama, u.bidang_id, b.nama AS nama_bidang
                              FROM user u
                              JOIN bidang b ON u.bidang_id = b.id
                              WHERE u.id = '$user_id'");
$user = mysqli_fetch_assoc($qUser);

$id_bidang = $user['bidang_id'];
$nama_bidang = $user['nama'];

// Ambil data permohonan (hanya yang ada di divisi ini)
$qPermohonan = mysqli_query($conn, "
    SELECT dr.id, t.judul AS judul_tugas, u.nama AS nama_pengaju, dr.alasan, dr.requested_deadline
    FROM deadline_request dr
    JOIN task t ON dr.task_id = t.id
    JOIN user u ON dr.user_id = u.id
    WHERE t.bidang_user = '$id_bidang'
    ORDER BY dr.requested_deadline DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Permohonan Tenggat Waktu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<!-- Header -->
<div class="bg-gray-700 text-white p-6 shadow-sm">
    <h1 class="text-2xl font-semibold">Permohonan Tenggat Waktu - <?= htmlspecialchars($nama_bidang) ?></h1>
</div>

<!-- Navbar -->
<nav class="bg-white shadow-sm px-6 py-3 flex items-center gap-6 border-b">
    <a href="dashboard.php" class="text-sm font-medium text-gray-600 hover:text-gray-900">Stream</a>
    <a href="tugas.php" class="text-sm font-medium text-gray-600 hover:text-gray-900">Tugas</a>
    <a href="bidang.php" class="text-sm font-medium text-gray-600 hover:text-gray-900">Anggota</a>
    <a href="tenggat.php" class="text-sm font-medium text-purple-700 border-b-2 border-purple-700 pb-1">Permohonan Tenggat Waktu</a>
    <div class="ml-auto text-sm text-gray-500">
        Anda login sebagai: <span class="font-medium text-gray-700"><?= htmlspecialchars($user['nama']) ?></span>
    </div>
</nav>

<!-- List Permohonan -->
<div class="max-w-5xl mx-auto p-6">
    <?php if (mysqli_num_rows($qPermohonan) > 0): ?>
        <div class="bg-white rounded-lg shadow divide-y">
            <?php while ($p = mysqli_fetch_assoc($qPermohonan)): ?>
                <div class="p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="font-semibold text-gray-800"><?= htmlspecialchars($p['judul_tugas']) ?></h2>
                        <p class="text-sm text-gray-500">Diajukan oleh: <?= htmlspecialchars($p['nama_pengaju']) ?></p>
                        <p class="text-sm text-gray-500 mt-1">Alasan: <?= htmlspecialchars($p['alasan']) ?></p>
                        <p class="text-xs text-gray-400 mt-1">Tanggal Pengajuan: <?= date("d M Y", strtotime($p['tanggal_pengajuan'])) ?></p>
                    </div>
                    <div class="mt-3 sm:mt-0 text-sm text-gray-700">
                        <p><span class="font-medium">Tenggat Lama:</span> <?= date("d M Y", strtotime($p['tenggat_lama'])) ?></p>
                        <p><span class="font-medium">Tenggat Diminta:</span> <?= date("d M Y", strtotime($p['tenggat_baru'])) ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-gray-500 text-center">Tidak ada permohonan tenggat waktu.</p>
    <?php endif; ?>
</div>

</body>
</html>
