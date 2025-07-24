<?php
include '../../db.php';
$status = isset($_GET['status']) ? $_GET['status'] : 'all';

// Buat query dasar
$query = "SELECT * FROM task";

// Filter berdasarkan status
if ($status == 'selesai') {
    $query .= " WHERE status='Selesai'";
} elseif ($status == 'berlangsung') {
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
    <title>Daftar Tugas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

<div class="max-w-5xl mx-auto mt-8 p-6 bg-white shadow-xl rounded-lg">
    <h2 class="text-3xl font-bold text-gray-800 mb-4">Daftar Tugas</h2>
    <p class="text-gray-600 mb-6">
        <?= ($status != 'all') ? "Status: " . ucfirst($status) : "Semua Tugas"; ?>
    </p>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
            <thead>
                <tr class="bg-blue-600 text-white">
                    <th class="py-3 px-4 text-left">No</th>
                    <th class="py-3 px-4 text-left">Nama Tugas</th>
                    <th class="py-3 px-4 text-left">Status</th>
                    <th class="py-3 px-4 text-left">Deadline</th>
                </tr>
            </thead>
            <tbody>
                <?php
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
                        <td class="py-2 px-4">
                            <span class="<?= $badgeColor; ?> text-white px-3 py-1 rounded-full text-sm">
                                <?= htmlspecialchars($row['status']); ?>
                            </span>
                        </td>
                        <td class="py-2 px-4"><?= htmlspecialchars($row['deadline']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
