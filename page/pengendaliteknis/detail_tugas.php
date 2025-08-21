<?php
include '../../db.php';
session_start();

// cek login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

if (!isset($_GET['id'])) {
    echo "Tugas tidak ditemukan.";
    exit;
}

$id = intval($_GET['id']);

// ambil detail tugas
$q_tugas = mysqli_query($conn, "
    SELECT t.id, t.judul, t.deadline, t.status, u.nama AS pegawai, b.nama AS bidang
    FROM task t
    JOIN user u ON t.assigned_to = u.id
    JOIN bidang b ON u.bidang_id = b.id
    WHERE t.id = $id
");
$tugas = mysqli_fetch_assoc($q_tugas);

if (!$tugas) {
    echo "Tugas tidak ditemukan.";
    exit;
}

// ambil kumpulan tugas dari task_update
$q_update = mysqli_query($conn, "
    SELECT tu.id, tu.file_lampiran, tu.tanggal_update
    FROM task_update tu
    WHERE tu.task_id = $id
    ORDER BY tu.tanggal_update DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Detail Tugas</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

<div class="max-w-3xl mx-auto bg-white p-6 shadow rounded">
    <h2 class="text-xl font-bold mb-4">📄 Detail Tugas</h2>

    <p><b>Bidang:</b> <?= htmlspecialchars($tugas['bidang']) ?></p>
    <p><b>Pegawai:</b> <?= htmlspecialchars($tugas['pegawai']) ?></p>
    <p><b>Judul:</b> <?= htmlspecialchars($tugas['judul']) ?></p>
    <p><b>Deadline:</b> <?= $tugas['deadline'] ?></p>

    <p><b>Status:</b> 
        <?php if ($tugas['status'] == 'Dikerjakan'): ?>
            <span class="px-2 py-1 bg-yellow-200 text-yellow-800 rounded">Dikerjakan</span>
        <?php elseif ($tugas['status'] == 'Selesai'): ?>
            <span class="px-2 py-1 bg-green-200 text-green-800 rounded">Selesai</span>
        <?php elseif ($tugas['status'] == 'Terlambat'): ?>
            <span class="px-2 py-1 bg-red-200 text-red-800 rounded">Terlambat</span>
        <?php else: ?>
            <span class="px-2 py-1 bg-gray-200 text-gray-800 rounded"><?= $tugas['status'] ?></span>
        <?php endif; ?>
    </p>

    <hr class="my-4">

    <h3 class="text-lg font-semibold mb-2">📌 Tugas yang Dikumpulkan</h3>
    <?php if (mysqli_num_rows($q_update) > 0): ?>
        <?php while ($up = mysqli_fetch_assoc($q_update)): ?>
            <div class="p-3 border rounded mb-3">
                <p><b>Waktu Submit:</b> <?= $up['tanggal_update'] ?></p>
                <?php if (!empty($up['file_lampiran'])): ?>
                    <?php 
                        $file = $up['file_lampiran'];
                        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                        $file_path = "../../uploads/" . $file;
                        $is_image = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                    ?>
                    <p><b>File:</b>
                        <a href="download.php?id=<?= $up['id'] ?>" class="text-blue-600 underline">Download</a>
                    </p>

                    <?php if ($is_image): ?>
                        <!-- Preview gambar -->
                        <img src="<?= $file_path ?>" alt="Lampiran" class="mt-2 max-h-64 rounded border">
                    <?php elseif ($ext == 'pdf'): ?>
                        <!-- Preview PDF -->
                        <iframe src="<?= $file_path ?>" class="mt-2 w-full h-64 border rounded"></iframe>
                    <?php else: ?>
                        <!-- File lain (Word, Excel, ZIP, dll) -->
                        <p class="text-gray-600 mt-2">📄 File <?= strtoupper($ext) ?> tidak bisa dipreview.</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="text-gray-500">Belum ada file tugas yang dikumpulkan.</p>
    <?php endif; ?>

    <div class="mt-4">
        <a href="dashboard.php?page=selesai" class="px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600">⬅ Kembali</a>
    </div>
</div>

</body>
</html>
