<?php
include '../../db.php';

$id = intval($_GET['id']);
$query = "SELECT status FROM task WHERE id = $id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

echo json_encode(['status' => $row['status']]);
?>
