<?php
include '../../db.php';
session_start();

if (!isset($_SESSION['user_id']) ) {
    // Jika belum, redirect ke halaman login
    header('Location: ../login.php');
    exit;
}
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
      <style>
    body {
      font-family: 'Segoe UI', sans-serif;
    }
    .sidebar-collapsed {
      width: 80px !important;
    }
    .sidebar-collapsed .menu-text,
    .sidebar-collapsed h2 {
      display: none;
    }
  </style>
</head>
<body class="bg-gray-100">
     <div class="flex h-screen">
    <!-- Sidebar -->
    <aside id="sidebar" class="w-64 bg-[#0D2B53] text-white flex flex-col min-h-screen transition-all duration-300">
      <!-- Logo dan SIMANTAP -->
      <div class="flex items-center justify-between px-6 py-6 text-xl font-bold">
        <div class="flex items-center space-x-2">
          <!-- <img src="../../assets/s.png" alt="Logo" class="w-6 h-6" /> -->
          <span class="menu-text">SIMANTAP</span>
        </div>

        <!-- Tombol Toggle -->
        <button id="toggle-btn" class="text-white focus:outline-none">
          <span class="material-icons">menu</span>
        </button>
      </div>

      <!-- Menu Admin -->
     <nav class="mt-2 px-4">
                        <ul class="space-y-2 text-sm">
                    <li>
                        <a href="dashboard.php"
                            class="flex items-center gap-2 py-2.5 px-2.5 font-bold rounded hover:bg-orange-500 transition duration-300">
                                <span class="material-icons text-[#F7EDED]">menu_book</span>
                                <span class="menu-text">Dashboard</span>
                        </a>
                    </li>
                </ul>
        <h2 class="text-[8px] font-bold text-gray-300 mb-2 ml-2">MENU UNTUK ADMIN</h2>
        <ul class="space-y-2 text-sm">
          <li>
            <a href="detail.php" class="flex items-center gap-2 py-1 px-2 rounded hover:bg-orange-500 transition">
                              <span class="material-icons">assignment</span>
              <span class="menu-text">Detail Tugas</span>
            </a>
          </li>
          <li>
            <a href="laporan.php" class="flex items-center gap-2 py-1 px-2 rounded hover:bg-orange-500 transition">
            <span class="material-icons">description</span>  
            <span class="menu-text">Laporan Harian</span>
            </a>
          </li>
          <li>
            <a href="tenggat.php" class="flex items-center gap-2 py-1 px-2 rounded hover:bg-orange-500 transition">   <span class="material-icons">schedule</span>
              <span class="menu-text">Permohonan Tenggat</span>
            </a>
          </li>
          <li>
            <a href="kinerja_pegawai.php" class="flex items-center gap-2 py-1 px-2 rounded hover:bg-orange-500 transition">              <span class="material-icons">bar_chart</span>
              <span class="menu-text">Kinerja Pegawai</span>
            </a>
          </li>
          <li>
            <a href="kelola_admin.php" class="flex items-center gap-2 py-1 px-2 rounded hover:bg-orange-500 transition">              <span class="material-icons">manage_accounts</span>
              <span class="menu-text">Kelola Pengguna</span>
            </a>
          </li>
          <li>
            <a href="kelola_bidang.php" class="flex items-center gap-2 py-1 px-2 rounded hover:bg-orange-500 transition">              <span class="material-icons">apartment</span>
              <span class="menu-text">Kelola Bidang</span>
            </a>
          </li>
        </ul>
      </nav>

       <div class="mt-auto px-4 py-4">
        <a href="../logout.php" class="flex items-center gap-2 text-sm hover:underline">
          <span class="material-icons">logout</span>
          <span class="menu-text">Logout</span>
        </a>
      </div>
    </aside>
    

<div class="flex-1 p-6">
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

  <!-- JavaScript untuk Toggle Sidebar -->
  <script>
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggle-btn');

    toggleBtn.addEventListener('click', () => {
      sidebar.classList.toggle('sidebar-collapsed');
    });
  </script>

</body>
</html>
