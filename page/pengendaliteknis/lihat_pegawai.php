<?php
session_start();
include '../../db.php';
if (!isset($_SESSION['user_id']) ) {
    // Jika belum, redirect ke halaman login
    header('Location: ../login.php');
    exit;
}

$bidang = $_SESSION['bidang_id'];

// Ambil pegawai sesuai bidang
$pegawaiQuery = "SELECT nama FROM user WHERE bidang_id='$bidang'";
$pegawaiResult = mysqli_query($conn, $pegawaiQuery);

// Fungsi ambil tugas berdasarkan pegawai
function getTasksByPegawai($conn, $pegawai) {
    $tasks = [];
    $query = "SELECT * FROM task WHERE assigned_to='$pegawai'";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $tasks[] = $row;
    }
    return $tasks;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lihat Pegawai - Bidang <?php echo htmlspecialchars($bidang); ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">

<div class="container">
    <h1 class="mb-4">Pegawai Bidang: <?php echo htmlspecialchars($bidang); ?></h1>
    <div class="row">
        <?php if (mysqli_num_rows($pegawaiResult) > 0): ?>
            <?php while ($pegawai = mysqli_fetch_assoc($pegawaiResult)): ?>
                <div class="col-md-3 mb-4">
                    <div class="card shadow text-center">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($pegawai['nama']) ?></h5>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal<?= md5($pegawai['nama']) ?>">Lihat Tugas</button>
                        </div>
                    </div>
                </div>

                <!-- Modal untuk pegawai -->
                <div class="modal fade" id="modal<?= md5($pegawai['nama']) ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Tugas: <?= htmlspecialchars($pegawai['nama']) ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <?php
                                $tasks = getTasksByPegawai($conn, $pegawai['nama']);
                                if (empty($tasks)) {
                                    echo "<p class='text-muted'>Belum ada tugas.</p>";
                                } else {
                                    echo "<ul class='list-group'>";
                                    foreach ($tasks as $task) {
                                        $badgeClass = '';
                                        if ($task['status'] == 'Dikerjakan') $badgeClass = 'bg-warning';
                                        if ($task['status'] == 'Selesai') $badgeClass = 'bg-success';
                                        if ($task['status'] == 'Terlambat') $badgeClass = 'bg-danger';

                                        echo "
                                        <li class='list-group-item'>
                                            <div class='d-flex justify-content-between align-items-center'>
                                                <strong>{$task['judul']}</strong>
                                                <span class='badge $badgeClass'>{$task['status']}</span>
                                            </div>
                                            <p class='mb-0 text-muted'>{$task['deskripsi']}</p>
                                            <small>Deadline: {$task['deadline']}</small>
                                        </li>";
                                    }
                                    echo "</ul>";
                                }
                                ?>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-muted">Tidak ada pegawai di bidang ini.</p>
        <?php endif; ?>
    </div>
    <a href="dashboard.php" class="btn btn-secondary mt-3">Kembali</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
