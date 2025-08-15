<?php
include '../../db.php';

if (!isset($_GET['id'])) {
    echo "<p class='text-danger'>Pegawai tidak ditemukan.</p>";
    exit;
}

$pegawaiId = intval($_GET['id']); // sanitize ID
$query = "SELECT id, judul, deskripsi, deadline, status FROM task WHERE assigned_to = '$pegawaiId' ORDER BY deadline ASC";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    echo "<ul class='list-group'>";
    while ($task = mysqli_fetch_assoc($result)) {
        $badgeClass = '';
        if ($task['status'] == 'Dikerjakan') $badgeClass = 'bg-warning';
        if ($task['status'] == 'Selesai') $badgeClass = 'bg-success';
        if ($task['status'] == 'Terlambat') $badgeClass = 'bg-danger';

        echo "<li class='list-group-item'>
                <div class='d-flex justify-content-between align-items-center'>
                    <strong>".htmlspecialchars($task['judul'])."</strong>
                    <span class='badge $badgeClass'>".htmlspecialchars($task['status'])."</span>
                </div>
                <p class='mb-0 text-muted'>".htmlspecialchars($task['deskripsi'])."</p>
                <small>Deadline: ".htmlspecialchars($task['deadline'])."</small>
                <div class='mt-2 d-flex justify-content-end'>
                    <a href='edit_task.php?id=".$task['id']."' class='btn btn-warning btn-sm me-2'>Edit</a>
                    <a href='hapus_task.php?id=".$task['id']."' class='btn btn-danger btn-sm'
                       onclick='return confirm(\"Yakin ingin menghapus tugas ini?\")'>Hapus</a>
                </div>
              </li>";
    }
    echo "</ul>";
} else {
    echo "<p class='text-muted'>Belum ada tugas untuk pegawai ini.</p>";
}
