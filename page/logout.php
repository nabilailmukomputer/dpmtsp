<?php
session_start(); // Mulai session
session_destroy(); // Hapus semua session

// Redirect ke halaman login
header("Location: login.php");
exit;
?>
