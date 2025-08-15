<?php
include '../../db.php'; // Atau sesuaikan path-nya

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id']) && isset($data['sisa_waktu'])) {
    $id = intval($data['id']);
    $waktu = intval($data['sisa_waktu']);

    $stmt = $conn->prepare("UPDATE task SET sisa_waktu = ? WHERE id = ?");
    $stmt->bind_param("ii", $waktu, $id);
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "failed";
    }
    $stmt->close();
} else {
    echo "invalid data";
}
?>
