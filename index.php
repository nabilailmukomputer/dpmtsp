<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SIMANTAP - Landing</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .font-times {
            font-family: 'Times New Roman', serif;
        }
    </style>
</head>

<body class="bg-gradient-to-b from-[#5C6BC0] to-[#E0E9F5] min-h-screen text-white font-sans">

<!-- ✅ Navbar Transparan -->
<nav class="fixed top-0 left-0 w-full flex justify-between items-center px-8 py-4 bg-transparent backdrop-blur-md z-50">
    <div class="flex items-center space-x-2">
        <img src="assets/s.png" alt="Logo" class="w-10 h-10 mt-2 "/>
        <h1 class="text-2xl md:text-3xl font-bold text-white ">SIMANTAP</h1>
    </div>
    <!-- <ul class="flex space-x-6 text-white">
        <li><a href="index.php" class="hover:text-blue-400">Beranda</a></li>
        <li><a href="tentang.php" class="hover:text-blue-400">Tentang Kami</a></li>
        <li><a href="contact.php" class="bg-[#052748] hover:bg-[#011527] px-6 py-2 rounded-tl-[50px] rounded-tr-[50px]
        rounded-br-[50px] rounded-bl-[0px]  text-white shadow-md transition">Contact</a></li>
    </ul> -->
</nav>

<!-- ✅ Section dengan Video Background -->
<section class="relative h-screen w-full overflow-hidden">
    <!-- Video Background -->
    <video autoplay muted loop playsinline class="absolute top-0 left-0 w-full h-full object-cover z-0 brightness-100">
        <source src="assets/f.mp4" type="video/mp4">
        Browser Anda tidak mendukung video tag.
    </video>

    <!-- ✅ Overlay agar teks lebih jelas -->
    <div class="absolute top-0 left-0 w-full h-full bg-black bg-opacity-40 z-10"></div>

    <!-- ✅ Hero Text di Atas Video -->
    <div class="relative z-20 flex flex-col md:flex-row items-center justify-center h-full text-center px-10">
        <div class="max-w-xl space-y-6">
            <h2 class="text-5xl md:text-6xl text-white font-bold leading-snug font-times">
                Sistem Informasi Monitoring<br>Tugas Pegawai
            </h2>
            <p class="text-2xl text-gray-200">Aplikasi Monitoring Tugas</p>
            <a href="page/login.php"
               class="inline-block bg-[#052748] hover:bg-[#011527] px-10 py-3 rounded-[30px] text-white font-bold">
                LOGIN
            </a>
        </div>
    </div>
</section>

</body>
</html>
