<?php
include '../../db.php'; // pastikan path-nya sesuai

if (!isset($_SESSION['user_id']) ) {
    // Jika belum, redirect ke halaman login
    header('Location: ../login.php');
    exit;
}

$page = isset($_GET['page']) ? $_GET['page'] : 'semua';
?>

<!DOCTYPE html>
<html>
<head>
  <title>Dashboard Penanaman</title>
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

  <h2>📋 Dashboard Penanaman Modal</h2>

  <div class="nav">
    <a href="?page=semua" class="<?= $page == 'semua' ? 'active' : '' ?>">Semua Tugas</a>
    <a href="?page=orang" class="<?= $page == 'orang' ? 'active' : '' ?>">Orang</a>
    <a href="?page=selesai" class="<?= $page == 'selesai' ? 'active' : '' ?>">Selesai</a>
    <a href="tambah.php?bidang=penanaman modal" class="btn btn-primary">Tambah Tugas</a>

  </div>

  <?php
  if ($page == 'semua') {
      echo "<h3>📌 Semua Tugas</h3>";
      $query = mysqli_query($conn, "SELECT * FROM task WHERE bidang_user='Penanaman Modal'");
      while ($row = mysqli_fetch_assoc($query)) {
          echo "<div class='card'>
                  <strong>".$row['judul']."</strong><br>
                  Nama: ".$row['nama']."<br>
                  Kategori: ".$row['kategori']."<br>
                  Deadline: ".$row['deadline']."<br>
                  Status: ".$row['status']."
                </div>";
      }
  }

  elseif ($page == 'orang') {
    echo "<h3>👤 Pegawai di Bidang Penanaman Modal</h3>";
    
    // Query yang lebih baik (join tabel jika diperlukan)
    $query = mysqli_query($conn, "SELECT u.id, u.nama 
                                 FROM user u
                                 JOIN bidang b ON u.bidang_id = b.id
                                 WHERE b.nama = 'Penanaman Modal'");
    
    if(mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            echo "<div class='card'>
                    <strong>".htmlspecialchars($row['nama'])."</strong>
                    <p>ID: ".$row['id']."</p>
                  </div>";
        }
    } else {
        echo "<div class='card'>Tidak ada data pegawai</div>";
    }
}

  elseif ($page == 'selesai') {
      echo "<h3>✅ Tugas Selesai</h3>";
      $query = mysqli_query($conn, "SELECT * FROM task WHERE bidang_user='Penanaman Modal' AND status='selesai'");
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
