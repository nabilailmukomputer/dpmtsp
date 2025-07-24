<?php
include '../../db.php';

if (!isset($_GET['bidang'])) {
    die("Parameter bidang tidak ditemukan!");
}
$bidang = $_GET['bidang'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal_tugas = $_POST['tanggal_tugas'];
    $deadline = $_POST['deadline'];
    $assigned_to = $_POST['assigned_to'];
    $pj_id = $_SESSION['id_user'];

    $query = "INSERT INTO task (judul, deskripsi, tanggal_tugas, deadline, created_by, assigned_to, bidang_user)
              VALUES ('$judul', '$deskripsi', '$tanggal_tugas', '$deadline', '$created_by', '$assigned_to', '$bidang_user')";

    if (mysqli_query($conn, $query)) {
        header("Location: {$bidang}.php");
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
<body class="p-4">
<div class="container">
    <h1 class="mb-4">Tambah Tugas - Bidang: <?php echo htmlspecialchars($bidang); ?></h1>
    <form method="POST" class="p-4 border rounded bg-light">
        <div class="mb-3">
            <label class="form-label">Judul Tugas</label>
            <input type="text" name="judul" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Tanggal Tugas</label>
            <input type="date" name="tanggal_tugas" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Deadline</label>
            <input type="date" name="deadline" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Ditugaskan Kepada</label>
            <input type="text" name="ditugaskan_kepada" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Simpan Tugas</button>
        <a href="<?php echo $bidang; ?>_dashboard.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>
</body>
</html>
