<?php
include '../../db.php';

// Hapus admin
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM user WHERE id=$id");
    header('Location: kelola_admin.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-900 font-sans">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-[#0D2B53] text-white flex flex-col">
            <div class="flex items-center px-6 py-6 text-xl font-bold border-b border-gray-700">
                <img src="../../assets/s.png" alt="Logo" class="w-6 h-6" />
                <span class="ml-2">SIMANTAP</span>
            </div>

            <nav class="mt-2 px-4">
                <ul class="space-y-2 text-sm">
                    <li>
                        <a href="dashboard.php"
                            class="block py-2.5 px-2.5 font-bold rounded hover:bg-orange-500 hover:text-white transition duration-300 hover:translate-x-1 mb-2">
                            <span class="flex items-center gap-2">
                                <span class="material-icons text-[#F7EDED]">menu_book</span>
                                Dashboard
                            </span>
                        </a>
                    </li>
                </ul>

        <h2 class="text-[8px] font-bold text-gray-300 mb-2 ml-2">MENU UNTUK ADMIN</h2>
       <ul class="space-y-2 text-sm">
          <li>
            <a href="detail.php" class="block py-1 px-2 rounded hover:bg-orange-500 hover:text-white transition duration-300 hover:translate-x-1">Detail Tugas</a>
          </li>
          <li>
            <a href="laporan.php" class="block py-1 px-2 rounded hover:bg-orange-500 hover:text-white transition duration-300 hover:translate-x-1">Laporan Harian</a>
          </li>
          <li>
            <a href="tenggat.php" class="block py-1 px-2 rounded hover:bg-orange-500 hover:text-white transition duration-300 hover:translate-x-1">Permohonan Tenggat</a>
          </li>
          <li>
            <a href="kinerja_pegawai.php" class="block py-1 px-2 rounded hover:bg-orange-500 hover:text-white transition duration-300 hover:translate-x-1">Kinerja Pegawai</a>
          </li>
          <li>
            <a href="kelola_admin.php" class="block py-1 px-2 rounded hover:bg-orange-500 hover:text-white transition duration-300 hover:translate-x-1">Kelola Pengguna</a>
          </li>
          <li>
            <a href="kelola_bidang.php" class="block py-1 px-2 rounded hover:bg-orange-500 hover:text-white transition duration-300 hover:translate-x-1">Kelola Bidang</a>
          </li>
        </ul>
            </nav>

            <div class="mt-auto px-4 py-4 border-t border-gray-700">
                <a href="../logout.php"
                    class="flex items-center gap-2 text-gray-200 hover:text-red-400 text-sm transition duration-300">
                    <span class="material-icons">logout</span>
                    Logout
                </a>
            </div>
        </aside>

        <!-- Konten -->
        <main class="flex-1 overflow-auto">
            <!-- Header -->
            <nav class="bg-blue-600 py-4 px-8 shadow-md">
                <h1 class="text-2xl md:text-3xl font-bold text-white">Kelola Pengguna</h1>
            </nav>

            <div class="p-6">
                <!-- Search dan Tambah -->
                <div class="flex justify-between items-center mb-4">
                    <a href="form_tambah_admin.php"
                        class="bg-green-500 text-white px-4 py-2 rounded shadow hover:bg-green-600 transition">+
                        Tambah Pengguna</a>
                    <input type="text" placeholder="Cari pengguna..."
                        class="border rounded px-4 py-2 w-64 focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>

                <!-- Table -->
                <div class="overflow-x-auto bg-white shadow rounded-lg p-4">
                    <table class="w-full table-auto border-collapse">
                        <thead class="bg-gray-200 text-gray-700">
                            <tr>
                                <th class="px-4 py-2 text-left">No</th>
                                <th class="px-4 py-2 text-left">Username</th>
                                <th class="px-4 py-2 text-left">Password</th>
                                <th class="px-4 py-2 text-left">Role</th>
                                <th class="px-4 py-2 text-left">Bidang</th>
                                <th class="px-4 py-2 text-left">NIP</th>
                                <th class="px-4 py-2 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $query = mysqli_query($conn, "SELECT * FROM user");
                            while ($data = mysqli_fetch_array($query)) :
                            ?>
                            <tr class="border-b hover:bg-gray-100">
                                <td class="px-4 py-2"><?= $no++ ?></td>
                                <td class="px-4 py-2"><?= $data['nama'] ?></td>
                                <td class="px-4 py-2"><?= $data['password'] ?></td>
                                <td class="px-4 py-2"><?= $data['role'] ?></td>
                                <td class="px-4 py-2"><?= $data['bidang_id'] ?></td>
                                <td class="px-4 py-2"><?= $data['NIP'] ?></td>
                                <td class="px-4 py-2 text-center space-x-2">
                                    <a href="form_edit_admin.php?id=<?= $data['id'] ?>"
                                        class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition">Edit</a>
                                    <a href="?hapus=<?= $data['id'] ?>"
                                        onclick="return confirm('Yakin hapus admin ini?')"
                                        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">Hapus</a>
                                </td>
                            </tr>
                            <?php endwhile ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
