<?php
include '../../db.php';
session_start();

// Pastikan user login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Pastikan ada ID tugas di URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID tugas tidak valid.");
}

$id = intval($_GET['id']);

// Ambil halaman asal untuk redirect
$redirectPage = isset($_GET['redirect']) ? $_GET['redirect'] : 'kesekretariatan.php';

// Ambil data tugas untuk prefill form
$stmt = $conn->prepare("
    SELECT t.id, t.judul, t.assigned_to, t.kategori, t.deadline, t.status, u.nama AS nama_pegawai
    FROM task t
    JOIN user u ON t.assigned_to = u.id
    WHERE t.id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Tugas tidak ditemukan.");
}

$tugas = $result->fetch_assoc();

// Ambil daftar pegawai
$pegawai = $conn->query("SELECT id, nama FROM user ORDER BY nama ASC");

// Proses update data
if (isset($_POST['update'])) {
    $judul       = trim($_POST['judul']);
    $assigned_to = intval($_POST['assigned_to']);
    $kategori    = trim($_POST['kategori']);
    $deadline    = trim($_POST['deadline']);
    $status      = trim($_POST['status']);

    $updateStmt = $conn->prepare("
        UPDATE task 
        SET judul = ?, assigned_to = ?, kategori = ?, deadline = ?, status = ?
        WHERE id = ?
    ");
    $updateStmt->bind_param("sisssi", $judul, $assigned_to, $kategori, $deadline, $status, $id);

    if ($updateStmt->execute()) {
        header("Location: " . $redirectPage);
        exit;
    } else {
        echo "Gagal mengupdate tugas: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Tugas</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-lg mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Edit Tugas</h2>
        <form method="POST">
            <label class="block mb-2">Judul:</label>
            <input type="text" name="judul" value="<?= htmlspecialchars($tugas['judul']) ?>" class="border w-full p-2 mb-4" required>

            <label class="block mb-2">Pegawai:</label>
            <select name="assigned_to" class="border w-full p-2 mb-4" required>
                <?php while ($row = $pegawai->fetch_assoc()): ?>
                    <option value="<?= $row['id'] ?>" <?= ($row['id'] == $tugas['assigned_to']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($row['nama']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label class="block mb-2">Kategori:</label>
            <input type="text" name="kategori" value="<?= htmlspecialchars($tugas['kategori']) ?>" class="border w-full p-2 mb-4" required>

            <label class="block mb-2">Deadline:</label>
            <input type="datetime-local" name="deadline" value="<?= date('Y-m-d\TH:i', strtotime($tugas['deadline'])) ?>" class="border w-full p-2 mb-4" required>

            <label class="block mb-2">Status:</label>
            <select name="status" class="border w-full p-2 mb-4" required>
                <option value="dikerjakan" <?= ($tugas['status'] == 'dikerjakan') ? 'selected' : '' ?>>Dikerjakan</option>
                <option value="selesai" <?= ($tugas['status'] == 'selesai') ? 'selected' : '' ?>>Selesai</option>
            </select>

            <button type="submit" name="update" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update</button>
            <a href="<?= htmlspecialchars($redirectPage) ?>" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</a>
        </form>
    </div>
</body>
</html>
