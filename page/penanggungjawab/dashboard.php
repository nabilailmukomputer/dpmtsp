<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard SIMANTAP</title>
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
    }

    .sidebar h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    .sidebar button {
      background: orange;
      color: white;
      border: none;
      padding: 10px;
      width: 100%;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
      margin-bottom: 20px;
    }

    .sidebar p {
      font-size: 14px;
      margin-top: 10px;
    }

    /* Content */
    .content {
      margin-left: 270px;
      padding: 20px;
      flex: 1;
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
        width: 200px;
      }
      .content {
        margin-left: 220px;
      }
    }

    @media (max-width: 576px) {
      .sidebar {
        position: relative;
        width: 100%;
        height: auto;
      }
      .content {
        margin-left: 0;
      }
      .card-container {
        justify-content: center;
      }
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h2>SIMANTAP</h2>
  <button>Dashboard</button>
  <p>MENU UNTUK PENANGGUNG JAWAB</p>
</div>

<!-- Content -->
<div class="content">
  <h2>📚 Dashboard</h2>
  <div class="card-container">
    <!-- Kesekretariatan -->
    <div class="card">
      <img src="img/kesekretariatan.jpg" alt="Kesekretariatan">
      <div class="card-title">Kesekretariatan</div>
      <div class="card-footer">
        <a href="kesekretariatan.php" class="lihat-btn">Lihat &gt;</a>
      </div>
    </div>

    <!-- Layanan -->
    <div class="card">
      <img src="img/layanan.jpg" alt="Layanan">
      <div class="card-title">Layanan</div>
      <div class="card-footer">
        <a href="layanan.php" class="lihat-btn">Lihat &gt;</a>
      </div>
    </div>

    <!-- Penanaman Modal -->
    <div class="card">
      <img src="img/penanaman.jpg" alt="Penanaman Modal">
      <div class="card-title">Penanaman Modal</div>
      <div class="card-footer">
        <a href="penanaman.php" class="lihat-btn">Lihat &gt;</a>
      </div>
    </div>
  </div>
</div>

</body>
</html>
