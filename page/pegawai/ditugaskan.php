<?php
include '../../db.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['bidang_id'])) {
    header('Location: ../login.php');
    exit;
}

$bidang_id = intval($_SESSION['bidang_id']);

// ✅ Ambil nama bidang dari tabel bidang
$q_bidang = mysqli_query($conn, "SELECT nama FROM bidang WHERE id = '$bidang_id' LIMIT 1");
$bidang = mysqli_fetch_assoc($q_bidang);
$nama_bidang = $bidang ? $bidang['nama'] : "Tidak diketahui";

// ✅ Ambil semua tugas untuk bidang ini (pakai nama bidang)
$stmt = $conn->prepare("SELECT * FROM task WHERE bidang_user = ? ORDER BY tanggal_tugas DESC");
$stmt->bind_param("s", $nama_bidang);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html>
<head>
  <title>Tugas Bidang</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f6f7fb; padding: 20px; }
    .card {
      background: white;
      border-radius: 12px;
      margin: 15px 0;
      padding: 15px;
      box-shadow: 0 3px 6px rgba(0,0,0,0.1);
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
      color: black;
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .card:hover { box-shadow: 0 5px 12px rgba(0,0,0,0.15); }
    .icon {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: #3f51b5;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 22px;
    }
    .title { font-weight: bold; font-size: 16px; }
    .assigned { color: gray; font-size: 14px; }
  </style>
</head>
<body>
  <h2>📌 Tugas untuk <?= $nama_bidang ?></h2>

  <?php while($row = mysqli_fetch_assoc($result)): ?>
    <a class="card" href="detail_tugas.php?id=<?= $row['id'] ?>">
      <div class="icon">📘</div>
      <div>
        <div class="title"><?= htmlspecialchars($row['judul']) ?></div>
        <div class="assigned">Ditugaskan kepada <?= htmlspecialchars($row['assigned_to']) ?></div>
      </div>
    </a>
  <?php endwhile; ?>

</body>
</html>