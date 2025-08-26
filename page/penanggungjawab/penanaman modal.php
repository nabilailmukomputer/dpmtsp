<?php
include '../../db.php';
session_start();

// Jika belum login, redirect
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Ambil halaman dari query string
$page = isset($_GET['page']) ? $_GET['page'] : 'semua';
$valid_pages = ['semua', 'orang', 'selesai'];
if (!in_array($page, $valid_pages)) {
    $page = 'semua';
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Kesekretariatan</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<style>
    body { font-family: 'Segoe UI', sans-serif; }
    .sidebar-collapsed { width: 80px !important; }
    .sidebar-collapsed .menu-text,
    .sidebar-collapsed h2 { display: none; }
    .active-tab { background-color: orange; color: white !important; }
</style>
</head>
<body class="bg-gray-100">

<div class="flex h-screen">
    <!-- Sidebar -->
    <aside id="sidebar" class="w-64 bg-[#0D2B53] text-white flex flex-col min-h-screen transition-all duration-300">
        <div class="flex items-center justify-between px-6 py-6 text-xl font-bold">
            <span class="menu-text">SIMANTAP</span>
            <button id="toggle-btn" class="text-white"><span class="material-icons">menu</span></button>
        </div>
        <nav class="mt-2 px-4">
            <ul class="space-y-2 text-sm">
                <li><a href="dashboard.php" class="flex items-center gap-2 py-2 px-2 hover:bg-orange-500 rounded"><span class="material-icons">menu_book</span><span class="menu-text">Dashboard</span></a></li>
            </ul>
            <h2 class="text-[8px] font-bold text-gray-300 mb-2 ml-2">MENU UNTUK PJ</h2>
            <ul class="space-y-2 text-sm">
                <li><a href="?page=semua" class="flex items-center gap-2 py-1 px-2 rounded hover:bg-orange-500 <?= $page=='semua'?'bg-orange-500':'' ?>"><span class="material-icons">assignment</span><span class="menu-text">Semua Tugas</span></a></li>
                <li><a href="?page=orang" class="flex items-center gap-2 py-1 px-2 rounded hover:bg-orange-500 <?= $page=='orang'?'bg-orange-500':'' ?>"><span class="material-icons">people</span><span class="menu-text">Pegawai</span></a></li>
                <li><a href="?page=selesai" class="flex items-center gap-2 py-1 px-2 rounded hover:bg-orange-500 <?= $page=='selesai'?'bg-orange-500':'' ?>"><span class="material-icons">check_circle</span><span class="menu-text">Tugas Selesai</span></a></li>
            </ul>
        </nav>
        <div class="mt-auto px-4 py-4">
            <a href="../logout.php" class="flex items-center gap-2 text-sm hover:underline"><span class="material-icons">logout</span><span class="menu-text">Logout</span></a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6 overflow-y-auto">
        <h2 class="text-2xl font-bold mb-4">📋 Dashboard Penanaman Modal</h2>

       <?php if ($page == 'semua'): ?>
    <h3 class="text-lg font-semibold mb-3">📌 Semua Tugas</h3>
    <div class="bg-white p-4 rounded shadow">
        <table class="w-full border border-gray-300 table-auto">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border px-3 py-2">Judul</th>
                    <th class="border px-3 py-2">Nama Pegawai</th>
                    <th class="border px-3 py-2">Kategori</th>
                    <th class="border px-3 py-2">Deadline</th>
                    <th class="border px-3 py-2">Status</th>
                    <th class="border px-3 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = mysqli_query($conn, "
                    SELECT t.id, t.judul, t.kategori, t.deadline, t.status, u.nama AS nama_pegawai
                    FROM task t
                    JOIN user u ON t.assigned_to = u.id
                    JOIN bidang b ON u.bidang_id = b.id
                    WHERE b.nama = 'Penanaman Modal'
                    ORDER BY t.deadline ASC
                ");
                if (mysqli_num_rows($query) > 0):
                    while ($row = mysqli_fetch_assoc($query)):
                ?>
                <tr class="text-center">
                    <td class="border px-3 py-2"><?= htmlspecialchars($row['judul']) ?></td>
                    <td class="border px-3 py-2"><?= htmlspecialchars($row['nama_pegawai']) ?></td>
                    <td class="border px-3 py-2"><?= htmlspecialchars($row['kategori']) ?></td>
                    <td class="border px-3 py-2"><?= htmlspecialchars($row['deadline']) ?></td>
                    <td class="border px-3 py-2"><?= htmlspecialchars($row['status']) ?></td>
                    <td class="border px-3 py-2">
                       <a href="edit_tugas.php?id=<?= $row['id'] ?>&redirect=<?= basename($_SERVER['PHP_SELF']) ?>" 
                            class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600">
                            Edit
                            </a>

                        <a href="hapus_tugas.php?id=<?= urlencode($row['id']) ?>" 
                           onclick="return confirm('Yakin mau hapus data ini?')" 
                           class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="6" class="text-center py-4">Tidak ada tugas</td></tr>
                <?php endif; ?> <!-- ✅ Tambahan untuk menutup if tugas -->
            </tbody>
        </table>
    </div>


        <?php elseif ($page == 'orang'): ?>
            <h3 class="text-lg font-semibold mb-3">👤 Pegawai di Bidang Penanaman Modal</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php
                $query = mysqli_query($conn, "
                    SELECT u.id, u.nama 
                    FROM user u
                    JOIN bidang b ON u.bidang_id = b.id
                    WHERE b.nama = 'Penanaman Modal'
                ");
                if (mysqli_num_rows($query) > 0):
                    while ($row = mysqli_fetch_assoc($query)):
                ?>
                <div class="bg-white rounded shadow p-4">
                    <strong><?= htmlspecialchars($row['nama']) ?></strong><br>
                    ID: <?= htmlspecialchars($row['id']) ?>
                </div>
                <?php endwhile; else: ?>
                <div class="bg-white rounded shadow p-4 text-center col-span-2">Tidak ada data pegawai</div>
                <?php endif; ?>
            </div>

        <?php elseif ($page == 'selesai'): ?>
            <h3 class="text-lg font-semibold mb-3">✅ Tugas Selesai</h3>
            <div class="bg-white p-4 rounded shadow">
                <?php
                 $query = mysqli_query($conn, "
    SELECT t.id, t.judul, t.deadline, u.nama AS nama_pegawai
    FROM task t
    JOIN user u ON t.assigned_to = u.id
    JOIN bidang b ON u.bidang_id = b.id
    WHERE b.nama = 'Penanaman Modal' 
    AND LOWER(t.status) = 'selesai'
");

                if (mysqli_num_rows($query) > 0):
                    while ($row = mysqli_fetch_assoc($query)):
                ?>
                <div class="p-3 border-b">
                    <strong><?= htmlspecialchars($row['judul']) ?></strong><br>
                    Pegawai: <?= htmlspecialchars($row['nama_pegawai']) ?><br>
                    Deadline: <?= htmlspecialchars($row['deadline']) ?><br>
                    Status: <span class="text-green-600 font-bold">Selesai</span>
                    <!-- Tombol Lihat Tugas -->
                    <a href="lihat_tugas.php?id=<?= $row['id'] ?>" 
                       class="inline-block mt-2 px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                       📄 Lihat Tugas
                    </a>
                </div>
        <?php endwhile; else: ?>
        <div class="text-center py-4">Tidak ada tugas selesai</div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<script>
const sidebar = document.getElementById('sidebar');
const toggleBtn = document.getElementById('toggle-btn');
toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('sidebar-collapsed');
});
</script>
</body>
</html>
