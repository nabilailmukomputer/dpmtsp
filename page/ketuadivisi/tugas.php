<?php
session_start();
include '../../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil info user + bidang
$qUser = mysqli_query($conn, "SELECT u.nama, u.bidang_id, b.nama AS nama_bidang 
                              FROM user u 
                              JOIN bidang b ON u.bidang_id = b.id 
                              WHERE u.id = '$user_id'");
$user = mysqli_fetch_assoc($qUser);

$id_bidang = $user['bidang_id'];
$nama_user = $user['nama'];
$nama_bidang = $user['nama_bidang'];

// Ambil tugas sesuai bidang
$sql = "SELECT * FROM task WHERE bidang_user = '$id_bidang' ORDER BY deadline ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tugas - <?= htmlspecialchars($nama_bidang) ?></title>
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
    <a href="tugas.php" class="text-sm font-medium text-green-700 border-b-2 border-green-700 pb-1">Tugas</a>
    <a href="bidang.php" class="text-sm font-medium text-gray-600 hover:text-gray-900">Anggota</a>
    <a href="tenggat.php" class="text-sm font-medium text-gray-600 hover:text-gray-900">Permohonan Tenggat Waktu</a>
    <div class="ml-auto text-sm text-gray-500">
        Anda login sebagai: <span class="font-medium text-gray-700"><?= htmlspecialchars($nama_user) ?></span>
    </div>
</nav>

<!-- Daftar Tugas -->
<div class="max-w-5xl mx-auto p-6 space-y-4">
    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="bg-white shadow rounded-lg p-4 flex items-center hover:shadow-md transition">
                <!-- Ikon Lingkaran -->
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-600 text-xl font-bold">
                    📄
                </div>
                <!-- Info Tugas -->
                <div class="ml-4 flex-1">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-800">
                            <?= htmlspecialchars($row['judul']) ?>
                        </h2>
                        <span class="text-sm text-gray-500">
                            Deadline: <?= date("d M Y", strtotime($row['deadline'])) ?>
                        </span>
                    </div>
                    <p class="text-gray-600 mt-1">
                        <?= nl2br(htmlspecialchars($row['deskripsi'])) ?>
                    </p>
                    <div class="mt-2 flex items-center gap-4">
                        <a href="detail_tugas.php?id=<?= $row['id'] ?>" 
                           class="text-green-700 hover:underline text-sm">
                            Lihat Detail
                        </a>
                        <?php if (!empty($row['lampiran'])): ?>
                            <a href="../../uploads/<?= htmlspecialchars($row['lampiran']) ?>" 
                               class="text-sm text-gray-500 hover:text-gray-700" target="_blank">
                                📎 Lihat Lampiran
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="text-gray-500 text-center">Belum ada tugas untuk bidang ini.</p>
    <?php endif; ?>
</div>

</body>
</html>
