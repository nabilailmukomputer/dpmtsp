<?php
include '../../db.php';

// Ambil parameter status
$status = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : 'all';

// Query dasar
$query = "SELECT * FROM task";

// Tambahkan filter status
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
        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
            <thead>
                <tr class="bg-blue-600 text-white">
                    <th class="py-3 px-4 text-left">No</th>
                    <th class="py-3 px-4 text-left">Nama Tugas</th>
                    <th class="py-3 px-4 text-left">Status</th>
                    <th class="py-3 px-4 text-left">Deadline</th>
                    <th class="py-3 px-4 text-left">Sisa Waktu</th>
                </tr>
            </thead>
            <tbody>
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
                                data-deadline="<?= htmlspecialchars($row['deadline']); ?>"
                                data-status="<?= strtolower($row['status']); ?>">
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

<script>
// Parsing tanggal deadline format "YYYY-MM-DD HH:MM:SS" agar tidak salah timezone
function parseLocalDateTime(str) {
    let parts = str.split(/[- :]/);
    return new Date(parts[0], parts[1]-1, parts[2], parts[3], parts[4], parts[5]);
}

function updateCountdown() {
    document.querySelectorAll(".countdown").forEach(el => {
        let deadline = parseLocalDateTime(el.dataset.deadline);
        let now = new Date();
        let diff = deadline - now;

        if (diff > 0) {
            let hours = Math.floor(diff / (1000 * 60 * 60));
            let minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((diff % (1000 * 60)) / 1000);
            el.textContent = `${hours.toString().padStart(2,'0')}:${minutes.toString().padStart(2,'0')}:${seconds.toString().padStart(2,'0')}`;
        } else {
            el.textContent = "Waktu Habis";

            // Jika belum selesai, ubah status jadi Terlambat
            if (el.dataset.status !== 'selesai') {
                let statusCell = el.closest('tr').querySelector('.status');
                statusCell.innerHTML = '<span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm">Terlambat</span>';
            }
        }
    });
}

setInterval(updateCountdown, 1000);
updateCountdown();
</script>

</body>
</html>
