<?php
include '../../db.php';
session_start();

// Cek login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Pastikan ada parameter id
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID tugas tidak valid.");
}

$id = intval($_GET['id']);

// Hapus data
$delete = $conn->prepare("DELETE FROM task WHERE id = ?");
$delete->bind_param("i", $id);

if ($delete->execute()) {
    header("Location: lihat_pegawai.php");
    exit;
} else {
    echo "Gagal menghapus tugas.";
}
?>
