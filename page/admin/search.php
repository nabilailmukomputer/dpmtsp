<?php
include '../../db.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$data = [];

if (!empty($q)) {
    $stmt = $conn->prepare("
        SELECT t.id, t.judul, t.deskripsi, t.kategori, t.tanggal_tugas, t.deadline, t.status,
               u.nama AS penerima,
               TIMESTAMPDIFF(SECOND, NOW(), t.deadline) AS remaining_seconds
        FROM task t
        JOIN user u ON t.assigned_to = u.id
        WHERE t.judul LIKE ? 
           OR t.deskripsi LIKE ? 
           OR t.kategori LIKE ? 
           OR t.status LIKE ?
           OR u.nama LIKE ?
        ORDER BY t.id DESC
    ");

    $like = "%$q%";
    $stmt->bind_param("sssss", $like, $like, $like, $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        // Hitung tampilan sisa waktu
        if ($row['remaining_seconds'] <= 0) {
            $row['time_remaining'] = '<span class="bg-gray-600 text-white px-3 py-1 rounded">Terlambat!</span>';
        } else {
            $row['time_remaining'] = '<span class="bg-red-500 text-white px-3 py-1 rounded">'
                                   . gmdate("H:i:s", $row['remaining_seconds'])
                                   . '</span>';
        }

        $data[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Hasil Pencarian</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background-color: #f5f5f7;
      color: black;
      font-family: sans-serif;
    }
  </style>
</head>
<body class="min-h-screen p-6">
  <div class="max-w-6xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">
        Hasil Pencarian: <span class="text-blue-500"><?= htmlspecialchars($q) ?></span>
    </h1>

    <?php if (count($data) > 0): ?>
      <table class="w-full bg-white rounded shadow border">
        <thead class="bg-gray-200 text-gray-800">
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
          <?php foreach ($data as $task): ?>
            <tr class="text-center hover:bg-gray-100">
              <td class="py-2 px-4 border"><?= htmlspecialchars($task['judul']) ?></td>
              <td class="py-2 px-4 border"><?= htmlspecialchars($task['deadline']) ?></td>
              <td class="py-2 px-4 border"><?= htmlspecialchars($task['tanggal_tugas']) ?></td>
              <td class="py-2 px-4 border"><?= htmlspecialchars($task['penerima']) ?></td>
              <td class="py-2 px-4 border"><?= htmlspecialchars($task['status']) ?></td>
              <td class="py-2 px-4 border"><?= $task['time_remaining'] ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p class="text-gray-400">Tidak ditemukan tugas dengan kata kunci tersebut.</p>
    <?php endif; ?>
  </div>
</body>
</html>
