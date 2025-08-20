<?php
include '../../db.php';
session_start();

// Cek login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil tugas yang baru diberikan hari ini
$query = "
SELECT 
    t.id, 
    t.judul, 
    t.deadline, 
    t.tanggal_tugas, 
    u.nama AS assigner,
    COALESCE(tu.status,'dikerjakan') AS status,
    tu.file_lampiran,
    tu.tanggal_update
FROM task t
JOIN user u ON t.created_by = u.id
LEFT JOIN task_update tu 
    ON t.id = tu.task_id 
    AND tu.user_id = '$user_id'
WHERE t.assigned_to = '$user_id'
  AND DATE(t.tanggal_tugas) = CURDATE()
ORDER BY t.deadline ASC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tugas Harian</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f5f6fa; margin: 20px; }
        h2 { color: #2c3e50; }
        .card { background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .judul { font-size: 18px; font-weight: bold; color: #2c3e50; }
        .info { margin: 5px 0; color: #555; font-size: 14px; }
        .status { margin: 10px 0; font-weight: bold; color: #e67e22; }
        .status.selesai { color: #27ae60; }
        .file-box { background: #f8f9fa; padding: 10px; border-radius: 8px; margin-top: 10px; }
        a.file-link { color: #2980b9; text-decoration: none; }
    </style>
</head>
<body>

    <h2>📅 Tugas Harian (<?= date('d M Y') ?>)</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="card">
                <div class="judul"><?= htmlspecialchars($row['judul']) ?></div>
                <div class="info">📅 Diberikan: <?= $row['tanggal_tugas'] ?></div>
                <div class="info">⏰ Deadline: <?= $row['deadline'] ?></div>
                <div class="info">👨‍💼 Dari: <?= htmlspecialchars($row['assigner']) ?></div>
                
                <?php if (!empty($row['tanggal_update'])): ?>
                    <div class="info">📝 Update terakhir: <?= $row['tanggal_update'] ?></div>
                <?php endif; ?>

                <div class="status <?= $row['status']=='selesai' ? 'selesai' : '' ?>">
                    Status: <?= htmlspecialchars($row['status']) ?>
                </div>

                <?php if (!empty($row['file_lampiran'])): ?>
                <div class="file-box">
                    📂 File Tugas: 
                    <a class="file-link" href="../../uploads/<?= htmlspecialchars($row['file_lampiran']) ?>" target="_blank">
                        <?= htmlspecialchars($row['file_lampiran']) ?>
                    </a>
                </div>
                <?php else: ?>
                <div class="file-box">⚠️ Belum ada tugas baru hari ini.</div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>ℹ️ Tidak ada tugas baru hari ini.</p>
    <?php endif; ?>

</body>
</html>
