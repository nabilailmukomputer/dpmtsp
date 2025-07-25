<?php
include '../db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - SIMANTAP</title>
    <script src="https://cdn.tailwindcss.com"></script>
<style>
    body {
        margin: 0;
        padding: 0;
        height: 100vh;
        background: linear-gradient(135deg, #e3f2fd, #90caf9, #42a5f5);
        overflow: hidden;
        font-family: 'Poppins', sans-serif;
        transition: background 0.3s ease;
    }
    .dark-mode {
        background: linear-gradient(135deg, #0f172a, #1e293b, #334155);
    }
    canvas {
        position: fixed;
        top: 0;
        left: 0;
        z-index: -1;
    }
    /* Animasi Fade-in */
    .fade-in {
        opacity: 0;
        transform: translateY(30px);
        animation: fadeIn 1s ease-out forwards;
    }
    @keyframes fadeIn {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
</head>
<body class="bg-[#77BEF0] min-h-screen flex items-center justify-center relative">

<canvas id="particleCanvas"></canvas>

<!-- DARK MODE TOGGLE -->
<div class="absolute top-4 right-4 z-20">
    <button id="darkModeToggle" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition">
        🌙 Dark Mode
    </button>
</div>

<!-- LOGIN CARD -->
<div class="bg-white shadow-lg rounded-lg flex max-w-4xl w-full overflow-hidden z-10 fade-in" id="loginCard">
    <div class="w-1/2 hidden md:flex items-center justify-center p-10 bg-gray-100">
        <img src="../assets/4a903338c0e478248153bd8f3f6f6745.jpg" alt="Ilustrasi Login" class="max-w-full h-auto rounded-lg">
    </div>
    <div class="w-full md:w-1/2 p-10 flex flex-col justify-center bg-white dark:bg-gray-800">
        <h2 class="text-2xl font-bold mb-8 text-gray-800 dark:text-white">LOGIN</h2>
        <form action="login_process.php" method="POST">
            <label for="nama" class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Username:</label>
            <input type="text" id="nama" name="nama" required
                class="w-full px-4 py-2 rounded-md bg-[#f0f0f0] dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-400">
            <div class="space-y-4 mt-4">
                <div class="relative">
                    <label for="password" class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Password:</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-2 rounded-md bg-[#f0f0f0] dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <!-- Toggle Icon -->
                    <span class="absolute top-10 right-3 cursor-pointer text-gray-500 dark:text-gray-300" id="togglePassword">👁</span>
                </div>
                <button type="submit"
                    class="w-full bg-[#003366] text-white py-2 rounded-md font-semibold hover:bg-[#002244] transition duration-300 transform hover:scale-105">
                    Login
                </button>
            </div>
        </form>
        <p class="mt-4 text-sm text-center text-gray-600 dark:text-gray-300">
            Kembali Ke Beranda? <a href="../index.php" class="text-blue-600 hover:underline">Klik Disini</a>
        </p>
    </div>
</div>

<script>
/* ===== Partikel Background ===== */
const canvas = document.getElementById('particleCanvas');
const ctx = canvas.getContext('2d');
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

let particles = [];
const numParticles = 80;
const maxDistance = 120;
const mouse = { x: null, y: null };

document.addEventListener('mousemove', (e) => {
    mouse.x = e.clientX;
    mouse.y = e.clientY;
});

class Particle {
    constructor() {
        this.x = Math.random() * canvas.width;
        this.y = Math.random() * canvas.height;
        this.size = 3;
        this.speedX = (Math.random() - 0.5) * 1.5;
        this.speedY = (Math.random() - 0.5) * 1.5;
    }
    draw() {
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
        ctx.fillStyle = '#fdfdfd';
        ctx.fill();
    }
    update() {
        this.x += this.speedX;
        this.y += this.speedY;
        if (this.x < 0 || this.x > canvas.width) this.speedX *= -1;
        if (this.y < 0 || this.y > canvas.height) this.speedY *= -1;
    }
}

function init() {
    particles = [];
    for (let i = 0; i < numParticles; i++) {
        particles.push(new Particle());
    }
}

function connect() {
    for (let a = 0; a < particles.length; a++) {
        for (let b = a; b < particles.length; b++) {
            let dx = particles[a].x - particles[b].x;
            let dy = particles[a].y - particles[b].y;
            let distance = Math.sqrt(dx * dx + dy * dy);
            if (distance < maxDistance) {
                ctx.strokeStyle = 'rgba(255, 255, 255, 0.7)';
                ctx.lineWidth = 0.3;
                ctx.beginPath();
                ctx.moveTo(particles[a].x, particles[a].y);
                ctx.lineTo(particles[b].x, particles[b].y);
                ctx.stroke();
            }
        }
        if (mouse.x && mouse.y) {
            let dx = particles[a].x - mouse.x;
            let dy = particles[a].y - mouse.y;
            let distance = Math.sqrt(dx * dx + dy * dy);
            if (distance < 150) {
                ctx.strokeStyle = 'rgba(255, 255, 255, 0.8)';
                ctx.lineWidth = 0.4;
                ctx.beginPath();
                ctx.moveTo(particles[a].x, particles[a].y);
                ctx.lineTo(mouse.x, mouse.y);
                ctx.stroke();
            }
        }
    }
}

function animate() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    particles.forEach(p => {
        p.update();
        p.draw();
    });
    connect();
    requestAnimationFrame(animate);
}

init();
animate();
window.addEventListener('resize', () => {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
    init();
});

/* ===== Toggle Show/Hide Password ===== */
const togglePassword = document.getElementById('togglePassword');
const passwordField = document.getElementById('password');

togglePassword.addEventListener('click', () => {
    const type = passwordField.type === 'password' ? 'text' : 'password';
    passwordField.type = type;
    togglePassword.textContent = type === 'password' ? '👁' : '🙈';
});

/* ===== Dark Mode Toggle ===== */
const darkModeToggle = document.getElementById('darkModeToggle');
const body = document.body;

darkModeToggle.addEventListener('click', () => {
    body.classList.toggle('dark-mode');
});
</script>

</body>
</html>
