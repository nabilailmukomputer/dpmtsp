<?php
include '../../db.php';
session_start();

$query = "SELECT t.*, 
                u1.nama AS penerima, 
                u2.nama AS pemberi
          FROM task t 
          LEFT JOIN user u1 ON t.assigned_to = u1.id 
          LEFT JOIN user u2 ON t.created_by = u2.id 
          ORDER BY t.deadline ASC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Tugas</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="p-6">
    <h1 class="text-3xl font-bold mb-6">Detail Tugas Pegawai</h1>
    <table class="min-w-full bg-white border rounded shadow-md">
        <thead class="bg-gray-200 text-gray-600">
            <tr>
                <th class="py-3 px-4 border">Judul Tugas</th>
                <th class="py-3 px-4 border">Deadline</th>
                <th class="py-3 px-4 border">Tanggal Penugasan</th>
                <th class="py-3 px-4 border">Pemberi</th>
                <th class="py-3 px-4 border">Penerima</th>
                <th class="py-3 px-4 border">Status</th>
                <th class="py-3 px-4 border">Sisa Waktu</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <?php
                $start_time = strtotime($row['tanggal_tugas']); 
                $deadline_time = strtotime($row['deadline']);
                $now = time();
                $total_time = $deadline_time - $start_time; 
                $remaining_time = $deadline_time - $now; 
            ?>
            <tr class="hover:bg-gray-100">
                <td class="py-2 px-4 border"><?= htmlspecialchars($row['judul']) ?></td>
                <td class="py-2 px-4 border"><?= htmlspecialchars($row['deadline']) ?></td>
                <td class="py-2 px-4 border"><?= htmlspecialchars($row['tanggal_tugas']) ?></td>
                <td class="py-2 px-4 border"><?= htmlspecialchars($row['created_by']) ?></td>
                <td class="py-2 px-4 border"><?= htmlspecialchars($row['assigned_to']) ?></td>
                <td class="py-2 px-4 border" id="status-<?= $row['id'] ?>"><?= htmlspecialchars($row['status']) ?></td>
                <td class="py-2 px-4 border">
                    <span id="timer-<?= $row['id'] ?>" class="px-2 py-1 rounded text-white font-bold"
                          data-remaining="<?= $remaining_time ?>"
                          data-total="<?= $total_time ?>"
                          data-id="<?= $row['id'] ?>"
                          data-status="<?= $row['status'] ?>"></span>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const timers = document.querySelectorAll("[id^='timer-']");

    timers.forEach(timer => {
        let remaining = parseInt(timer.dataset.remaining);
        const total = parseInt(timer.dataset.total);
        const taskId = timer.dataset.id;

        const interval = setInterval(() => {
            // **REAL-TIME STATUS UPDATE**
            fetch(`get_status.php?id=${taskId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === "selesai") {
                        timer.textContent = "selesai";
                        timer.className = "bg-green-500 px-2 py-1 rounded text-white font-bold";
                        document.getElementById("status-" + taskId).textContent = "selesai";
                        clearInterval(interval); // stop timer
                        return;
                    }
                });

            if (remaining <= 0) {
                timer.textContent = "Terlambat!";
                timer.className = "bg-red-600 px-2 py-1 rounded text-white font-bold";
                document.getElementById("status-" + taskId).textContent = "Terlambat";
                updateStatus(taskId, "Terlambat");
                clearInterval(interval);
                return;
            }

            const hours = Math.floor(remaining / 3600);
            const minutes = Math.floor((remaining % 3600) / 60);
            const seconds = remaining % 60;
            timer.textContent = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

            const percent = remaining / total;
            if (percent > 0.66) {
                timer.className = "bg-green-500 px-2 py-1 rounded text-white font-bold";
            } else if (percent > 0.33) {
                timer.className = "bg-yellow-400 px-2 py-1 rounded text-black font-bold";
            } else {
                timer.className = "bg-red-500 px-2 py-1 rounded text-white font-bold";
            }

            remaining--;
        }, 1000);
    });
});

function updateStatus(id, status) {
    fetch("update_status.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: `id=${id}&status=${status}`
    });
}
</script>

</body>
</html>
