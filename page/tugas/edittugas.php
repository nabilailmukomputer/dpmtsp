<?php
include '../db.php';
$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM task WHERE id=$id"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Tugas</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
  <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-xl font-bold mb-4">Edit Tugas</h2>
    <form method="post">
      <input type="text" name="judul" value="<?= $data['judul'] ?>" required class="w-full border p-2 rounded mb-4">
      <textarea name="deskripsi" class="w-full border p-2 rounded mb-4"><?= $data['deskripsi'] ?></textarea>
      <select name="status" class="w-full border p-2 rounded mb-4">
        <option <?= $data['status'] == 'Belum' ? 'selected' : '' ?>>Belum</option>
        <option <?= $data['status'] == 'Berlangsung' ? 'selected' : '' ?>>Berlangsung</option>
        <option <?= $data['status'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
      </select>
      <button name="update" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">Update</button>
      <a href="detail_tugas.php" class="ml-4 text-gray-600 hover:underline">Kembali</a>
    </form>

    <?php
    if (isset($_POST['update'])) {
      $judul = $_POST['judul'];
      $deskripsi = $_POST['deskripsi'];
      $status = $_POST['status'];
      mysqli_query($koneksi, "UPDATE tugas SET judul='$judul', deskripsi='$deskripsi', status='$status' WHERE id=$id");
      echo "<script>window.location='detail_tugas.php';</script>";
    }
    ?>
  </div>
</body>
</html>
