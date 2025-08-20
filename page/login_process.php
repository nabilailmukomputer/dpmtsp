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
    $_SESSION['user_id']   = $data['id'];        // ID user
    $_SESSION['nama']      = $data['nama']; 
    $_SESSION['password']  = $data['password']; 
    $_SESSION['role']      = $data['role'];      // Role
    $_SESSION['bidang']    = $data['bidang'];    // Nama bidang
    $_SESSION['bidang_id'] = $data['bidang_id']; // Jika ada kolom ini di DB
    
   

    // Mapping role
    $routes = [
        'admin'              => 'admin/dashboard.php',
        'penanggung jawab'   => 'penanggungjawab/dashboard.php',
        'pengendali teknis'  => 'pengendaliteknis/dashboard.php', // Ganti folder tanpa spasi
        'ketua divisi'       => 'ketuadivisi/dashboard.php',
        'anggota'            => 'pegawai/dashboard.php',
    ];

    $role = strtolower(trim($data['role']));
    if (isset($routes[$role])) {
        header('Location: ' . $routes[$role]);
        exit;
    } else {
        echo "<script>alert('Role tidak dikenali di sistem.');window.location='login.php';</script>";
    }
} else {
    echo "<script>alert('Login gagal! Periksa nama dan password.');window.location='login.php';</script>";
}
