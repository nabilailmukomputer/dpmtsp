<?php
session_start();
include '../../db.php';


if (!isset($_SESSION['user_id']) ) {
    // Jika belum, redirect ke halaman login
    header('Location: ../login.php');
    exit;
}
$roles = [
    'admin' => 'Admin',
    'penanggung jawab' => 'Penanggung Jawab',
    'pengendali teknis' => 'Pengendali Teknis',
    'ketua divisi' => 'Ketua Divisi',
    'anggota' => 'Anggota'
];
// Pastikan parameter bidang ada
if (!isset($_GET['bidang'])) {
    die("Parameter bidang tidak ditemukan!");
}
$bidang = $_GET['bidang'];

// Proses simpan data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $tanggal_tugas = mysqli_real_escape_string($conn, $_POST['tanggal_tugas']);
    $deadline_date = $_POST['deadline_date'];
    $deadline_time = $_POST['deadline_time'];
    $deadline = $deadline_date . ' ' . $deadline_time . ':00'; 
    $created_by=mysqli_real_escape_string($conn,$_POST['created_by']);
    $assigned_to = mysqli_real_escape_string($conn, $_POST['assigned_to']);
    $bidang_user=mysqli_real_escape_string($conn,$_POST['bidang_user']);
     $role_user=mysqli_real_escape_string($conn,$_POST['role_user']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    // Ambil nama user dari session
    $created_by = $_SESSION['nama_user'];
    $bidang_user = $bidang;

    $query = "INSERT INTO task (judul, deskripsi, tanggal_tugas, deadline, created_by, assigned_to, bidang_user, role_user,  status)
              VALUES ('$judul', '$deskripsi', '$tanggal_tugas', '$deadline', '$created_by', '$assigned_to', '$bidang_user', '$role_user',  '$status')";
              

    if (mysqli_query($conn, $query)) {
        header("Location: {$bidang}.php?success=1");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tambah Tugas</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
<div class="container">
    <h1 class="mb-4">Tambah Tugas - Bidang: <?php echo htmlspecialchars($bidang); ?></h1>
    <form method="POST" class="p-4 border rounded bg-white shadow">
        <!-- Judul Tugas -->
        <div class="mb-3">
            <label class="form-label">Judul Tugas</label>
            <input type="text" name="judul" class="form-control" required>
        </div>

        <!-- Deskripsi -->
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" required></textarea>
        </div>

        <!-- Tanggal Tugas -->
        <div class="mb-3">
            <label class="form-label">Tanggal Tugas</label>
            <input type="date" name="tanggal_tugas" class="form-control" required>
        </div>

        <!-- Deadline -->
        <div class="mb-3">
            <label class="form-label">Deadline</label>
            <div class="d-flex gap-2">
                <input type="date" name="deadline_date" class="form-control" required>
                <input type="time" name="deadline_time" class="form-control" required>
            </div>
        </div>

        <!-- Ditugaskan Kepada -->
        <div class="mb-3">
            <label class="form-label">Ditugaskan Kepada</label>
            <input type="text" name="assigned_to" class="form-control" required>
        </div>


        <div class="mb-3">
            <label class="form-label">Diberikan Oleh</label>
            <input type="text" name="created_by" class="form-control" required>
        </div>

        <label class="block mb-2 font-semibold">Role</label>
            <select name="role_user" required class="w-full mb-4 p-2 border rounded">
                <option value="">-- Pilih Role --</option>
                <?php foreach ($roles as $key => $label): ?>
                    <option value="<?= $key ?>" <?= $roles == $key ? 'selected' : '' ?>>
                        <?= $label ?>
                    </option>
                <?php endforeach; ?>
            </select>

        <!-- Status -->
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-control" required>
                <option value="Dikerjakan">Dikerjakan</option>
                <option value="Selesai">Selesai</option>
                <option value="Terlambat">Terlambat</option>
            </select>
        </div>

        <!-- Tombol -->
        <button type="submit" class="btn btn-success">Simpan Tugas</button>
        <a href="<?php echo $bidang; ?>.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>
</body>
</html>
