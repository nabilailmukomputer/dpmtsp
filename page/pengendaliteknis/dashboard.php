<?php
include '../../db.php';
if (!isset($_SESSION['user_id']) ) {
    // Jika belum, redirect ke halaman login
    header('Location: ../login.php');
    exit;
}


$bidang = $_SESSION['bidang_id'];
$user_id = $_SESSION['nama'];

// Ambil tugas sesuai bidang
$queryTask = "SELECT t.*, u.nama AS pegawai
              FROM task t
              LEFT JOIN user u ON t.assigned_to = u.id
              WHERE t.bidang_user = '$bidang'";
$tasks = mysqli_query($conn, $queryTask);

// Ambil permohonan tenggat sesuai bidang
$queryPermohonan = "SELECT dr.*, t.judul, u.nama AS pemohon
          FROM deadline_request dr
          JOIN task t ON dr.task_id = t.id
          JOIN user u ON dr.requested_by = u.id
          WHERE t.bidang_user = '$bidang'
          ORDER BY dr.requested_deadline DESC";
$permohonan = mysqli_query($conn, $queryPermohonan);

// Ambil pegawai bidang ini
$queryPegawai = "SELECT * FROM user WHERE role='Anggota' AND bidang_id='$bidang'";
$pegawai = mysqli_query($conn, $queryPegawai);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Pengendali - <?= htmlspecialchars($bidang) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-800 text-white h-screen p-5">
        <h1 class="text-2xl font-bold mb-6">Pengendali</h1>
        <nav>
            <ul>
                <li class="mb-3"><a href="tambah_tugas.php" class="hover:text-yellow-300">Tambah Tugas</a></li>
                <li class="mb-3"><a href="lihat_pegawai.php" class="hover:text-yellow-300">Lihat Pegawai</a></li>
                <li class="mb-3"><a href="permohonan_tenggat.php" class="hover:text-yellow-300">Permohonan Tenggat</a></li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6">
        <h1 class="text-3xl font-bold mb-6">Dashboard Pengendali - <?= htmlspecialchars($bidang) ?></h1>

       