<?php
include '.././db.php';
session_start();

if (isset($_POST['submit'])) {
    $task_id = $_POST['task_id'];
    $user_id = $_SESSION['user_id'];
    $status = 'dikerjakan';

    $file_name = $_FILES['file']['name'];
    $file_tmp  = $_FILES['file']['tmp_name'];
    $file_type = $_FILES['file']['type'];

    // Lokasi folder upload
    $target_dir = __DIR__ . "/uploads/"; // absolute path
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $new_file_name = time() . "_" . basename($file_name);
    $target_file = $target_dir . $new_file_name;

    if (move_uploaded_file($file_tmp, $target_file)) {
        // Simpan relative path ke DB (biar gampang diakses)
        $relative_path = "uploads/" . $new_file_name;

        $stmt = $conn->prepare("INSERT INTO task_update 
            (task_id, user_id, status, file_lampiran, file_name, file_type) 
            VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissss", $task_id, $user_id, $status, $relative_path, $file_name, $file_type);
        $stmt->execute();

        echo "Upload berhasil!";
    } else {
        echo "Upload gagal!";
    }
}
?>
