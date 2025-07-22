<?php
include '../../db.php';

// Hapus admin
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM user WHERE id=$id");
    header("Location: kelola_admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Kelola Pengguna</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<nav class="bg-[#5C7CFA] py-4 px-8 flex items-center justify-between">
    <div class="flex items-center space-x-2">
      <h1 class="text-2xl md:text-3xl font-bold text-white">Kelola Admin</h1>
    </div>
    <div class="space-x-6">
     <a href="dashboard.php" class="text-white hover:underline">Dashboard</a>
       <a href="kelola_admin.php" class="text-white hover:underline">Kelola Pengguna</a>
      
    </div>
  </nav>
<body class="bg-white text-gray-900 font-sans p-6">

  <a href="form_tambah_admin.php" class="bg-green-500 text-white px-4 py-2 rounded inline-block mb-4">+ Tambah Pengguna</a>

  <table class="table-auto w-full border-collapse border border-gray-300 bg-white shadow">
    <thead class="bg-gray-200">
      <tr>
        <th class="border px-4 py-2">No</th>
        <th class="border px-4 py-2">Username</th>
        <th class="border px-4 py-2">Password</th>
        <th class="border px-4 py-2">role</th>
        <th class="border px-4 py-2">bidang</th>
        <th class="border px-4 py-2">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $no = 1;
      $query = mysqli_query($conn, "SELECT * FROM user");
      while ($data = mysqli_fetch_array($query)) :
      ?>
        <tr class="hover:bg-gray-100">
          <td class="border px-4 py-2"><?= $no++ ?></td>
          <td class="border px-4 py-2"><?= $data['nama'] ?></td>
          <td class="border px-4 py-2"><?= $data['password'] ?></td>
          <td class="border px-4 py-2"><?= $data['role'] ?></td>
          <td class="border px-4 py-2"><?= $data['bidang'] ?></td>
          <td class="border px-4 py-2">
            <a href="form_edit_admin.php?id=<?= $data['id'] ?>" class="text-blue-600 hover:underline mr-2">Edit</a>
            <a href="?hapus=<?= $data['id'] ?>" onclick="return confirm('Yakin hapus admin ini?')" class="text-red-600 hover:underline">Hapus</a>
          </td>
        </tr>
      <?php endwhile ?>
    </tbody>
  </table>

</body>
</html>
