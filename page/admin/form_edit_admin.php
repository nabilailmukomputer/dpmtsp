<?php
include '../../db.php';
session_start();
if (!isset($_SESSION['user_id']) ) {
    // Jika belum, redirect ke halaman login
    header('Location: ../login.php');
    exit;
}
// Daftar role manual
$roles = [
    'admin' => 'Admin',
    'penanggung jawab' => 'Penanggung Jawab',
    'pengendali teknis' => 'Pengendali Teknis',
    'ketua divisi' => 'Ketua Divisi',
    'anggota' => 'Anggota'
];

// Ambil data bidang dari tabel bidang
$queryBidang = "SELECT * FROM bidang";
$resultBidang = mysqli_query($conn, $queryBidang);
$daftarBidang = [];
while ($row = mysqli_fetch_assoc($resultBidang)) {
    $daftarBidang[] = $row;
}

// Ambil ID dari URL
$id = $_GET['id'] ?? null;

// Inisialisasi nilai default
$nama = '';
$password = '';
$role = '';
$bidang_id = '';
$NIP = '';

// Jika ID ada, ambil data user untuk prefill form
if ($id) {
    $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();

    if ($userData) {
        $nama = $userData['nama'];
        $password = $userData['password']; // password lama ditampilkan
        $role = $userData['role'];
        $bidang_id = $userData['bidang_id'];
        $NIP = $userData['NIP'];
    } else {
        echo "Data tidak ditemukan.";
        exit;
    }
}

// Jika form disubmit untuk update
if (isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $password = $_POST['password']; // tidak di-hash sesuai permintaan
    $role = $_POST['role'];
    $bidang_id = $_POST['bidang'];
    $NIP = $_POST['NIP'];

    // Update data
    $stmt = $conn->prepare("UPDATE user SET nama=?, password=?, role=?, bidang_id=?, NIP=? WHERE id=?");
    $stmt->bind_param("sssisi", $nama, $password, $role, $bidang_id, $NIP, $id);

    if ($stmt->execute()) {
        header("Location: kelola_admin.php");
        exit;
    } else {
        echo "Gagal memperbarui data: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <nav class="bg-[#5C7CFA] py-4 px-8 flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <h1 class="text-2xl md:text-3xl font-bold text-white">Edit Admin</h1>
        </div>
        <div class="space-x-6">
            <a href="dashboard.php" class="text-white hover:underline">Dashboard</a>
            <a href="kelola_admin.php" class="text-white hover:underline">Kelola Pengguna</a>
        </div>
    </nav>

    <div class="flex justify-center items-center flex-grow">
        <form method="POST" class="bg-white p-6 rounded shadow-md w-full max-w-md">
            <label class="block mb-2 font-semibold">Nama</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($nama) ?>" required class="w-full mb-4 p-2 border rounded">

            <label class="block mb-2 font-semibold">Password</label>
            <input type="text" name="password" value="<?= htmlspecialchars($password) ?>" required class="w-full mb-4 p-2 border rounded">

            <label class="block mb-2 font-semibold">Role</label>
            <select name="role" required class="w-full mb-4 p-2 border rounded">
                <option value="">-- Pilih Role --</option>
                <?php foreach ($roles as $key => $label): ?>
                    <option value="<?= $key ?>" <?= $role == $key ? 'selected' : '' ?>>
                        <?= $label ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label class="block mb-2 font-semibold">Bidang</label>
            <select name="bidang" required class="w-full mb-4 p-2 border rounded">
                <option value="">-- Pilih Bidang --</option>
                <?php foreach ($daftarBidang as $b): ?>
                    <option value="<?= $b['id']; ?>" <?= $bidang_id == $b['id'] ? 'selected' : '' ?>>
                        <?= $b['nama']; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label class="block mb-2 font-semibold">NIP</label>
            <input type="text" name="NIP" value="<?= htmlspecialchars($NIP) ?>" required class="w-full mb-4 p-2 border rounded">

            <button type="submit" name="update" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Update</button>
            <a href="kelola_admin.php" class="ml-4 text-gray-600 hover:underline">Kembali</a>
        </form>
    </div>
</body>
</html>
