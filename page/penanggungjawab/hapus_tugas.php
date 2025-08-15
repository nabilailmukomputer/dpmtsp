<?php
include '../../db.php';
session_start();

// Pastikan user login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Cek apakah data ada
    $stmt = $conn->prepare("SELECT id FROM task WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Hapus data
        $stmt_del = $conn->prepare("DELETE FROM task WHERE id = ?");
        $stmt_del->bind_param("i", $id);
        if ($stmt_del->execute()) {
            // Balik ke halaman sebelumnya
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'kesekretariatan.php'));
            exit;
        } else {
            echo "Gagal menghapus data: " . $conn->error;
        }
        $stmt_del->close();
    } else {
        echo "Data dengan ID tersebut tidak ditemukan.";
    }
    $stmt->close();
} else {
    echo "ID tidak valid.";
}
?>
