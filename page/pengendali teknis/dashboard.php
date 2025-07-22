<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'Pengendali Teknis') {
    header("Location: ../../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Dashboard SIMANTAP</title>
  <style>
    body { font-family: Arial; background-color: #f5f5f5; margin: 0; }
    .sidebar { width: 250px; background: #002244; color: white; height: 100vh; float: left; padding: 20px; }
    .content { margin-left: 250px; padding: 20px; }
    .card-container { display: flex; gap: 20px; }
    .card {
      width: 250px; background: white; border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;
    }
    .card img { width: 100%; height: 120px; object-fit: cover; }
    .card-title { padding: 10px; font-weight: bold; }
    .card-footer {
      padding: 10px; text-align: right;
    }
    .lihat-btn {
      background: orange; padding: 6px 12px; border-radius: 5px; color: white; text-decoration: none;
    }
  </style>
</head>
<body>

<div class="sidebar">
  <h2>SIMANTAP</h2>
  <button style="background: orange; color: white; border: none; padding: 10px; border-radius: 5px;">Dashboard</button>
  <p>MENU UNTUK PENANGGUNG JAWAB</p>
</div>

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