<?php
include '../../db.php';

session_start();
if (!isset($_SESSION['user_id']) ) {
    // Jika belum, redirect ke halaman login
    header('Location: ../login.php');
    exit;
}
if (!isset($_GET['id'])) {
  header("Location: kelola_bidang.php");
  exit;
}

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM bidang WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (isset($_POST['update'])) {
  $bidang = $_POST['bidang'];
  mysqli_query($conn, "UPDATE bidang SET nama='$bidang' WHERE id=$id");
  header("Location: kelola_bidang.php");
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <nav class="bg-[#5C7CFA] py-4 px-8 flex items-center justify-between">
    <div class="flex items-center space-x-2">
      <h1 class="text-2xl md:text-3xl font-bold text-white">Edit Bidang</h1>
    </div>
    <div class="space-x-6">
      <a href="dashboard.php" class="text-white hover:underline">Dashboard</a>
       <a href="kelola_bidang.php" class="text-white hover:underline">Kelola Pengguna</a>
      
    </div>
  </nav>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-6 bg-gray-100">
  <form method="POST" class="bg-white p-4 rounded shadow-md w-full max-w-md">
             <label class="block mb-2">Bidang</label>
    <input type="text" name="bidang" value="<?= htmlspecialchars($data['nama']) ?>" required class="w-full mb-4 p-2 border rounded">
    <button type="submit" name="update" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
    <a href="kelola_bidang.php" class="ml-4 text-gray-600 hover:underline">Batal</a>
  </form>
</body>
</html>
