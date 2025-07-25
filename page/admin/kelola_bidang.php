<?php
include '../../db.php';

// Hapus admin
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM bidang WHERE id=$id");
    header('Location: kelola_bidang.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Bidang</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white text-gray-900 font-sans">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-[#0D2B53] text-white flex flex-col">
            <div class="flex items-center px-6 py-6 text-xl font-bold space-x-2">
                <img src="assets/titik_tiga.png" alt="Logo" class="w-6 h-6" />
                <span>SIMANTAP</span>
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
                    <li><a href="detail.php"
                            class="block py-1 px-2 rounded hover:bg-orange-500 hover:text-white transition duration-300 hover:translate-x-1">Detail
                            Tugas</a></li>
                    <li><a href="laporan.php"
                            class="block py-1 px-2 rounded hover:bg-orange-500 hover:text-white transition duration-300 hover:translate-x-1">Laporan
                            Harian</a></li>
                    <li><a href="tenggat.php"
                            class="block py-1 px-2 rounded hover:bg-orange-500 hover:text-white transition duration-300 hover:translate-x-1">Permohonan
                            Tenggat</a></li>
                    <li><a href="kinerja_pegawai.php"
                            class="block py-1 px-2 rounded hover:bg-orange-500 hover:text-white transition duration-300 hover:translate-x-1">Kinerja
                            Pegawai</a></li>
                    <li><a href="kelola_admin.php"
                            class="block py-1 px-2 rounded hover:bg-orange-500 hover:text-white transition duration-300 hover:translate-x-1">Kelola
                            Pengguna</a></li>
                                      <li>
            <a href="kelola_bidang.php" class="block py-1 px-2 rounded hover:bg-orange-500 hover:text-white transition duration-300 hover:translate-x-1">Kelola Bidang</a>
          </li>
                </ul>
            </nav>

            <div class="mt-auto px-4 py-4 border-t border-gray-700">
                <a href="../logout.php"
                    class="flex items-center gap-2 text-white-500 hover:underline text-sm transition duration-300">
                    <span class="material-icons">logout</span>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        <!-- Konten -->
        <main class="flex-1 overflow-auto">
            <nav class="bg-blue-600 py-4 px-8 shadow-md">
                <h1 class="text-2xl md:text-3xl font-bold text-white">Kelola Bidang</h1>
            </nav>

            <div class="p-6">
                <a href="form_tambah_bidang.php"
                    class="bg-green-500 text-white px-4 py-2 rounded inline-block mb-4 hover:bg-green-600 transition">+
                    Tambah Bidang</a>

                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto border border-gray-300 rounded shadow text-sm">
                        <thead class="bg-gray-100 text-gray-800">
                            <tr>
                                <th class="border px-4 py-2 text-left">No</th>
                                <th class="border px-4 py-2 text-left">Bidang</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $query = mysqli_query($conn, "SELECT * FROM bidang");
                            while ($data = mysqli_fetch_array($query)) :
                            ?>
                            <tr class="hover:bg-gray-50">
                                <td class="border px-4 py-2"><?= $no++ ?></td>
                                <td class="border px-4 py-2"><?= $data['nama'] ?></td>
                                <td class="border px-4 py-2 space-x-2">
                                    <a href="form_edit_bidang.php?id=<?= $data['id'] ?>"
                                        class="text-blue-600 hover:underline">Edit</a>
                                    <a href="?hapus=<?= $data['id'] ?>"
                                        onclick="return confirm('Yakin hapus bidang ini?')"
                                        class="text-red-600 hover:underline">Hapus</a>
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
