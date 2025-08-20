<?php
include '../../db.php';
session_start();

if (!isset($_GET['id'])) die("ID tugas tidak ditemukan.");

$id = intval($_GET['id']);

$q = mysqli_query($conn, "SELECT file_lampiran FROM task_update WHERE id = $id");
$row = mysqli_fetch_assoc($q);

if (!$row) die("File tidak ditemukan di database.");

// header untuk download
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="task_'.$id.'"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . strlen($row['file_lampiran']));

echo $row['file_lampiran'];
exit;
