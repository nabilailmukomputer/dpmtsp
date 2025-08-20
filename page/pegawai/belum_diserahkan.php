<?php
include '../../db.php';
session_start();

// Cek login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data tugas yang statusnya masih "dikerjakan" (belum diserahkan)
$query = "SELECT t.id, t.judul, t.deadline, t.tanggal_tugas, t.status, u.nama AS assigner
          FROM task t
          JOIN user u ON t.created_by = u.id
          WHERE t.assigned_to = '$user_id'
          ORDER BY t.deadline ASC";


$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tugas Belum Diserahkan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f5f6fa; margin: 20px; }
        .card { background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .judul { font-size: 18px; font-weight: bold; color: #2c3e50; }
        .info { margin: 5px 0; color: #555; font-size: 14px; }
        .status { margin: 10px 0; font-weight: bold; color: #e67e22; }
        .form-box { background: #f8f9fa; padding: 10px; border-radius: 8px; margin-top: 10px; }
    </style>
</head>
<body>

    <h2>📌 Tugas Belum Diserahkan</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="card">
                <div class="judul"><?= htmlspecialchars($row['judul']) ?></div>
                <div class="info">📅 Diberikan: <?= $row['tanggal_tugas'] ?></div>
                <div class="info">⏰ Deadline: <?= $row['deadline'] ?></div>
                <div class="info">👨‍💼 Dari: <?= htmlspecialchars($row['assigner']) ?></div>
                <div class="status">Status: <?= htmlspecialchars($row['status']) ?></div>

                <!-- Form Kirim Tugas -->
                <div class="form-box">
                    <form action="detail_tugas.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="task_id" value="<?= $row['id'] ?>">
                        <label>📂 Kirim Tugas:</label><br>
                        <input type="file" name="file_tugas" required>
                        <button type="submit">Kirim</button>
                    </form>
                </div>

                <!-- Form Ajukan Perpanjangan -->
                <div class="form-box">
                    <form action="ajukan_tenggat.php" method="post">
                        <input type="hidden" name="task_id" value="<?= $row['id'] ?>">
                        <label>📌 Ajukan Perpanjangan Tenggat:</label><br>
                        <input type="datetime-local" name="deadline_baru" required>
                        <button type="submit">Ajukan</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>✅ Tidak ada tugas yang belum diserahkan.</p>
    <?php endif; ?>

</body>
</html>
