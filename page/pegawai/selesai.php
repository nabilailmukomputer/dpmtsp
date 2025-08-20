<?php
include '../../db.php';
session_start();

// Cek login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data tugas yang sudah selesai + file dari task_update
$query = "SELECT t.id, t.judul, t.deadline, t.tanggal_tugas, 
                 tu.status, tu.file_lampiran, tu.tanggal_update,
                 u.nama AS assigner
          FROM task t
          JOIN user u ON t.created_by = u.id
          JOIN task_update tu ON t.id = tu.task_id
          WHERE t.assigned_to = '$user_id'
          AND tu.status = 'selesai'
          ORDER BY tu.tanggal_update DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tugas Selesai</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f5f6fa; margin: 20px; }
        h2 { color: #2c3e50; }
        .card { background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .judul { font-size: 18px; font-weight: bold; color: #2c3e50; }
        .info { margin: 5px 0; color: #555; font-size: 14px; }
        .status { margin: 10px 0; font-weight: bold; color: #27ae60; }
        .file-box { background: #f8f9fa; padding: 10px; border-radius: 8px; margin-top: 10px; }
        a.file-link { color: #2980b9; text-decoration: none; }
    </style>
</head>
<body>

    <h2>✅ Tugas Selesai</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="card">
                <div class="judul"><?= htmlspecialchars($row['judul']) ?></div>
                <div class="info">📅 Diberikan: <?= $row['tanggal_tugas'] ?></div>
                <div class="info">⏰ Deadline: <?= $row['deadline'] ?></div>
                <div class="info">👨‍💼 Dari: <?= htmlspecialchars($row['assigner']) ?></div>
                <div class="info">📝 Diselesaikan: <?= $row['tanggal_update'] ?></div>
                <div class="status">Status: <?= htmlspecialchars($row['status']) ?></div>

                <?php if (!empty($row['file_lampiran'])): ?>
                <div class="file-box">
                    📂 File Tugas: 
                    <a class="file-link" href="../../uploads/<?= htmlspecialchars($row['file_lampiran']) ?>" target="_blank">
                        <?= htmlspecialchars($row['file_lampiran']) ?>
                    </a>
                </div>
                <?php else: ?>
                <div class="file-box">⚠️ Tidak ada file tugas yang dikumpulkan.</div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>ℹ️ Belum ada tugas yang selesai dikerjakan.</p>
    <?php endif; ?>

</body>
</html>
