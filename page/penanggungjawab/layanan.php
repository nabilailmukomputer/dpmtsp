<!DOCTYPE html>
<html>
<head>
  <title>Layanan - SIMANTAP</title>
  <style>
    body { font-family: Arial; background-color: #f5f5f5; margin: 0; }
    .sidebar { width: 250px; background: #002244; color: white; height: 100vh; float: left; padding: 20px; }
    .content { margin-left: 250px; padding: 20px; }
    .nav-tab a {
      margin-right: 15px;
      text-decoration: none;
      padding: 8px 12px;
      background: #003366;
      color: white;
      border-radius: 5px;
    }
    .nav-tab a:hover { background: #005599; }
  </style>
</head>
<body>

<div class="sidebar">
  <h2>SIMANTAP</h2>
  <p>Bidang: Layanan</p>
  <a href="dashboard.php" style="color: orange;">← Kembali</a>
</div>

<div class="content">
  <h2>📁 Layanan</h2>
  
  <!-- Tab Navigasi -->
  <div class="nav-tab">
    <a href="layanan.php?page=semua">Semua Tugas</a>
    <a href="layanan.php?page=kategori">Kategori</a>
    <a href="layanan.php?page=orang">Orang</a>
    <a href="layanan.php?page=selesai">Selesai</a>
  </div>

  <div style="margin-top: 20px;">
    <?php
    $page = $_GET['page'] ?? 'semua';
    if ($page == 'semua') {
      echo "<h3>📋 Daftar Semua Tugas di Layanan</h3>";
      // Tampilkan data dari database: SELECT * FROM task WHERE bidang='layanan'
    } elseif ($page == 'kategori') {
      echo "<h3>📂 Kategori Tugas Layanan</h3>";
      // Tampilkan kategori dari data
    } elseif ($page == 'orang') {
      echo "<h3>👤 Orang yang Bertugas di Layanan</h3>";
      // SELECT nama FROM user WHERE bidang='layanan'
    } elseif ($page == 'selesai') {
      echo "<h3>✅ Tugas yang Sudah Selesai</h3>";
      // SELECT * FROM task WHERE bidang='layanan' AND status='selesai'
    } else {
      echo "<p>Halaman tidak ditemukan</p>";
    }
    ?>
  </div>

</div>

</body>
</html>
