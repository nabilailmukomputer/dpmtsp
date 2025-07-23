<?php
include '../../db.php';

// Inisialisasi nilai kosong agar tidak error saat form pertama kali dibuka
$nama = '';
$password = '';
$role = '';
$bidang = '';
$NIP ='';
// Jika form disubmit
if (isset($_POST['tambah'])) {
  $nama = $_POST['nama'];
  $password = $_POST['password'];
  $role = $_POST['role'];
  $bidang = $_POST['bidang'];
  $NIP = $_POST['NIP'];

  // Simpan ke database
  $query = "INSERT INTO user (nama, password, role, bidang,NIP) VALUES ('$nama', '$password', '$role', '$bidang','$NIP')";
  if (mysqli_query($conn, $query)) {
    header("Location: kelola_admin.php");
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
      <h1 class="text-2xl md:text-3xl font-bold text-white">Tambah Admin</h1>
    </div>
    <div class="space-x-6">
     <a href="dashboard.php" class="text-white hover:underline">Dashboard</a>
       <a href="kelola_admin.php" class="text-white hover:underline">Kelola Pengguna</a>
      
    </div>
  </nav>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

  <form method="POST" class="bg-white p-4 rounded shadow-md w-full max-w-md">
    <label class="block mb-2">Nama</label>
    <input type="text" name="nama" value="<?= htmlspecialchars($nama) ?>" required class="w-full mb-4 p-2 border rounded">

    <label class="block mb-2">Password</label>
    <input type="text" name="password" value="<?= htmlspecialchars($password) ?>" required class="w-full mb-4 p-2 border rounded">

    <label class="block mb-2">Role</label>
    <select name="role" required class="w-full mb-4 p-2 border rounded">
      <option value="-">-</option>
      <option value="admin" <?= $role == 'admin' ? 'selected' : '' ?>>Admin</option>
      <option value="penanggung jawab" <?= $role == 'penanggung jawab' ? 'selected' : '' ?>>Penanggung Jawab</option>
      <option value="pengendali teknis" <?= $role == 'pengendali teknis' ? 'selected' : '' ?>>Pengendali Teknis</option>
      <option value="ketua divisi" <?= $role == 'ketua divisi' ? 'selected' : '' ?>>Ketua Divisi</option>
      <option value="anggota" <?= $role == 'anggota' ? 'selected' : '' ?>>Anggota</option>
    </select>

     <label class="block mb-2">Bidang</label>
    <input type="text" name="bidang" value="<?= htmlspecialchars($bidang) ?>" required class="w-full mb-4 p-2 border rounded">


   
    <label class="block mb-2">NIP</label>
    <input type="text" name="NIP" value="<?= htmlspecialchars($NIP) ?>" required class="w-full mb-4 p-2 border rounded">

    <button type="submit" name="tambah" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
    <a href="kelola_admin.php" class="ml-4 text-gray-600 hover:underline">Kembali</a>
  </form>
</body>
</html>
