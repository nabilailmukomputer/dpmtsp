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

// Ambil pegawai sesuai bidang
$bidang = $_SESSION['bidang_id'];
$queryPegawai = "SELECT * FROM user WHERE role='Anggota' AND bidang_id='$bidang'";
$pegawai = mysqli_query($conn, $queryPegawai);
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
