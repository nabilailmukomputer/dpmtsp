<?php
session_start();
include '../../db.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Ambil bidang user dari session
$bidang = $_SESSION['bidang_id'];
$user_id = $_SESSION['user_id'];

// Ambil pegawai sesuai bidang
$queryPegawai = "
    SELECT u.id, u.nama, b.nama AS bidang_nama
    FROM user u
    JOIN bidang b ON u.bidang_id = b.id
    WHERE u.role = 'Anggota' AND u.bidang_id = '$bidang'
";

$pegawai = mysqli_query($conn, $queryPegawai);

// Daftar role
$roles = [
    'admin' => 'Admin',
    'penanggung jawab' => 'Penanggung Jawab',
    'pengendali teknis' => 'Pengendali Teknis',
    'ketua divisi' => 'Ketua Divisi',
    'anggota' => 'Anggota'
];

// Proses simpan data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $tanggal_tugas = $_POST['tanggal_tugas'];
    $deadline_date = $_POST['deadline_date'];
    $deadline_time = $_POST['deadline_time'];
    $assigned_to = $_POST['assigned_to'];
    $created_by = $_SESSION['nama']; // Ambil nama user yang login
    $role_user = $_POST['role_user'];
    $status = $_POST['status'];

    // Gabungkan tanggal dan waktu deadline
    $deadline = $deadline_date . ' ' . $deadline_time;

    // Simpan ke database
    $queryInsert = "INSERT INTO task (judul, deskripsi, tanggal_tugas, deadline, assigned_to, created_by, role_user, status, bidang_user) 
                    VALUES ('$judul', '$deskripsi', '$tanggal_tugas', '$deadline', '$assigned_to', '$created_by', '$role_user', '$status', '$bidang')";
    
    if (mysqli_query($conn, $queryInsert)) {
        header("Location: $bidang.php?success=1");
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
            <select name="assigned_to" class="form-control" required>
                <option value="">-- Pilih Pegawai --</option>
                <?php while($row = mysqli_fetch_assoc($pegawai)): ?>
                    <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nama']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- Role -->
        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role_user" class="form-control" required>
                <option value="">-- Pilih Role --</option>
                <?php foreach ($roles as $key => $label): ?>
                    <option value="<?= $key ?>"><?= $label ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Status -->
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-control" required>
                <option value="dikerjakan">Dikerjakan</option>
                <option value="selesai">Selesai</option>
                <option value="terlambat">Terlambat</option>
            </select>
        </div>

        <!-- Tombol -->
        <button type="submit" class="btn btn-success">Simpan Tugas</button>
        <a href="<?= $bidang ?>.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>
</body>
</html>
