<?php
session_start();
include '../db.php';

$nama     = mysqli_real_escape_string($conn, $_POST['nama']);
$password = mysqli_real_escape_string($conn, $_POST['password']);

// Ambil user berdasarkan nama & password
$sql   = "SELECT * FROM user WHERE nama='$nama' AND password='$password' LIMIT 1";
$query = mysqli_query($conn, $sql);
$data  = mysqli_fetch_assoc($query);

if ($data) {
    // Simpan ke sesi
    $_SESSION['user'] = $data;
    session_regenerate_id(true); // opsional tapi disarankan

    // Ambil role dari DB
    $role = strtolower(trim($data['role'] ?? ''));

    // Mapping role -> path (gunakan folder tanpa spasi)
    $routes = [
        'admin'              => 'admin/dashboard.php',
        'penanggung jawab'   => 'penanggungjawab/dashboard.php',
        'pengendali teknis'  => 'pengendali_teknis/dashboard.php',
        'ketua divisi'       => 'ketua_divisi/dashboard.php',
        'pegawai'            => 'pegawai/dashboard.php',
    ];

    if (isset($routes[$role])) {
        header('Location: ' . $routes[$role]);
        exit;
    } else {
        echo "<script>alert('Role tidak dikenali di sistem.');window.location='login.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('Login gagal! Periksa nama dan password.');window.location='login.php';</script>";
    exit;
}

