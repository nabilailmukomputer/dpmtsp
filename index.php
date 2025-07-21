
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SIMANTAP - Landing</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .font-times {
      font-family: 'Times New Roman', serif;
    }
  </style>
</head>
<body class="bg-gradient-to-b from-[#5C6BC0] to-[#E0E9F5] min-h-screen text-white font-sans">

  <!-- Navbar -->
  <nav class="flex justify-between items-center px-10 py-4 bg-transparent">
    <div class="flex items-center space-x-2">
      <img src="assets/titik_tiga.png" alt="Logo" class="w-10 h-10"/>
      <h1 class="text-2xl md:text-3xl font-bold text-white">SIMANTAP</h1>
    </div>
    <ul class="flex space-x-8 md:space-x-16 font-medium text-base md:text-lg">
      <li><a href="index.php" class="hover:border-b-2 border-white">Beranda</a></li>
      <li><a href="tentang.php" class="hover:border-b-2 border-white">Tentang Kami</a></li>
      <li>
        <a href="contact.php" class="bg-[#006D77] hover:bg-[#005760] px-6 py-2 rounded-full text-white shadow-md transition">
          Contact
        </a>
      </li>
    </ul>
  </nav>

  <!-- Hero Section -->
  <div class="flex flex-col md:flex-row items-center justify-between px-10 py-20 space-y-10 md:space-y-0">
    <!-- Text Section -->
    <div class="max-w-xl space-y-6">
      <h2 class="text-5xl md:text-6xl text-gray-900 font-bold leading-snug font-times">
        Sistem Informasi<br>Monitoring<br>Tugas Pegawai
      </h2>
      <p class="text-2xl text-gray-700">Aplikasi Monitoring Tugas</p>
      <a href="page/login.php" class="inline-block bg-blue-800 hover:bg-blue-900 px-8 py-3 rounded text-white font-bold">
        LOGIN
      </a>
    </div>

    <!-- Image Section -->
    <div>
      <img src="assets/bing.png" alt="Ilustrasi SIMANTAP" class="w-[600px] md:w-[600px] rounded-lg shadow-lg"/>
    </div>
  </div>

</body>
</html>
