<?php
include '../../db.php';

if (!isset($_GET['id'])) {
  header("Location: kelola_admin.php");
  exit;
}

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM user WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (isset($_POST['update'])) {
  $username = $_POST['nama'];
  $password = $_POST['password'];
  $role = $_POST['role'];
  $bidang = $_POST['bidang'];
  $NIP = $_POST['NIP'];
  mysqli_query($conn, "UPDATE user SET nama='$username', password='$password' , role='$role', bidang='$bidang', NIP='$NIP' WHERE id=$id");
  header("Location: kelola_admin.php");
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <nav class="bg-[#5C7CFA] py-4 px-8 flex items-center justify-between">
    <div class="flex items-center space-x-2">
      <h1 class="text-2xl md:text-3xl font-bold text-white">Tambah Admin</h1>
    </div>
    <div class="space-x-6">
      <a href="dashboard.php" class="text-white hover:underline">Dashboard</a>
       <a href="kelola_admin.php" class="text-white hover:underline">Kelola Pengguna</a>
      
    </div>
  </nav>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-6 bg-gray-100">
  <form method="POST" class="bg-white p-4 rounded shadow-md w-full max-w-md">
    <label class="block mb-2">Username</label>
    <input type="text" name="nama" value="<?= $data['nama'] ?>" required class="w-full mb-4 p-2 border rounded">

    <label class="block mb-2">Password</label>
    <input type="text" name="password" value="<?= $data['password'] ?>" required class="w-full mb-4 p-2 border rounded">
    <select name="role" required class="w-full mb-4 p-2 border rounded">
                <option value="-">-</option>
                <option value="admin">Admin</option>
                <option value="penanggung jawab">Penanggung Jawab</option>
                <option value="pengendali teknis">Pengendali Teknis</option>
                <option value="ketua divisi">Ketua Divisi</option>
                <option value="anggota">Anggota </option>
            </select>
             <label class="block mb-2">Bidang</label>
    <input type="text" name="bidang" value="<?= htmlspecialchars($data['bidang']) ?>" required class="w-full mb-4 p-2 border rounded">
               
            <label class="block mb-2">NIP</label>
    <input type="text" name="NIP" value="<?= htmlspecialchars($data['NIP']) ?>" required class="w-full mb-4 p-2 border rounded">

    <button type="submit" name="update" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
    <a href="kelola_admin.php" class="ml-4 text-gray-600 hover:underline">Batal</a>
  </form>
</body>
</html>
