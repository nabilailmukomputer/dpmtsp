<?php include '../../db.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Tugas</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
  <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-xl font-bold mb-4">Tambah Tugas</h2>
    <form method="post">
      <input type="text" name="judul" placeholder="Judul Tugas" required class="w-full border p-2 rounded mb-4">
      <textarea name="deskripsi" placeholder="Deskripsi" required class="w-full border p-2 rounded mb-4"></textarea>
      <select name="status" class="w-full border p-2 rounded mb-4">
        <option value="Belum">Belum</option>
        <option value="Berlangsung">Berlangsung</option>
        <option value="Selesai">Selesai</option>
      </select>
      <button name="simpan" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
      <a href="detail_tugas.php" class="ml-4 text-gray-600 hover:underline">Kembali</a>
    </form>

    <?php
    if (isset($_POST['simpan'])) {
      $judul = $_POST['judul'];
      $deskripsi = $_POST['deskripsi'];
      $status = $_POST['status'];
      mysqli_query($conn, "INSERT INTO task (judul, deskripsi, status) VALUES ('$judul', '$deskripsi', '$status')");
      echo "<script>window.location='detail_tugas.php';</script>";
    }
    ?>
  </div>
</body>
</html>
