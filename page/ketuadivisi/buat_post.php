<?php
include '../../db.php';
session_start();

$id_bidang = $_POST['id_bidang'];
$konten = $_POST['konten'];
$user_id = $_SESSION['user_id'];

// 1. Simpan pengumuman
mysqli_query($conn, "INSERT INTO pengumuman (bidang_id, user_id, konten, tanggal, created_at)
                     VALUES ('$id_bidang', '$user_id', '$konten', NOW(), NOW())");
$id_pengumuman = mysqli_insert_id($conn);

// 2. Upload file (jika ada)
if (!empty($_FILES['lampiran']['name'][0])) {
    $total_files = count($_FILES['lampiran']['name']);
    for ($i = 0; $i < $total_files; $i++) {
        $nama_file = basename($_FILES['lampiran']['name'][$i]);
        $target = "../../uploads/" . time() . "_" . $nama_file;

        if (move_uploaded_file($_FILES['lampiran']['tmp_name'][$i], $target)) {
            mysqli_query($conn, "INSERT INTO lampiran_pengumuman (id_pengumuman, nama_file, path_file)
                                 VALUES ('$id_pengumuman', '$nama_file', '$target')");
        }
    }
}

header("Location: dashboard.php");
exit;
?>
