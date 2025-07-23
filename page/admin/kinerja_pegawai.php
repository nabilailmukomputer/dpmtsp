<?php
include '../../db.php';
session_start();

// Ambil semua user
$users = mysqli_query($conn, 'SELECT * FROM user');

?>

<!DOCTYPE html>
<html>

<head>
    <title>Kinerja Pegawai</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=search" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white text-gray-800 font-sans">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-[#0D2B53] text-white flex flex-col min-h-screen">
            <!-- Logo dan SIMANTAP -->
            <div class="flex items-center px-6 py-6 text-xl font-bold space-x-2">
                <img src="assets/titik_tiga.png" alt="Logo" class="w-6 h-6" />
                <span>SIMANTAP</span>
            </div>

            <!-- Menu Admin -->
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
                        <a href="detail.php"
                            class="block py-2 px-2 rounded hover:bg-orange-500 hover:text-white transition duration-300 hover:translate-x-1">Detail
                            Tugas</a>
                    </li>
                    <li>
                        <a href="laporan.php"
                            class="block py-2 px-2 rounded hover:bg-orange-500 hover:text-white transition duration-300 hover:translate-x-1">Laporan
                            Harian</a>
                    </li>
                    <li>
                        <a href="tenggat.php"
                            class="block py-2 px-2 rounded hover:bg-orange-500 hover:text-white transition duration-300 hover:translate-x-1">Permohonan
                            Tenggat</a>
                    </li>
                    <li>
                        <a href="kinerja_pegawai.php"
                            class="block py-2 px-2 rounded hover:bg-orange-500 hover:text-white transition duration-300 hover:translate-x-1">Kinerja
                            Pegawai</a>
                    </li>
                    <li>
                        <a href="kelola_admin.php"
                            class="block py-2 px-2 rounded hover:bg-orange-500 hover:text-white transition duration-300 hover:translate-x-1">Kelola
                            Pengguna</a>
                    </li>
                </ul>
            </nav>

            <div class="mt-auto px-4 py-4">
                <a href="../logout.php"
                    class="flex items-center gap-2 text-white-500 hover:underline text-sm transition duration-300">
                    <span class="material-icons">logout</span>
                    <span>Logout</span>
                </a>
            </div>
        </aside>
        <div class="ml-2 mt-4">
    <h1 class="text-2xl font-bold mb-4">Kinerja Pegawai</h1>
    <table class="w-full table-auto border border-gray-300 rounded shadow-md">
        <thead class="bg-gray-100 text-gray-800 font-semibold">
            <tr>
                <th class="border px-4 py-2 text-left">No</th>
                <th class="border px-4 py-2 text-left">Nama</th>
                <th class="border px-4 py-2 text-left">Jabatan</th>
                <th class="border px-4 py-2 text-left">Tugas Selesai</th>
                <th class="border px-4 py-2 text-left">Tugas Berlangsung</th>
                <th class="border px-4 py-2 text-left">Belum Dikerjakan</th>
                <th class="border px-4 py-2 text-left">Total Tugas</th>
            </tr>
        </thead>
            <tbody>
                <?php
            $no = 1;
            while ($user = mysqli_fetch_assoc($users)) :
                $id_user = $user['id'];

                $total_selesai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM task WHERE id_user = $id_user AND status = 'Selesai'"))['total'];
                $total_berlangsung = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM task WHERE id_user = $id_user AND status = 'Berlangsung'"))['total'];
                $total_belum = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM task WHERE id_user = $id_user AND status = 'Belum'"))['total'];
                $total_semua = $total_selesai + $total_berlangsung + $total_belum;
            ?>
                <tr class="hover:bg-gray-50">
                    <td class="border px-3 py-2"><?= $no++ ?></td>
                    <td class="border px-3 py-2"><?= $user['nama'] ?></td>
                    <td class="border px-3 py-2"><?= $user['role'] ?></td>
                    <td class="border px-3 py-2 text-green-600 font-bold"><?= $total_selesai ?></td>
                    <td class="border px-3 py-2 text-blue-600 font-bold"><?= $total_berlangsung ?></td>
                    <td class="border px-3 py-2 text-red-600 font-bold"><?= $total_belum ?></td>
                    <td class="border px-3 py-2 font-bold"><?= $total_semua ?></td>
                </tr>
                <?php endwhile ?>
            </tbody>
        </table>
    </div>
</body>

</html>
