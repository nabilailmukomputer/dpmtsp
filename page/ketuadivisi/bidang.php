<?php
session_start();
include '../../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil info user dan bidang
$qUser = mysqli_query($conn, "SELECT u.nama, u.email, u.bidang_id, b.nama AS nama_bidang 
                              FROM user u
                              JOIN bidang b ON u.bidang_id = b.id
                              WHERE u.id = '$user_id'");
$user = mysqli_fetch_assoc($qUser);

$id_bidang = $user['bidang_id'];
$nama_user = $user['nama'];
$nama_bidang = $user['nama_bidang'];

// Ambil ketua divisi (role = ketua_divisi)
$qKetua = mysqli_query($conn, "SELECT nama, email 
                               FROM user 
                               WHERE bidang_id = '$id_bidang' AND role = 'ketua_divisi'");

// Ambil anggota divisi biasa
$qAnggota = mysqli_query($conn, "SELECT nama, email 
                                 FROM user 
                                 WHERE bidang_id = '$id_bidang' AND role = 'anggota'");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Anggota - <?= htmlspecialchars($nama_bidang) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

<!-- Header -->
<div class="bg-gray-700 text-white p-6 shadow-sm">
    <h1 class="text-2xl font-semibold"><?= htmlspecialchars($nama_bidang) ?></h1>
    <p class="text-sm opacity-90">Ketua: <?= htmlspecialchars($nama_user) ?></p>
</div>

<!-- Navbar -->
<nav class="bg-white shadow-sm px-6 py-3 flex items-center gap-6 border-b">
    <a href="dashboard.php" class="text-sm font-medium text-gray-600 hover:text-gray-900">Stream</a>
    <a href="tugas.php" class="text-sm font-medium text-gray-600 hover:text-gray-900">Tugas</a>
    <a href="bidang.php" class="text-sm font-medium text-green-700 border-b-2 border-green-700 pb-1">Anggota</a>
    <a href="tenggat.php" class="text-sm font-medium text-gray-600 hover:text-gray-900">Permohonan Tenggat Waktu</a>
    <div class="ml-auto text-sm text-gray-500">
        Anda login sebagai: <span class="font-medium text-gray-700"><?= htmlspecialchars($nama_user) ?></span>
    </div>
</nav>

<!-- Daftar Anggota -->
<div class="max-w-4xl mx-auto p-6 space-y-8">

    <!-- Ketua Divisi -->
    <div>
        <h2 class="text-lg font-semibold text-gray-800 mb-3">Ketua Divisi</h2>
        <div class="bg-white rounded-lg shadow divide-y">
            <?php while ($ketua = mysqli_fetch_assoc($qKetua)): ?>
                <div class="flex items-center p-4">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold">
                        <?= strtoupper(substr($ketua['nama'], 0, 1)) ?>
                    </div>
                    <div class="ml-4">
                        <p class="font-medium text-gray-800"><?= htmlspecialchars($ketua['nama']) ?></p>
                        <p class="text-sm text-gray-500"><?= htmlspecialchars($ketua['email']) ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Anggota -->
    <div>
        <h2 class="text-lg font-semibold text-gray-800 mb-3">Anggota</h2>
        <div class="bg-white rounded-lg shadow divide-y">
            <?php if (mysqli_num_rows($qAnggota) > 0): ?>
                <?php while ($anggota = mysqli_fetch_assoc($qAnggota)): ?>
                    <div class="flex items-center p-4">
                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold">
                            <?= strtoupper(substr($anggota['nama'], 0, 1)) ?>
                        </div>
                        <div class="ml-4">
                            <p class="font-medium text-gray-800"><?= htmlspecialchars($anggota['nama']) ?></p>
                            <p class="text-sm text-gray-500"><?= htmlspecialchars($anggota['email']) ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-gray-500 p-4 text-center">Belum ada anggota di bidang ini.</p>
            <?php endif; ?>
        </div>
    </div>

</div>

</body>
</html>
