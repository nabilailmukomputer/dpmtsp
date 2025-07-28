<?php
include '../../db.php';

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

// Inisialisasi nilai kosong
$nama = '';
$password = '';
$role = '';
$bidang_id = '';
$NIP = '';

// Jika form disubmit
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $password = $_POST['password']; // <-- TANPA HASH
    $role = $_POST['role'];
    $bidang_id = $_POST['bidang'];
    $NIP = $_POST['NIP'];

    // Simpan langsung ke database (tanpa hash)
    $stmt = $conn->prepare("INSERT INTO user (nama, password, role, bidang_id, NIP) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssii", $nama, $password, $role, $bidang_id, $NIP);

    if ($stmt->execute()) {
        header("Location: kelola_admin.php");
        exit;
    } else {
        echo "Gagal menyimpan data: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <nav class="bg-[#5C7CFA] py-4 px-8 flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <h1 class="text-2xl md:text-3xl font-bold text-white">Tambah Admin</h1>
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
            <input type="int" name="NIP" value="<?= htmlspecialchars($NIP) ?>" required class="w-full mb-4 p-2 border rounded">

            <button type="submit" name="tambah" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Simpan</button>
            <a href="kelola_admin.php" class="ml-4 text-gray-600 hover:underline">Kembali</a>
        </form>
    </div>
</body>
</html>
