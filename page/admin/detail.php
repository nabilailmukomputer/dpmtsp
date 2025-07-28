<?php
include '../../db.php';
session_start();
date_default_timezone_set('Asia/Jakarta'); // Set timezone ke WITA
$now = time(); 

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
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
     <div class="flex h-screen">
    <!-- Sidebar -->
   <aside class="w-64 bg-[#0D2B53] text-white flex flex-col min-h-screen">
      <!-- Logo dan SIMANTAP -->
            <div class="flex items-center px-6 py-6 text-xl font-bold space-x-2">
                <img src="../../assets/s.png" alt="Logo" class="w-6 h-6" />
                <span>SIMANTAP</span>
            </div>

      <!-- Tombol Dashboard -->

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

       <div class="mt-auto px-4 py-4">
        <a href="../logout.php" class="flex items-center gap-2 text-white-500 hover:underline text-sm transition duration-300">
          <span class="material-icons">logout</span>
          <span>Logout</span>
        </a>
      </div>
    </aside>
    

<div class="p-6">
    <h1 class="text-3xl font-bold mb-6">Detail Tugas Pegawai</h1>
    <table class="min-w-full bg-white border rounded shadow-md">
        <thead class="bg-gray-200 text-gray-600">
            <tr>
                <th class="py-3 px-4 border">Judul Tugas</th>
                <th class="py-3 px-4 border">Deadline</th>
                <th class="py-3 px-4 border">Tanggal Penugasan</th>
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
