<?php
include '../../db.php';
session_start();

// Cek login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Cek parameter ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID tugas tidak valid.");
}

$id = intval($_GET['id']);

// Ambil data lama
$query = $conn->prepare("SELECT * FROM task WHERE id = ?");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
    die("Tugas tidak ditemukan.");
}

$task = $result->fetch_assoc();

// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $deadline = $_POST['deadline'];
    $status = $_POST['status'];

    $update = $conn->prepare("UPDATE task SET judul=?, deskripsi=?, deadline=?, status=? WHERE id=?");
    $update->bind_param("ssssi", $judul, $deskripsi, $deadline, $status, $id);

    if ($update->execute()) {
        header("Location: lihat_pegawai.php");
        exit;
    } else {
        echo "Gagal mengupdate tugas.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Tugas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="p-4">
    <div class="container">
        <h3>Edit Tugas</h3>
        <form method="post">
            <div class="mb-3">
                <label>Judul</label>
                <input type="text" name="judul" value="<?= htmlspecialchars($task['judul']) ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" required><?= htmlspecialchars($task['deskripsi']) ?></textarea>
            </div>
            <div class="mb-3">
                <label>Deadline</label>
                <input type="datetime-local" name="deadline" value="<?= date('Y-m-d\TH:i', strtotime($task['deadline'])) ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="Dikerjakan" <?= $task['status'] == 'Dikerjakan' ? 'selected' : '' ?>>Dikerjakan</option>
                    <option value="Selesai" <?= $task['status'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                    <option value="Terlambat" <?= $task['status'] == 'Terlambat' ? 'selected' : '' ?>>Terlambat</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="lihat_pegawai.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>
</html>
