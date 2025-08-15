<?php
include '../../db.php';

$id = (int)$_POST['id'];
$status = mysqli_real_escape_string($conn, $_POST['status']);
$sisa_waktu = isset($_POST['sisa_waktu']) ? (int)$_POST['sisa_waktu'] : null;
$waktu_terlambat = isset($_POST['waktu_terlambat']) ? (int)$_POST['waktu_terlambat'] : null;

$query = "UPDATE task SET status='$status'";

if ($sisa_waktu !== null) {
    $query .= ", sisa_waktu=$sisa_waktu, waktu_terlambat=NULL";
}
if ($waktu_terlambat !== null) {
    $query .= ", waktu_terlambat=$waktu_terlambat, sisa_waktu=NULL";
}

$query .= " WHERE id=$id";

if (mysqli_query($conn, $query)) {
    echo "OK";
} else {
    echo "Error: " . mysqli_error($conn);
}
