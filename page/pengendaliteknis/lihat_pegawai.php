<?php
session_start();
include '../../db.php';

// Pastikan user login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$bidang = $_SESSION['bidang_id'];

// Ambil nama bidang
$bidangResult = mysqli_query($conn, "SELECT nama FROM bidang WHERE id='$bidang'");
$bidangNama = ($bidangResult && mysqli_num_rows($bidangResult) > 0) 
    ? mysqli_fetch_assoc($bidangResult)['nama'] 
    : 'Tidak Diketahui';

// Ambil pegawai di bidang
$pegawaiQuery = "SELECT id, nama FROM user WHERE bidang_id='$bidang'";
$pegawaiResult = mysqli_query($conn, $pegawaiQuery);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Lihat Pegawai</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">

<div class="container">
    <h1 class="mb-4">Pegawai Bidang: <?= htmlspecialchars($bidangNama) ?></h1>
    <div class="row">
        <?php if (mysqli_num_rows($pegawaiResult) > 0): ?>
            <?php while ($pegawai = mysqli_fetch_assoc($pegawaiResult)): ?>
                <div class="col-md-3 mb-4">
                    <div class="card shadow text-center">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($pegawai['nama']) ?></h5>
                            <button class="btn btn-primary btn-sm lihat-tugas" 
                                    data-id="<?= $pegawai['id'] ?>" 
                                    data-nama="<?= htmlspecialchars($pegawai['nama']) ?>" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#taskModal">
                                Lihat Tugas
                            </button>

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

<!-- Modal -->
<div class="modal fade" id="taskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Tugas Pegawai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="taskContent">
                <p class="text-center text-muted">Memuat data...</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Event ketika tombol Lihat Tugas diklik
document.querySelectorAll('.lihat-tugas').forEach(btn => {
    btn.addEventListener('click', function() {
        const pegawaiId = this.getAttribute('data-id'); // ambil ID
        const nama = this.getAttribute('data-nama'); // ambil nama untuk judul modal
        
        document.querySelector('.modal-title').innerText = "Tugas: " + nama;
        const taskContent = document.getElementById('taskContent');
        taskContent.innerHTML = "<p class='text-center text-muted'>Memuat data...</p>";
        
        fetch('get_tasks.php?id=' + encodeURIComponent(pegawaiId))
            .then(response => response.text())
            .then(data => {
                taskContent.innerHTML = data; // langsung tampilkan isi dari get_tasks.php
            })
            .catch(error => {
                taskContent.innerHTML = "<p class='text-danger'>Gagal memuat data.</p>";
            });
    });
});

</script>
</body>
</html>
