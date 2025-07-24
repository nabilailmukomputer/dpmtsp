<?php
include '../../db.php';
session_start();
if ($_SESSION['role'] !== 'pj') {
    header("Location: login.php");
    exit;
}

$bidang = $_GET['bidang']; 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal_tugas = $_POST['tanggal_tugas'];
    $deadline = $_POST['deadline'];
    $ditugaskan_kepada = $_POST['ditugaskan_kepada'];
    $pj_id = $_SESSION['id_user'];

    $query = "INSERT INTO task (judul, deskripsi, tanggal_tugas, deadline, diberikan_oleh, ditugaskan_kepada, bidang)
              VALUES ('$judul', '$deskripsi', '$tanggal_tugas', '$deadline', '$created_by', '$assigned_to', '$bidang_user')";
    mysqli_query($conn, $query);
    header("Location: {$bidang}_dashboard.php");
}
?>

<form method="POST" class="bg-white p-6 rounded shadow w-1/2 mx-auto mt-10">
    <h2 class="text-xl font-bold mb-4">Tambah Tugas Bidang <?= ucfirst($bidang) ?></h2>
    <input type="text" name="judul" placeholder="Judul Tugas" class="border w-full p-2 mb-3">
    <textarea name="deskripsi" placeholder="Deskripsi" class="border w-full p-2 mb-3"></textarea>
    <input type="date" name="tanggal_tugas" class="border w-full p-2 mb-3">
    <input type="date" name="deadline" class="border w-full p-2 mb-3">
    <select name="ditugaskan_kepada" class="border w-full p-2 mb-3">
        <?php
        $pegawai = mysqli_query($conn, "SELECT * FROM user WHERE role='Anggota' AND bidang='$bidang'");
        while ($row = mysqli_fetch_assoc($pegawai)) {
            echo "<option value='{$row['id']}'>{$row['username']}</option>";
        }
        ?>
    </select>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Tambah Tugas</button>
</form>
