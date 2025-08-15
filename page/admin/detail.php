<?php
include '../../db.php';
session_start();

if (!isset($_SESSION['nama'])) {
    header('Location: ../login.php');
    exit;
}
date_default_timezone_set('Asia/Jakarta');

// ✅ Tambah handler untuk update waktu & status langsung dari fetch()
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['status'])) {
    $id = intval($_POST['id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $sisa_waktu = isset($_POST['sisa_waktu']) ? intval($_POST['sisa_waktu']) : null;
    $waktu_terlambat = isset($_POST['waktu_terlambat']) ? intval($_POST['waktu_terlambat']) : null;

    if ($sisa_waktu !== null) {
        $sql = "UPDATE task SET status='$status', sisa_waktu=$sisa_waktu, waktu_terlambat=NULL WHERE id=$id";
    } elseif ($waktu_terlambat !== null) {
        $sql = "UPDATE task SET status='$status', waktu_terlambat=$waktu_terlambat, sisa_waktu=NULL WHERE id=$id";
    } else {
        $sql = "UPDATE task SET status='$status' WHERE id=$id";
    }
    mysqli_query($conn, $sql);
    echo "OK";
    exit;
}

// =================== TAMPILAN LIST ===================
$search = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : '';

$query = "
SELECT t.id, t.judul, t.deadline, t.tanggal_tugas, t.status, t.sisa_waktu, t.waktu_terlambat,
       u.nama AS penerima
FROM task t
JOIN user u ON t.assigned_to = u.id
WHERE t.judul LIKE '%$search%' OR u.nama LIKE '%$search%'
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
    body { font-family: 'Segoe UI', sans-serif; }
    .sidebar-collapsed { width: 80px !important; }
    .sidebar-collapsed .menu-text, .sidebar-collapsed h2 { display: none; }
  </style>
</head>
<body class="bg-gray-100">

<div class="flex h-screen">
    <!-- Sidebar -->
    <aside id="sidebar" class="w-64 bg-[#0D2B53] text-white flex flex-col min-h-screen transition-all duration-300">
      <div class="flex items-center justify-between px-6 py-6 text-xl font-bold">
        <span class="menu-text">SIMANTAP</span>
        <button id="toggle-btn" class="text-white focus:outline-none">
          <span class="material-icons">menu</span>
        </button>
      </div>
      <nav class="mt-2 px-4">
        <ul class="space-y-2 text-sm">
          <li>
            <a href="dashboard.php" class="flex items-center gap-2 py-2.5 px-2.5 font-bold rounded hover:bg-orange-500 transition duration-300">
              <span class="material-icons text-[#F7EDED]">menu_book</span>
              <span class="menu-text">Dashboard</span>
            </a>
          </li>
        </ul>
        <h2 class="text-[8px] font-bold text-gray-300 mb-2 ml-2">MENU UNTUK ADMIN</h2>
        <ul class="space-y-2 text-sm">
          <li><a href="detail.php" class="flex items-center gap-2 py-1 px-2 rounded bg-orange-500"><span class="material-icons">assignment</span><span class="menu-text">Detail Tugas</span></a></li>
          <li><a href="laporan.php" class="flex items-center gap-2 py-1 px-2 rounded hover:bg-orange-500"><span class="material-icons">description</span><span class="menu-text">Laporan Harian</span></a></li>
          <li><a href="tenggat.php" class="flex items-center gap-2 py-1 px-2 rounded hover:bg-orange-500"><span class="material-icons">schedule</span><span class="menu-text">Permohonan Tenggat</span></a></li>
          <li><a href="kinerja_pegawai.php" class="flex items-center gap-2 py-1 px-2 rounded hover:bg-orange-500"><span class="material-icons">bar_chart</span><span class="menu-text">Kinerja Pegawai</span></a></li>
          <li><a href="kelola_admin.php" class="flex items-center gap-2 py-1 px-2 rounded hover:bg-orange-500"><span class="material-icons">manage_accounts</span><span class="menu-text">Kelola Pengguna</span></a></li>
          <li><a href="kelola_bidang.php" class="flex items-center gap-2 py-1 px-2 rounded hover:bg-orange-500"><span class="material-icons">apartment</span><span class="menu-text">Kelola Bidang</span></a></li>
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
        <div class="mb-4">
            <form method="GET" class="flex space-x-2">
                <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Cari judul atau penerima..." class="border rounded px-4 py-2 w-64">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Cari</button>
            </form>
        </div>
        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="w-full table-auto border border-gray-300">
                <thead class="bg-gray-200 text-gray-800">
                    <tr>
                        <th class="border px-4 py-2">Judul Tugas</th>
                        <th class="border px-4 py-2">Penerima</th>
                        <th class="border px-4 py-2">Tanggal Penugasan</th>
                        <th class="border px-4 py-2">Deadline</th>
                        <th class="border px-4 py-2">Sisa Waktu</th>
                        <th class="border px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                   <?php
// ...
while($row = mysqli_fetch_assoc($result)):
    $deadline = strtotime($row['deadline']);
    $assigned = strtotime($row['tanggal_tugas']);
    $total = max(1, $deadline - $assigned);

    // Bagian PHP perhitungan waktu
if ($row['status'] === 'selesai') {
    if ($row['sisa_waktu'] !== null && $row['sisa_waktu'] !== '') {
        $remaining = (int)$row['sisa_waktu']; // waktu saat selesai
    } elseif ($row['waktu_terlambat'] !== null && $row['waktu_terlambat'] !== '') {
        $remaining = -(int)$row['waktu_terlambat']; // telat → minus
    } else {
        $remaining = 0;
    }
} else {
    $remaining = $deadline - time(); // tetap bisa minus
}

?>
<tr class="text-center">
    <td class="border px-4 py-2"><?= htmlspecialchars($row['judul']) ?></td>
    <td class="border px-4 py-2"><?= htmlspecialchars($row['penerima']) ?></td>
    <td class="border px-4 py-2"><?= htmlspecialchars($row['tanggal_tugas']) ?></td>
    <td class="border px-4 py-2"><?= htmlspecialchars($row['deadline']) ?></td>
    <td class="border px-4 py-2">
        <span id="timer-<?= $row['id']; ?>"
              class="timer-box <?= ($remaining < 0 ? 'bg-gray-500' : 'bg-green-600') ?> text-white px-3 py-1 rounded"
              data-remaining="<?= $remaining; ?>"
              data-total="<?= $total; ?>"
              data-id="<?= $row['id']; ?>"
              data-status="<?= $row['status']; ?>">
              <?= ($remaining < 0 ? '-' : '') . gmdate("H:i:s", abs($remaining)); ?>
        </span>
    </td>
    <td id="status-<?= $row['id']; ?>" class="border px-4 py-2"><?= htmlspecialchars($row['status']) ?></td>
</tr>
<?php endwhile; ?>

                </tbody>
            </table>
        </div>
    </main>
</div>

<script>
function updateTimerDisplay(timer, remaining) {
  const isNegative = remaining < 0;
  const absTime = Math.abs(remaining);
  const hours = Math.floor(absTime / 3600).toString().padStart(2, "0");
  const minutes = Math.floor((absTime % 3600) / 60).toString().padStart(2, "0");
  const seconds = (absTime % 60).toString().padStart(2, "0");
  timer.textContent = `${isNegative ? "-" : ""}${hours}:${minutes}:${seconds}`;
}

function kirimWaktuKeDB(id, remaining) {
  const data = new URLSearchParams();
  data.append("id", id);
  data.append("status", "selesai");

  if (remaining >= 0) {
    data.append("sisa_waktu", remaining);
  } else {
    data.append("waktu_terlambat", Math.abs(remaining));
  }

  // kirim POST ke file ini sendiri
  fetch(window.location.href, {
    method: "POST",
    body: data
  })
    .then(res => res.text())
    .then(res => {
      console.log("Respon:", res);
      if (res.trim() === "OK") {
        // update tampilan tanpa reset ke 00
        const timerEl = document.querySelector(`#timer-${id}`);
        updateTimerDisplay(timerEl, remaining);
        document.querySelector(`#status-${id}`).textContent = "selesai";
      }
    })
    .catch(err => console.error("Error:", err));
}

document.addEventListener("DOMContentLoaded", function () {
  const timers = document.querySelectorAll("[id^='timer-']");

  timers.forEach(timer => {
    let remaining = parseInt(timer.dataset.remaining);
    const total = parseInt(timer.dataset.total);
    const status = timer.dataset.status.toLowerCase();
    const id = timer.dataset.id;

    if (status === "selesai") {
      updateTimerDisplay(timer, remaining);
      return;
    }

    const interval = setInterval(() => {
      remaining -= 1;
      updateTimerDisplay(timer, remaining);

      let colorClass = "bg-green-600";
      if (remaining < 0) {
        colorClass = "bg-gray-500";
      } else if (remaining <= total / 2) {
        colorClass = "bg-red-500";
      }
      timer.className = `timer-box ${colorClass} text-white px-3 py-1 rounded`;
    }, 1000);

    // Klik untuk selesai
    timer.addEventListener("click", function () {
      clearInterval(interval);
      kirimWaktuKeDB(id, remaining);
    });
  });
});

</script>
</body>
</html>
