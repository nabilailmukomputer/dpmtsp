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
<html>
<head>
    <title>Detail Tugas</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
    <h1 class="text-3xl font-bold mb-6">Detail Tugas Pegawai</h1>
     <form action="search.php" method="GET" class="relative">
          <input type="text" name="q" placeholder="Cari...." class="bg-gray-800 text-white px-4 py-2 rounded-lg focus:outline-none" />
          <button type="submit" class="absolute right-2 top-2 text-gray-400 hover:text-white">🔍</button>
        </form>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded shadow-md">
            <thead class="bg-gray-200 text-gray-600">
                <tr>
                    <th class="py-3 px-4 border">Nama</th>
                    <th class="py-3 px-4 border">Judul Tugas</th>
                    <th class="py-3 px-4 border">Deskripsi</th>
                    <th class="py-3 px-4 border">Kategori</th>
                    <th class="py-3 px-4 border">Tanggal Tugas</th>
                    <th class="py-3 px-4 border">Deadline</th>
                    <th class="py-3 px-4 border">Diberikan Oleh</th>
                    <th class="py-3 px-4 border">Ditugaskan Kepada</th>
                    <th class="py-3 px-4 border">Status</th>
                   <th class="py-3 px-4 border">Sisa Hari</th>

                </tr>
            </thead>
            <tbody>
                <?php
                $bulan_indo = [
                    'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
                    'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
                    'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
                    'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
                ];

                 
      while ($row = mysqli_fetch_assoc($result)) {
    $deadline_date = new DateTime($row['deadline']);
    $now = new DateTime();
    $interval = $now->diff($deadline_date);
    $selisih_hari = (int)$interval->format('%r%a');
    $selisih_detik = $deadline_date->getTimestamp() - $now->getTimestamp();

    $formatted_tanggal = $deadline_date->format('d F Y');
    $formatted_tanggal = strtr($formatted_tanggal, $bulan_indo);

    // Inisialisasi default
    $keterangan = "Waktu tidak valid";
    $statusColor = "bg-gray-500 text-white";

    if ($selisih_detik <= 0) {
    // Sudah lewat deadline
    $keterangan = "Lewat waktu! ($formatted_tanggal)";
    $statusColor = "bg-red-500 text-white";
} elseif ($selisih_hari >= 3) {
    // 3+ hari
    $keterangan = "$selisih_hari hari lagi ($formatted_tanggal)";
    $statusColor = "bg-green-500 text-white"; 
} elseif ($selisih_hari == 2) {
    // 2 hari
    $keterangan = "$selisih_hari hari lagi ($formatted_tanggal)";
    $statusColor = "bg-yellow-400 text-black";
} else {
        // Less than 1 day (display hours:minutes:seconds)
        $jam = floor(abs($selisih_detik) / 3600);
        $sisa_detik = abs($selisih_detik) % 3600;
        $menit = floor($sisa_detik / 60);
        $detik = $sisa_detik % 60;
        // Ensure minutes are double digits (02) but keep seconds as normal (1 or 2 digits)
        $keterangan = "Tersisa " . sprintf("%d:%d:%d", $jam, $menit, $detik); 
        $statusColor = "bg-red-500 text-white";
}
    // Tampilkan di HTML
    ?>

    <tr class="hover:bg-gray-100">
        <td class="py-2 px-4 border"><?= htmlspecialchars($row['nama']) ?></td>
        <td class="py-2 px-4 border"><?= htmlspecialchars($row['judul']) ?></td>
        <td class="py-2 px-4 border"><?= htmlspecialchars($row['deskripsi']) ?></td>
        <td class="py-2 px-4 border"><?= htmlspecialchars($row['kategori']) ?></td>
        <td class="py-2 px-4 border"><?= htmlspecialchars($row['tanggal_tugas']) ?></td>
        <td class="py-2 px-4 border"><?= htmlspecialchars($row['deadline']) ?></td>
        <td class="py-2 px-4 border"><?= htmlspecialchars($row['created_by']) ?></td>
        <td class="py-2 px-4 border"><?= htmlspecialchars($row['assigned_to']) ?></td>
        <td class="py-2 px-4 border"><?= htmlspecialchars($row['status']) ?></td>
        <td class="py-2 px-4 border">
            <span class="px-2 py-1 rounded-full text-sm font-semibold <?= $statusColor ?>">
                <?= $keterangan ?>
</span>

                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
