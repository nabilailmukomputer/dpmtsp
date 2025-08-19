<?php
include '../../db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['status'])) {
    $id = intval($_POST['id']);
    $status = $_POST['status'];

    // ambil deadline
    $sql = "SELECT deadline FROM task WHERE id=$id";
    $res = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($res);

    $deadline_ts = strtotime($row['deadline']);
    $now = time();
    $sisa_detik = $deadline_ts - $now;

    if ($status === 'selesai') {
        // simpan sisa detik terakhir (positif atau minus)
        $update = "UPDATE task SET status='selesai', sisa_waktu=$sisa_detik WHERE id=$id";
    } elseif ($status === 'terlambat') {
        $update = "UPDATE task SET status='terlambat', sisa_waktu=$sisa_detik WHERE id=$id";
    } else {
        $update = "UPDATE task SET status='dikerjakan', sisa_waktu=$sisa_detik WHERE id=$id";
    }

    mysqli_query($conn, $update);
}

header("Location: detail.php");
exit;
