<?php
include '../../db.php';
session_start();
if (!isset($_SESSION['user_id']) ) {
    // Jika belum, redirect ke halaman login
    header('Location: ../login.php');
    exit;
}

$bidang = '';
// Jika form disubmit
if (isset($_POST['tambah'])) {
  $bidang = $_POST['nama'];


  // Simpan ke database
  $query = "INSERT INTO bidang(nama) VALUES ('$bidang')";
  if (mysqli_query($conn, $query)) {
    header("Location: kelola_bidang.php");
    exit;
  } else {
    echo "Gagal menyimpan data: " . mysqli_error($conn);
  }
}
?>
<!DOCTYPE html>
<html>
<head>
    <nav class="bg-[#5C7CFA] py-4 px-8 flex items-center justify-between">
    <div class="flex items-center space-x-2">
      <h1 class="text-2xl md:text-3xl font-bold text-white">Tambah Bidang</h1>
    </div>
    <div class="space-x-6">
     <a href="dashboard.php" class="text-white hover:underline">Dashboard</a>
       <a href="kelola_bidang.php" class="text-white hover:underline">Kelola Bidang</a>
      
    </div>
  </nav>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

  <form method="POST" class="bg-white p-4 rounded shadow-md w-full max-w-md">

     <label class="block mb-2">Bidang</label>
    <input type="text" name="nama" value="<?= htmlspecialchars($bidang) ?>" required class="w-full mb-4 p-2 border rounded">

    <button type="submit" name="tambah" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
    <a href="kelola_bidang.php" class="ml-4 text-gray-600 hover:underline">Kembali</a>
  </form>
</body>
</html>
