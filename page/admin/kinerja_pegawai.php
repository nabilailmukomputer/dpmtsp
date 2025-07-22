<?php
include '../../db.php';
session_start();

// Ambil semua user
$users = mysqli_query($conn, "SELECT * FROM user");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Kinerja Pegawai</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white p-6 text-gray-800 font-sans">

    <h1 class="text-2xl font-bold mb-4">Kinerja Pegawai</h1>

    <table class="w-full table-auto border border-gray-300 shadow">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-3 py-2">No</th>
                <th class="border px-3 py-2">Nama</th>
                <th class="border px-3 py-2">Jabatan</th>
                <th class="border px-3 py-2">Tugas Selesai</th>
                <th class="border px-3 py-2">Tugas Berlangsung</th>
                <th class="border px-3 py-2">Belum Dikerjakan</th>
                <th class="border px-3 py-2">Total Tugas</th>
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

</body>
</html>
