<?php
include '../../db.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['bidang_id'])) {
    die("Akses ditolak");
}

$user_id = $_SESSION['user_id'];
$bidang_id = $_SESSION['bidang_id'];
$teks = $_POST['teks'] ?? '';
$created_at = date('Y-m-d H:i:s');

// Upload file jika ada
$lampiranNama = null;
$jenisLampiran = null;

if (!empty($_FILES['lampiran']['name'][0])) {
    $file = $_FILES['lampiran'];
    $namaFile = time() . "_" . basename($file['name'][0]);
    $targetPath = "uploads" . $namaFile;

    if (move_uploaded_file($file['tmp_name'][0], $targetPath)) {
        $lampiranNama = $namaFile;
        $jenisLampiran = $file['type'][0]; // mime type
    }
}

// Simpan ke database
$stmt = $conn->prepare("INSERT INTO pengumuman (user_id, teks, lampiran, jenis_lampiran, created_at, bidang_id) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("issssi", $user_id, $teks, $lampiranNama, $jenisLampiran, $created_at, $bidang_id);

if ($stmt->execute()) {
    header("Location: dashboard.php");
} else {
    echo "Gagal menyimpan pengumuman: " . $stmt->error;
}
$stmt->close();
$conn->close();
?>
