<?php
session_start();
include 'db.php';

$username = $_POST['nama'];
$password = $_POST['password'];

$query = mysqli_query($conn, "SELECT * FROM user WHERE nama='$username' AND password='$password'");
$data = mysqli_fetch_assoc($query);

if ($data) {
    $_SESSION['user'] = $data;
    header("Location: page/dashboard.php");
} else {
    echo "<script>alert('Login gagal!');window.location='pages/login.php';</script>";
}
?>
