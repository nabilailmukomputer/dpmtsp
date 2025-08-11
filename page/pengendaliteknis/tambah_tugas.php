<?php
session_start();
include '../../db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Ambil semua user + bidang
$sql = "
SELECT user.id, user.nama, bidang.nama AS nama_bidang
FROM user
JOIN bidang ON user.bidang_id = bidang.id
ORDER BY bidang.nama, user.nama
";
$result_users = $conn->query($sql);

// List Role
$roles = [
    'admin' => 'Admin',
    'penanggung jawab' => 'Penanggung Jawab',
    'pengendali teknis' => 'Pengendali Teknis',
    'ketua divisi' => 'Ketua Divisi',
    'anggota' => 'Anggota'
];

// Ambil bidang dari URL hanya untuk judul (tidak untuk input database)
$bidang_url = isset($_GET['bidang']) ? mysqli_real_escape_string($conn, $_GET['bidang']) : '';

// Proses simpan data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $deadline_date = $_POST['deadline_date'];
    $deadline_time = $_POST['deadline_time'];
    $deadline = $deadline_date . ' ' . $deadline_time . ':00';
    $assigned_to = $_POST['assigned_to'];
    $role_user = $_POST['role_user'];
    $status = $_POST['status'];
    $created_by = isset($_SESSION['nama']) ? $_SESSION['nama'] : 'Admin';

    // ✅ Ambil nama bidang dari assigned_to
    $sqlBidang = "
        SELECT b.nama AS nama_bidang
        FROM user u
        JOIN bidang b ON u.bidang_id = b.id
        WHERE u.id = '$assigned_to'
    ";
    $resBidang = mysqli_query($conn, $sqlBidang);
    $dataBidang = mysqli_fetch_assoc($resBidang);
    $bidang_user = $dataBidang ? $dataBidang['nama_bidang'] : '';

    $stmt = $conn->prepare("
        INSERT INTO task (judul, deskripsi,  deadline, created_by, assigned_to, bidang_user, role_user, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("ssssssss", $judul, $deskripsi, $deadline, $created_by, $assigned_to, $bidang_user, $role_user, $status);

    if ($stmt->execute()) {
        header("Location: dashboard.php?success=1&bidang=" . urlencode($bidang_user));
        exit;
    } else {
        echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($stmt->error) . "</div>";
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
    <h1 class="mb-4">Tambah Tugas<?= $bidang_url ? ' - Bidang: ' . htmlspecialchars($bidang_url) : '' ?></h1>
    <form method="POST" action="?bidang=<?= urlencode($bidang_url); ?>" class="p-4 border rounded bg-white shadow">

        <!-- Judul -->
        <div class="mb-3">
            <label class="form-label">Judul Tugas</label>
            <input type="text" name="judul" class="form-control" required>
        </div>

        <!-- Deskripsi -->
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" required></textarea>
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
                <?php while ($user = $result_users->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($user['id']); ?>">
                        <?= htmlspecialchars($user['nama']); ?> (<?= htmlspecialchars($user['nama_bidang']); ?>)
                    </option>
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
                <option value="Dikerjakan">Dikerjakan</option>
                <option value="Selesai">Selesai</option>
                <option value="Terlambat">Terlambat</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Simpan Tugas</button>
        <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>
</body>
</html>
