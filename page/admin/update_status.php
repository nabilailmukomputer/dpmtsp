<?php
include '../../db.php';
session_start();
date_default_timezone_set('Asia/Jakarta');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['status'])) {
    $id = intval($_POST['id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $sisa_waktu = isset($_POST['sisa_waktu']) ? intval($_POST['sisa_waktu']) : "NULL";

    $sql = "UPDATE task 
            SET status='$status', sisa_waktu=$sisa_waktu 
            WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        echo "OK";
    } else {
        echo "ERROR: " . mysqli_error($conn);
    }
    exit;
}

echo "INVALID";
