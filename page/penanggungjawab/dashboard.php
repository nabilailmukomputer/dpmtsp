<?php
include '../../db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}


?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard SIMANTAP</title>
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f5f5f5;
      margin: 0;
      display: flex;
    }

    /* Sidebar */
    .sidebar {
      width: 250px;
      background: #002244;
      color: white;
      height: 100vh;
      padding: 20px;
      position: fixed;
      left: 0;
      top: 0;
      transition: width 0.3s ease;
      overflow: hidden;
      z-index: 999;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .sidebar.collapsed {
      width: 80px;
    }

    .sidebar h2 {
      text-align: center;
      margin-bottom: 20px;
      font-size: 22px;
      transition: opacity 0.3s ease;
    }

    .sidebar.collapsed h2 {
      opacity: 0;
    }

    .sidebar ul {
      list-style: none;
      padding: 0;
    }

    .sidebar ul li {
      margin: 15px 0;
    }

    .sidebar ul li a {
      display: flex;
      align-items: center;
      gap: 10px;
      color: white;
      text-decoration: none;
      font-size: 16px;
      padding: 10px;
      border-radius: 6px;
      transition: background 0.3s ease, transform 0.3s ease;
    }

    .sidebar ul li a:hover {
      background: #004080;
      transform: scale(1.05);
    }

    .menu-text {
      transition: opacity 0.3s ease;
    }

    /* Logout button style */
    .logout-btn {
      margin-top: auto;
      margin-bottom: 20px; /* ✅ Naikkan 20px dari bawah */
      border-top: 1px solid #004080;
      padding-top: 15px;
    }

    .logout-btn a {
      display: flex;
      align-items: center;
      gap: 10px;
      color: #ffffff; /* ✅ Ubah tulisan Logout jadi putih */
      font-weight: bold;
      text-decoration: none;
      padding: 10px;
      border-radius: 6px;
      transition: background 0.3s ease, color 0.3s ease;
    }

    .logout-btn a:hover {
      background: #ff9800;
      color: white;
    }

    /* Toggle Sidebar Button */
    .toggle-btn {
      position: absolute;
      top: 15px;
      left: 250px;
      background: #ff9800;
      border: none;
      border-radius: 50%;
      width: 45px;
      height: 45px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      transition: all 0.3s ease;
      z-index: 1000;
    }

    .toggle-btn:hover {
      background: #e68a00;
      transform: scale(1.1);
    }

    .toggle-btn i {
      font-size: 20px;
      color: white;
      transition: transform 0.3s ease;
    }

    /* Content */
    .content {
      margin-left: 270px;
      padding: 20px;
      flex: 1;
      transition: margin-left 0.3s ease;
    }

    .content h2 {
      margin-bottom: 20px;
      font-size: 24px;
      color: #333;
    }

    .card-container {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }

    .card {
      width: 250px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }

    .card img {
      width: 100%;
      height: 150px;
      object-fit: cover;
    }

    .card-title {
      padding: 15px;
      font-weight: bold;
      font-size: 18px;
      color: #002244;
    }

    .card-footer {
      padding: 10px 15px;
      text-align: right;
    }

    .lihat-btn {
      background: orange;
      padding: 8px 14px;
      border-radius: 5px;
      color: white;
      text-decoration: none;
      font-size: 14px;
      font-weight: bold;
    }

    .lihat-btn:hover {
      background: #cc8400;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .sidebar {
        left: -250px;
      }

      .sidebar.show {
        left: 0;
      }

      .toggle-btn {
        left: 15px;
      }

      .content {
        margin-left: 0;
      }
    }
</style>
</head>
<body>
  

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
  <div>
    <h2>SIMANTAP</h2>
    <ul>
      <li><a href="kesekretariatan.php"><i class="fa-solid fa-folder"></i> <span class="menu-text">Kesekretariatan</span></a></li>
      <li><a href="pelayanan.php"><i class="fa-solid fa-gear"></i> <span class="menu-text">Pelayanan</span></a></li>
      <li><a href="penanaman modal.php"><i class="fa-solid fa-building"></i> <span class="menu-text">Penanaman Modal</span></a></li>
      <li><a href="tambah.php"><i class="fa-solid fa-building"></i> <span class="menu-text">Tambah Tugas</span></a></li>
      <li><a href="permohonan_tenggat.php"><i class="fa-solid fa-building"></i> <span class="menu-text">Permohonan Tenggat</span></a></li>
    </ul>
  </div>
  <div class="logout-btn">
    <a href="login.php" onclick="confirmLogout(event)">
      <i class="fa-solid fa-right-from-bracket"></i> 
      <span class="menu-text">Logout</span>
    </a>
  </div>
</div>

<!-- Tombol Toggle Sidebar -->
<button class="toggle-btn" id="toggle-btn">
  <i class="fa-solid fa-bars"></i>
</button>

<!-- Content -->
<div class="content" id="content">
  <h2>📚 Dashboard</h2>
  <div class="card-container">
    <div class="card">
      <img src="../../assets/kesekretariatan.jpeg" alt="Kesekretariatan">
      <div class="card-title">Kesekretariatan</div>
      <div class="card-footer">
        <a href="kesekretariatan.php" class="lihat-btn">Lihat &gt;</a>
      </div>
    </div>

    <div class="card">
      <img src="../../assets/pelayanan.jpeg" alt="Layanan">
      <div class="card-title">Pelayanan</div>
      <div class="card-footer">
        <a href="pelayanan.php" class="lihat-btn">Lihat &gt;</a>
      </div>
    </div>

    <div class="card">
      <img src="../../assets/penanaman.jpeg" alt="Penanaman Modal">
      <div class="card-title">Penanaman Modal</div>
      <div class="card-footer">
        <a href="penanaman modal.php" class="lihat-btn">Lihat &gt;</a>
      </div>
    </div>
  </div>
</div>

<script>
const sidebar = document.getElementById('sidebar');
const toggleBtn = document.getElementById('toggle-btn');
const content = document.getElementById('content');
const icon = toggleBtn.querySelector('i');

toggleBtn.addEventListener('click', () => {
  if (window.innerWidth > 768) {
    sidebar.classList.toggle('collapsed');
    if (sidebar.classList.contains('collapsed')) {
      content.style.marginLeft = '100px';
      toggleBtn.style.left = '90px';
      icon.classList.remove('fa-bars');
      icon.classList.add('fa-arrow-right');
      document.querySelectorAll('.menu-text').forEach(el => el.style.display = 'none');
    } else {
      content.style.marginLeft = '270px';
      toggleBtn.style.left = '250px';
      icon.classList.remove('fa-arrow-right');
      icon.classList.add('fa-bars');
      document.querySelectorAll('.menu-text').forEach(el => el.style.display = 'inline');
    }
  } else {
    sidebar.classList.toggle('show');
  }

  icon.style.transform = 'rotate(180deg)';
  setTimeout(() => {
    icon.style.transform = 'rotate(0deg)';
  }, 300);
});

function confirmLogout(event) {
  event.preventDefault();
  if (confirm("Apakah Anda yakin ingin logout?")) {
    window.location.href = "../logout.php";
  }
}
</script>

</body>
</html>
