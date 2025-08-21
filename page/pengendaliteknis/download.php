<?php
include '../../db.php';
session_start();

// cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// pastikan ada id file
if (!isset($_GET['id'])) {
    die("File tidak ditemukan.");
}

$id = intval($_GET['id']);

// ambil info file dari task_update
$q = mysqli_query($conn, "SELECT file_lampiran FROM task_update WHERE id = $id");
$fileData = mysqli_fetch_assoc($q);

if (!$fileData || empty($fileData['file_lampiran'])) {
    die("File tidak ditemukan di database.");
}

$filename = basename($fileData['file_lampiran']);
$filepath = "../../uploads" . $filename;

// cek file ada di server
if (!file_exists($filepath)) {
    die("File tidak ditemukan di server.");
}

// tentukan header supaya browser download file
header("Content-Description: File Transfer");
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Expires: 0");
header("Cache-Control: must-revalidate");
header("Pragma: public");
header("Content-Length: " . filesize($filepath));

readfile($filepath);
exit;
