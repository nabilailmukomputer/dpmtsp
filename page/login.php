<?php
include '../db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - SIMANTAP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f5f5f5] min-h-screen flex items-center justify-center">
    <div class="bg-white shadow-lg rounded-lg flex max-w-4xl w-full overflow-hidden">
        <div class="w-1/2 bg-white flex items-center justify-center p-10">
            <img src="../assets/4a903338c0e478248153bd8f3f6f6745.jpg" alt="Ilustrasi Login" class="max-w-full h-auto">
        </div>
        <div class="w-1/2 p-10 flex flex-col justify-center">
            <h2 class="text-2xl font-bold mb-8 text-black">
                LOGIN
            </h2>
            <form action="login_process.php" method="POST">
              <label for="nama" class="block text-sm text-gray-700 mb-1">Username:</label>
                <input type="text" id="nama" name="nama" required
                    class="w-full px-4 py-2 rounded-md bg-[#dddddd] focus:outline-none focus:ring-2 focus:ring-blue-400">
                <div class="space-y-4">
                <div>
                    <label for="password" class="block text-sm text-gray-700 mb-1">Password:</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-2 rounded-md bg-[#dddddd] focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <button type="submit"
                        class="w-full bg-[#003366] text-white py-2 rounded-md font-semibold hover:bg-[#002244] transition duration-300">
                    Login
                </button>
                </div>
            </form>
            <p class="mt-4 text-sm text-center text-black">
                Kembali Ke Beranda? <a href="../index.php" class="text-blue-600 hover:underline">Klik Disini</a>
            </p>
        </div>
    </div>
</body>
</html>
