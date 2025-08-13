<?php
session_start();
include '../db.php';

$user_id = $_SESSION['user_id'] ?? 0;
$teks = $_POST['teks'] ?? '';
$lampiran = null;
$jenis_lampiran = null;

if (!empty($_FILES['file']['name'])) {
    $targetDir = "../uploads/";
    $fileName = time() . "_" . basename($_FILES['file']['name']);
    $targetFile = $targetDir . $fileName;
    move_uploaded_file($_FILES['file']['tmp_name'], $targetFile);
    $lampiran = $fileName;
    $jenis_lampiran = 'file';
}

$stmt = $conn->prepare("INSERT INTO pengumuman (user_id, teks, lampiran, jenis_lampiran) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isss", $user_id, $teks, $lampiran, $jenis_lampiran);
$stmt->execute();

header("Location: ../page/dashboard.php");
exit;
