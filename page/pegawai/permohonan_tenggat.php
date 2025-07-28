<?php
include '../../db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task_id = $_POST['task_id'];
    $alasan = $_POST['alasan'];
    $deadline_baru = $_POST['requested_deadline'];
    $requested_by = $_SESSION['user_id']; // ID user yang login

    $query = "INSERT INTO deadline_request (task_id, requested_by, alasan, requested_deadline) 
              VALUES ('$task_id', '$requested_by', '$alasan', '$deadline_baru')";
    mysqli_query($conn, $query);

    header("Location: dashboard.php");
    exit;
}

// Ambil semua tugas yang dimiliki user ini
$user_id = $_SESSION['user_id'];
$tugas = mysqli_query($conn, "SELECT * FROM task WHERE assigned_to='$user_id'");
?>

<form method="POST" class="p-6 bg-white shadow-md rounded max-w-lg mx-auto mt-10">
<h2 class="text-xl font-bold mb-4">Ajukan Perpanjangan Deadline</h2>
<label class="block mb-2">Pilih Tugas:</label>
<select name="task_id" class="border p-2 w-full mb-4">
<?php while ($row = mysqli_fetch_assoc($tugas)): ?>
<option value="<?= $row['id'] ?>"><?= $row['judul'] ?></option>
<?php endwhile; ?>
</select>

<label class="block mb-2">Deadline Baru:</label>
<input type="datetime-local" name="deadline_baru" class="border p-2 w-full mb-4" required>

<label class="block mb-2">Alasan:</label>
<textarea name="alasan" class="border p-2 w-full mb-4" required></textarea>

<button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-800">Ajukan</button>
</form>
