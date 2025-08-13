<?php
include '../../db.php';
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['bidang_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_nama = $_SESSION['nama'];
$bidang_id = $_SESSION['bidang_id'];
$resultBidang = mysqli_query($conn, "SELECT nama FROM bidang WHERE id='$bidang_id'");
$bidang_nama = ($resultBidang && mysqli_num_rows($resultBidang) > 0) 
    ? mysqli_fetch_assoc($resultBidang)['nama'] 
    : 'Tidak Diketahui';

$tugas_query = mysqli_query($conn, "SELECT judul, deadline, status 
    FROM task 
    WHERE bidang_user='$bidang_nama' 
    ORDER BY tanggal_tugas DESC 
    LIMIT 5");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Pengendali</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<!-- Wrapper -->
<div class="min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-gray-800 text-white p-6">
        <h1 class="text-2xl font-bold mb-6">Pengendali</h1>
        <nav>
            <ul class="space-y-3">
                <li><a href="tambah_tugas.php" class="block hover:bg-gray-700 px-3 py-2 rounded">➕ Tambah Tugas</a></li>
                <li><a href="lihat_pegawai.php" class="block hover:bg-gray-700 px-3 py-2 rounded">👥 Lihat Pegawai</a></li>
                <li><a href="permohonan_tenggat.php" class="block hover:bg-gray-700 px-3 py-2 rounded">📩 Permohonan Tenggat</a></li>
                <li><a href="../logout.php" class="block hover:bg-red-500 px-3 py-2 rounded">🚪 Logout</a></li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6">
        <!-- Header -->
        <h1 class="text-3xl font-bold mb-2">Dashboard Pengendali</h1>
        <p class="text-lg text-gray-700 mb-6">Bidang: <span class="font-semibold"><?= htmlspecialchars($bidang_nama) ?></span></p>

        <!-- Info User -->
        <div class="mb-6 bg-white shadow p-4 rounded-lg">
            <p class="text-gray-700">👤 Pengendali: <strong><?= htmlspecialchars($user_nama) ?></strong></p>
            <p class="text-gray-700">📅 Tanggal: <?= date('d F Y') ?></p>
        </div>

        <!-- Navigasi Cepat -->
        <div class="flex gap-4 mb-6">
            <a href="tambah_tugas.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Tambah Tugas</a>
            <a href="lihat_pegawai.php" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">Lihat Pegawai</a>
            <a href="permohonan_tenggat.php" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg">Permohonan Tenggat</a>
        </div>

        <!-- Tabel Tugas Terbaru -->
        <div class="bg-white shadow rounded-lg p-4">
            <h2 class="text-xl font-bold mb-4">📌 Tugas Terbaru</h2>
            <table class="w-full border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border px-4 py-2">Judul</th>
                        <th class="border px-4 py-2">Deadline</th>
                        <th class="border px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($tugas_query) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($tugas_query)): ?>
                        <tr class="text-center hover:bg-gray-50">
                            <td class="border px-4 py-2"><?= htmlspecialchars($row['judul']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($row['deadline']) ?></td>
                            <td class="border px-4 py-2 font-bold
                                <?= $row['status']=='selesai'?'text-green-600':($row['status']=='dikerjakan'?'text-yellow-600':'text-red-600') ?>">
                                <?= htmlspecialchars(ucfirst($row['status'])) ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="3" class="text-center py-4 text-gray-500">Belum ada tugas</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

</body>
</html>
