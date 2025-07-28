<?php
include '../../db.php';

$id = $_GET['id'];
$aksi = $_GET['aksi'];

if ($aksi == 'terima') {
    $query = "SELECT task_id, requested_deadline FROM deadline_request WHERE id = $id";
    $data = mysqli_fetch_assoc(mysqli_query($conn, $query));

    // Update status permohonan
    mysqli_query($conn, "UPDATE deadline_request SET status='Disetujui' WHERE id = $id");

    // Update deadline di tabel task
    mysqli_query($conn, "UPDATE task SET deadline='{$data['deadline_baru']}' WHERE id={$data['task_id']}");

} elseif ($aksi == 'tolak') {
    mysqli_query($conn, "UPDATE deadline_request SET status='Ditolak' WHERE id = $id");
}

header("Location: permohonan_tenggat.php");
exit;
