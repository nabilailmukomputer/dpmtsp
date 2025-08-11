<?php
include '../../db.php';

// Ambil parameter status
$status = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : 'all';

// Query dasar
$query = "SELECT * FROM task";

// Tambahkan filter status
if ($status == 'selesai') {
    $query .= " WHERE status='Selesai'";
} elseif ($status == 'dikerjakan') {
    $query .= " WHERE status='dikerjakan'";
} elseif ($status == 'terlambat') {
    $query .= " WHERE status='Terlambat'";
}

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tugas (Admin)</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

<div class="max-w-6xl mx-auto mt-8 p-6 bg-white shadow-xl rounded-lg">
    <h2 class="text-3xl font-bold text-gray-800 mb-4">Daftar Tugas</h2>
    <p class="text-gray-600 mb-6">
        <?= ($status != 'all') ? "Status: " . ucfirst($status) : "Semua Tugas"; ?>
    </p>

    <div class="overflow-x-auto">
       <table class="w-full">
                <thead>
                    <tr class="bg-blue-600 text-white">
                    <th class="px-4 py-2 text-left">No</th>
                    <th class="px-4 py-2 text-left">Nama Tugas</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Deadline</th>
                    </tr>
                </thead>
                <tbody>
    <!-- baris data -->
  

                <?php
                if (mysqli_num_rows($result) > 0) {
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        // Tentukan warna badge berdasarkan status
                        $badgeColor = "bg-gray-500"; // default
                        if (strtolower($row['status']) == 'selesai') {
                            $badgeColor = "bg-green-500";
                        } elseif (strtolower($row['status']) == 'dikerjakan') {
                            $badgeColor = "bg-yellow-500";
                        } elseif (strtolower($row['status']) == 'terlambat') {
                            $badgeColor = "bg-red-500";
                        }
                        ?>
                        <tr class="hover:bg-gray-100 border-b">
                            <td class="py-2 px-4"><?= $no++; ?></td>
                            <td class="py-2 px-4"><?= htmlspecialchars($row['judul']); ?></td>
                            <td class="py-2 px-4 status">
                                <span class="<?= $badgeColor; ?> text-white px-3 py-1 rounded-full text-sm">
                                    <?= htmlspecialchars($row['status']); ?>
                                </span>
                            </td>
                            <td class="py-2 px-4"><?= htmlspecialchars($row['deadline']); ?></td>
                            <td class="py-2 px-4 text-green-600 font-bold countdown"
                                data-deadline="<?= htmlspecialchars($row['deadline']); ?>">
                               
                            </td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">Tidak ada data tugas.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>



</body>
</html>