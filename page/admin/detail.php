<?php
include '../../db.php';
session_start();

if (!isset($_SESSION['nama']) ) {
    // Jika belum, redirect ke halaman login
    header('Location: ../login.php');
    exit;
}
date_default_timezone_set('Asia/Jakarta');

// Ambil semua tugas dengan join ke tabel user untuk dapat nama penerima
$query = "
SELECT t.id, t.judul, t.deadline, t.tanggal_tugas, t.status,
       u.nama AS penerima
FROM task t
JOIN user u ON t.assigned_to = u.id
ORDER BY t.id DESC
";
$result = mysqli_query($conn, $query);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Tugas Pegawai</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
      <div class="flex items-center justify-between px-6 py-6 text-xl font-bold">
        <div class="flex items-center space-x-2">
          <span class="menu-text">SIMANTAP</span>
        </div>
        <button id="toggle-btn" class="text-white focus:outline-none">
          <span class="material-icons">menu</span>
        </button>
      </div>

      <!-- Menu -->
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
            <a href="detail.php" class="flex items-center gap-2 py-1 px-2 rounded bg-orange-500">
              <span class="material-icons">assignment</span>
              <span class="menu-text">Detail Tugas</span>
            </a>
          </li>
          <li>
            <a href="laporan.php" class="flex items-center gap-2 py-1 px-2 rounded hover:bg-orange-500">
              <span class="material-icons">description</span>
              <span class="menu-text">Laporan Harian</span>
            </a>
          </li>
          <li>
            <a href="tenggat.php" class="flex items-center gap-2 py-1 px-2 rounded hover:bg-orange-500">
              <span class="material-icons">schedule</span>
              <span class="menu-text">Permohonan Tenggat</span>
            </a>
          </li>
          <li>
            <a href="kinerja_pegawai.php" class="flex items-center gap-2 py-1 px-2 rounded hover:bg-orange-500">
              <span class="material-icons">bar_chart</span>
              <span class="menu-text">Kinerja Pegawai</span>
            </a>
          </li>
          <li>
            <a href="kelola_admin.php" class="flex items-center gap-2 py-1 px-2 rounded hover:bg-orange-500">
              <span class="material-icons">manage_accounts</span>
              <span class="menu-text">Kelola Pengguna</span>
            </a>
          </li>
          <li>
            <a href="kelola_bidang.php" class="flex items-center gap-2 py-1 px-2 rounded hover:bg-orange-500">
              <span class="material-icons">apartment</span>
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

    <!-- Main Content -->
    <main class="flex-1 p-6">
        <h1 class="text-2xl font-bold mb-4">Detail Tugas Pegawai</h1>
        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="w-full table-auto border border-gray-300">
                <thead class="bg-gray-200 text-gray-800">
                    <tr>
                        <th class="border px-4 py-2">Judul Tugas</th>
                        <th class="border px-4 py-2">Deadline</th>
                        <th class="border px-4 py-2">Tanggal Penugasan</th>
                        <th class="border px-4 py-2">Penerima</th>
                        <th class="border px-4 py-2">Status</th>
                        <th class="border px-4 py-2">Sisa Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr class="text-center">
                            <td class="border px-4 py-2"><?= htmlspecialchars($row['judul']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($row['deadline']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($row['tanggal_tugas']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($row['penerima']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($row['status']) ?></td>
                            <td class="border px-4 py-2">
                                <?php if ($row['status'] == 'terlambat'): ?>
                                    <span class="bg-gray-500 text-white px-3 py-1 rounded">Terlambat!</span>
                                <?php else: ?>
                                    <span class="bg-green-500 text-white px-3 py-1 rounded">On Time</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

</script>
</body>
</html>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const timers = document.querySelectorAll("[id^='timer-']");

    timers.forEach(timer => {
        let remaining = parseInt(timer.dataset.remaining);
        const total = parseInt(timer.dataset.total);
        const taskId = timer.dataset.id;

        const interval = setInterval(() => {
            fetch(`get_status.php?id=${taskId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === "Selesai") {
                        timer.textContent = "Selesai";
                        timer.className = "timer-box bg-primary";
                        document.getElementById("status-" + taskId).textContent = "Selesai";
                        clearInterval(interval);
                        return;
                    }
                });

            if (remaining <= 0) {
                timer.textContent = "Terlambat!";
                timer.className = "timer-box bg-secondary";
                document.getElementById("status-" + taskId).textContent = "Terlambat";
                updateStatus(taskId, "Terlambat");
                clearInterval(interval);
                return;
            }

            const hours = Math.floor(remaining / 3600);
            const minutes = Math.floor((remaining % 3600) / 60);
            const seconds = remaining % 60;
            timer.textContent = `${hours.toString().padStart(2,'0')}:${minutes.toString().padStart(2,'0')}:${seconds.toString().padStart(2,'0')}`;

            const percent = remaining / total;
            if (percent > 0.5) {
                timer.className = "timer-box bg-success";
            } else {
                timer.className = "timer-box bg-danger";
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
