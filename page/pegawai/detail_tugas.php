<?php
include '../../db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

if (!isset($_GET['id'])) {
    die("ID tugas tidak ditemukan.");
}

$id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM task WHERE id = $id";
$result = mysqli_query($conn, $query);
$tugas = mysqli_fetch_assoc($result);

if (!$tugas) die("Tugas tidak ditemukan.");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kirim_tugas'])) {
    if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
        $isi_file = file_get_contents($_FILES['file']['tmp_name']); // ambil isi file

        // Simpan ke task_update
        $stmt = $conn->prepare("INSERT INTO task_update (task_id, user_id, file_lampiran, tanggal_update) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iis", $id, $user_id, $isi_file);
        $stmt->execute();

        // Update status di task
        mysqli_query($conn, "UPDATE task SET status='Selesai' WHERE id=$id");

        echo "<script>alert('✅ Tugas berhasil dikirim!'); window.location.href='detail_tugas.php?id=$id';</script>";
        exit;
    }
}



// ✅ Handle ajukan perpanjangan tenggat
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajukan_tenggat'])) {
    $requested_deadline = $_POST['requested_deadline'];

    $stmt = $conn->prepare("INSERT INTO deadline_request (task_id, user_id, requested_deadline, status, requested_by) 
                            VALUES (?, ?, ?, 'Pending', NOW())");
    $stmt->bind_param("iis", $id, $user_id, $requested_deadline);
    $stmt->execute();

    echo "<script>alert('⏰ Permintaan perpanjangan tenggat diajukan!'); window.location.href='detail_tugas.php?id=$id';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Detail Tugas</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
  <div class="max-w-3xl mx-auto mt-10">
    <!-- Tombol Kembali -->
    <a href="ditugaskan.php" class="flex items-center text-blue-600 hover:underline mb-4">
        ← Kembali
    </a>

    <!-- Card Detail Tugas -->
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <!-- Judul -->
        <h1 class="text-2xl font-bold text-gray-800 mb-6">📄 <?= htmlspecialchars($tugas['judul']) ?></h1>

        <!-- Info Tugas -->
        <div class="space-y-3 text-gray-700">
            <p><i class="fa-solid fa-user mr-2 text-purple-600"></i> Ditugaskan kepada: <?= htmlspecialchars($tugas['assigned_to']) ?></p>
            <p><i class="fa-solid fa-calendar-plus mr-2 text-pink-600"></i> Diberikan: <?= $tugas['tanggal_tugas'] ?></p>
            <p><i class="fa-solid fa-hourglass-end mr-2 text-red-600"></i> Deadline: <?= $tugas['deadline'] ?></p>
            <p><i class="fa-solid fa-circle-check mr-2 text-green-600"></i> Status: <b><?= $tugas['status'] ?></b></p>
        </div>

        <!-- Kirim Tugas -->
        <div class="mt-8 border-t pt-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-3">📤 Kirim Tugas</h2>
            <form method="post" enctype="multipart/form-data" class="flex items-center space-x-3">
                <input type="file" name="file" required class="block w-full text-sm text-gray-600 border rounded-lg cursor-pointer focus:outline-none">
                <button type="submit" name="kirim_tugas" class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">
                    Kirim
                </button>
            </form>
        </div>

        <!-- Perpanjangan Tenggat -->
        <div class="mt-8 border-t pt-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-3">⏰ Ajukan Perpanjangan Tenggat</h2>
            <form method="post" class="flex items-center space-x-3">
                <input type="datetime-local" name="requested_deadline" required class="border rounded-lg p-2 w-1/2 focus:ring focus:ring-blue-300">
                <button type="submit" name="ajukan_tenggat" class="px-4 py-2 bg-yellow-500 text-white rounded-lg shadow hover:bg-yellow-600">
                    Ajukan
                </button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
