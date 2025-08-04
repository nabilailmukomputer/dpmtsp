<?php
include '../../db.php';
session_start();

// Pastikan user login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Ambil bidang user (ID dan nama bidang)
$user_id = $_SESSION['user_id'];
$query_user = mysqli_query($conn, "
    SELECT u.bidang_id, b.nama
    FROM user u 
    JOIN bidang b ON u.bidang_id = b.id 
    WHERE u.id = '$user_id'
");
$data_user = mysqli_fetch_assoc($query_user);
$nama_bidang = $data_user['nama_bidang']; // contoh: "Kesekretariatan"

// Ambil parameter status (default: all)
$status = isset($_GET['status']) ? strtolower(mysqli_real_escape_string($conn, $_GET['status'])) : 'all';

// Query dasar: ambil semua task berdasarkan nama bidang
$query = "SELECT * FROM task WHERE bidang_user = '$nama_bidang'";

// Jika ada filter status, tambahkan ke query
if ($status != 'all') {
    $query .= " AND LOWER(status) = '$status'";
}

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tugas - <?= htmlspecialchars($nama_bidang); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

<div class="max-w-6xl mx-auto mt-8 p-6 bg-white shadow-xl rounded-lg">
    <h2 class="text-3xl font-bold text-gray-800 mb-4">Daftar Tugas (<?= htmlspecialchars($nama_bidang); ?>)</h2>
    <p class="text-gray-600 mb-6">
        <?= ($status != 'all') ? "Status: " . ucfirst($status) : "Semua Tugas"; ?>
    </p>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
            <thead>
                <tr class="bg-blue-600 text-white">
                    <th class="py-3 px-4 text-left">No</th>
                    <th class="py-3 px-4 text-left">Nama Tugas</th>
                    <th class="py-3 px-4 text-left">Status</th>
                    <th class="py-3 px-4 text-left">Deadline</th>
                    <th class="py-3 px-4 text-left">Sisa Waktu</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                        <?php
                        // Tentukan warna badge
                        $badgeColor = "bg-gray-500";
                        if (strtolower($row['status']) == 'selesai') $badgeColor = "bg-green-500";
                        elseif (strtolower($row['status']) == 'dikerjakan') $badgeColor = "bg-yellow-500";
                        elseif (strtolower($row['status']) == 'terlambat') $badgeColor = "bg-red-500";

                        // Format deadline
                        $deadline = !empty($row['deadline']) ? date("d M Y H:i", strtotime($row['deadline'])) : "-";
                        ?>
                        <tr class="hover:bg-gray-100 border-b">
                            <td class="py-2 px-4"><?= $no++; ?></td>
                            <td class="py-2 px-4"><?= htmlspecialchars($row['judul']); ?></td>
                            <td class="py-2 px-4 status">
                                <span class="<?= $badgeColor; ?> text-white px-3 py-1 rounded-full text-sm">
                                    <?= htmlspecialchars($row['status']); ?>
                                </span>
                            </td>
                            <td class="py-2 px-4"><?= $deadline; ?></td>
                            <td class="py-2 px-4 text-green-600 font-bold countdown"
                                data-deadline="<?= htmlspecialchars($row['deadline']); ?>"
                                data-status="<?= strtolower($row['status']); ?>">
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">Tidak ada data tugas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Parse deadline format "YYYY-MM-DD HH:MM:SS"
function parseLocalDateTime(str) {
    if (!str) return null;
    let parts = str.split(/[- :]/);
    return new Date(parts[0], parts[1]-1, parts[2], parts[3], parts[4], parts[5]);
}

function updateCountdown() {
    document.querySelectorAll(".countdown").forEach(el => {
        let deadline = parseLocalDateTime(el.dataset.deadline);
        if (!deadline) {
            el.textContent = "-";
            return;
        }
        let now = new Date();
        let diff = deadline - now;

        if (diff > 0) {
            let hours = Math.floor(diff / (1000 * 60 * 60));
            let minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((diff % (1000 * 60)) / 1000);
            el.textContent = `${hours.toString().padStart(2,'0')}:${minutes.toString().padStart(2,'0')}:${seconds.toString().padStart(2,'0')}`;
        } else {
            el.textContent = "Waktu Habis";
            if (el.dataset.status !== 'selesai') {
                let statusCell = el.closest('tr').querySelector('.status');
