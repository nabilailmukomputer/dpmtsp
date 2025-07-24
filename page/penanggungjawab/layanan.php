<?php
include '../../db.php'; // pastikan path-nya sesuai

$page = isset($_GET['page']) ? $_GET['page'] : 'semua';
?>

<!DOCTYPE html>
<html>
<head>
  <title>Dashboard Layanan</title>
  <style>
    body { font-family: Arial; background-color: #f5f5f5; margin: 0; padding: 20px; }
    h2 { color: #002244; }
    .nav { margin-bottom: 20px; }
    .nav a {
      padding: 10px 15px;
      background: #002244;
      color: white;
      text-decoration: none;
      margin-right: 5px;
      border-radius: 5px;
    }
    .nav a.active { background: orange; }
    .card {
      background: white;
      padding: 15px;
      margin-bottom: 15px;
      border-radius: 8px;
      box-shadow: 0 1px 5px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>

  <h2>📋 Dashboard Layanan</h2>

  <div class="nav">
    <a href="?page=semua" class="<?= $page == 'semua' ? 'active' : '' ?>">Semua Tugas</a>
    <a href="?page=kategori" class="<?= $page == 'kategori' ? 'active' : '' ?>">Kategori</a>
    <a href="?page=orang" class="<?= $page == 'orang' ? 'active' : '' ?>">Orang</a>
    <a href="?page=selesai" class="<?= $page == 'selesai' ? 'active' : '' ?>">Selesai</a>
  </div>

  <?php
  if ($page == 'semua') {
      echo "<h3>📌 Semua Tugas</h3>";
      $query = mysqli_query($conn, "SELECT * FROM task WHERE bidang_user='pelayanan'");
      while ($row = mysqli_fetch_assoc($query)) {
          echo "<div class='card'>
                  <strong>".$row['judul']."</strong><br>
                  Kategori: ".$row['kategori']."<br>
                  Deadline: ".$row['deadline']."<br>
                  Status: ".$row['status']."
                </div>";
      }
  }

  elseif ($page == 'kategori') {
      echo "<h3>📁 Kategori Tugas</h3>";
      $query = mysqli_query($conn, "SELECT DISTINCT kategori FROM task WHERE bidang_user='pelayanan'");
      while ($row = mysqli_fetch_assoc($query)) {
          echo "<div class='card'>".$row['kategori']."</div>";
      }
  }

  elseif ($page == 'orang') {
      echo "<h3>👤 Pegawai di Bidang Layanan</h3>";
      $query = mysqli_query($conn, "SELECT DISTINCT nama FROM user WHERE bidang='pelayanan'");
      while ($row = mysqli_fetch_assoc($query)) {
          echo "<div class='card'>".$row['nama']."</div>";
      }
  }

  elseif ($page == 'selesai') {
      echo "<h3>✅ Tugas Selesai</h3>";
      $query = mysqli_query($conn, "SELECT * FROM task WHERE bidang_user='pelayanan' AND status='selesai'");
      while ($row = mysqli_fetch_assoc($query)) {
          echo "<div class='card'>
                  <strong>".$row['judul']."</strong><br>
                  Deadline: ".$row['deadline']."<br>
                  Status: <span style='color:green;'>Selesai</span>
                </div>";
      }
  }
  ?>

</body>
</html>
