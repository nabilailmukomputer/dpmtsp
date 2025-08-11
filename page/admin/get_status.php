<?php
include '../../db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $query = mysqli_query($conn, "SELECT status FROM task WHERE id = $id");
    $data = mysqli_fetch_assoc($query);

    echo json_encode([
        'status' => $data['status']
    ]);
}
?>
