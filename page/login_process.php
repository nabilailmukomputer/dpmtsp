<?php
session_start();
include '../db.php';

$nama = $_POST['nama'];
$password = $_POST['password'];
$role  = $_POST['role'];

// Cek ke database
$query = mysqli_query($conn, "SELECT * FROM user WHERE nama='$nama' AND password='$password' AND role='$role'");
$data  = mysqli_fetch_assoc($query);

if ($data) {
    $_SESSION['user'] = $data;

    // Arahkan ke dashboard berdasarkan role
    if ($role == 'admin') {
        header('Location:admin/dashboard.php');
    } elseif ($role == 'penanggung jawab') {
        header('Location:penanggungjawab/dashboard.php');
    } elseif ($role == 'pengendali teknis') {
        header('Location: pengendali teknis/dashboard.php');
    } elseif ($role == 'ketua divisi') {
        header('Location:ketua divisi/dashboard.php');
    } elseif ($role == 'pegawai') {
        header('Location: pegawai/dashboard.php');
    } else {
        echo "<script>alert('Role tidak dikenali.');window.location='login.php';</script>";
    }
    exit;
} else {
    echo "<script>alert('Login gagal! Periksa nama, password, dan role.');window.location='login.php';</script>";
}
?>
