<?php include '../../db.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Detail Tugas - SIMANTAP</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
  <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-lg">
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-2xl font-bold text-gray-800">Detail Tugas</h1>
      <a href="add_tugas.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Tambah Tugas</a>
    </div>
    <table class="w-full table-auto border-collapse">
      <thead>
        <tr class="bg-gray-200 text-gray-700">
          <th class="px-4 py-2 border">No</th>
          <th class="px-4 py-2 border">Judul</th>
          <th class="px-4 py-2 border">Deskripsi</th>
          <th class="px-4 py-2 border">Status</th>
          <th class="px-4 py-2 border">Aksi</th>
        </tr>
      </thead>
      <tbody>
       <?php
// Ambil data
$query = mysqli_query($conn, "SELECT * FROM task");
$no = 1;
while ($data = mysqli_fetch_assoc($query)) {
?>
  <tr class="text-gray-700 text-sm text-center">
    <td class="border px-4 py-2"><?= $no++; ?></td>
    <td class="border px-4 py-2"><?= $data['judul']; ?></td>
    <td class="border px-4 py-2"><?= $data['deskripsi']; ?></td>
    <td class="border px-4 py-2"><?= $data['status']; ?></td>
    <td class="border px-4 py-2">
      <a href="edittugas.php?id=<?= $data['id']; ?>" class="text-blue-600 hover:underline">Edit</a> | 
      <a href="?hapus=<?= $data['id']; ?>" onclick="return confirm('Yakin ingin hapus?')" class="text-red-600 hover:underline">Hapus</a>
    </td>
  </tr>
<?php
} // <--- ini penting, penutup while
?>
        </tbody>
      </table>
    </div>
  </body>
</html>

<?php
// Logika hapus, ini harus DI LUAR HTML/table
if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  mysqli_query($koneksi, "DELETE FROM task WHERE id=$id");
  echo "<script>window.location='detail_tugas.php';</script>";
}
?>
