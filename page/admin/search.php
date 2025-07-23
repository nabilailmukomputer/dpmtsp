<?php
include '../../db.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$data = [];

if (!empty($q)) {
    // Query dengan pencarian di multiple fields
    $stmt = $conn->prepare("SELECT * FROM task 
                           WHERE judul LIKE ? 
                           OR deskripsi LIKE ? 
                           OR created_by LIKE ?
                           OR assigned_to LIKE ?
                           OR kategori LIKE ?
                           OR status LIKE ?
                           OR deadline LIKE ?");
    $like = "%$q%";
    $stmt->bind_param("sssssss", $like, $like, $like, $like, $like, $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        if (isset($row['deadline'])) {
            $deadline = new DateTime($row['deadline']);
            $today = new DateTime();
            $interval = $today->diff($deadline);
            $row['days_remaining'] = $interval->format('%r%a');
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
  <div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Hasil Pencarian: <span class="text-blue-400"><?= htmlspecialchars($q) ?></span></h1>

    <?php if (count($data) > 0): ?>
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
          <?php foreach ($data as $task): ?>
            <tr class="hover:bg-gray-100">
                <td class="py-2 px-4 border"><?= htmlspecialchars($task['nama'] ?? '') ?></td>
                <td class="py-2 px-4 border"><?= htmlspecialchars($task['judul'] ?? '') ?></td>
                <td class="py-2 px-4 border"><?= htmlspecialchars($task['deskripsi'] ?? '') ?></td>
                <td class="py-2 px-4 border"><?= htmlspecialchars($task['kategori'] ?? '') ?></td>
                <td class="py-2 px-4 border"><?= htmlspecialchars($task['tanggal_tugas'] ?? '') ?></td>
                <td class="py-2 px-4 border"><?= htmlspecialchars($task['deadline'] ?? '') ?></td>
                <td class="py-2 px-4 border"><?= htmlspecialchars($task['created_by'] ?? '') ?></td>
                <td class="py-2 px-4 border"><?= htmlspecialchars($task['assigned_to'] ?? '') ?></td>
                <td class="py-2 px-4 border"><?= htmlspecialchars($task['status'] ?? '') ?></td>
                <td class="py-2 px-4 border"><?= $task['days_remaining'] ?? '' ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p class="text-gray-400">Tidak ditemukan tugas dengan kata kunci tersebut.</p>
    <?php endif ?>
  </div>
</body>
</html>