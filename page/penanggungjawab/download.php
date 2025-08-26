<?php
include '../../db.php';


if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT file_name, file_type, file_lampiran FROM task_update WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($file_name, $file_type, $file_data);
    $stmt->fetch();

    if ($file_data) {
        header("Content-Type: " . $file_type);
        header("Content-Disposition: attachment; filename=\"" . $file_name . "\"");
        header("Content-Length: " . strlen($file_data));
        echo $file_data;
        exit;
    } else {
        echo "File tidak ditemukan.";
    }
}
?>
