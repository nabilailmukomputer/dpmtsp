<?php
include '../../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $query = "UPDATE task SET status = '$status' WHERE id = $id";
    mysqli_query($conn, $query);
}
?>
