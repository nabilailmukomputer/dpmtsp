<?php
session_start();
include '../../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$konten = $_POST['konten'] ?? '';
$lampiran = null;

if (!empty($_FILES['file_upload']['name'])) {
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
    
    $fileName = time() . "_" . basename($_FILES['file_upload']['name']);
    $targetFilePath = $targetDir . $fileName;

    if (move_uploaded_file($_FILES['file_upload']['tmp_name'], $targetFilePath)) {
        $lampiran = $fileName;
    }
}

$stmt = $conn->prepare("INSERT INTO pengumuman (user_id, konten, lampiran) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $user_id, $konten, $lampiran);
$stmt->execute();
$stmt->close();

header("Location: dashboard.php");
exit();
?>
