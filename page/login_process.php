<?php
session_start();
include '../db.php';

$nama = $_POST['nama'];
$password = $_POST['password'];
$role  = $_POST['role'];

$query = mysqli_query($conn, "SELECT * FROM user WHERE nama='$nama' AND password='$password' AND role='$role'");
$data  = mysqli_fetch_assoc($query);

if ($data) {
    $_SESSION['user'] = $data;
    header('Location: dashboard.php');
    exit;
} else {
    echo "<script>alert('Login gagal!');window.location='login.php';</script>";
}
?>
