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
        background: linear-gradient(135deg, #1f2937, #111827);
        overflow: hidden;
        font-family: 'Poppins', sans-serif;
        transition: background 0.3s ease;
    }
    .dark-mode {
        background: linear-gradient(135deg, #0f172a, #1e293b);
    }
    canvas {
        position: fixed;
        top: 0;
        left: 0;
        z-index: -1;
    }
    /* Glass Card dengan glow soft */
    .glass-card {
        background: rgba(255, 255, 255, 0.06);
        backdrop-filter: blur(10px);
        border-radius: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .glass-card::before {
        content: '';
        position: absolute;
        inset: -3px;
        background: rgba(255, 255, 255, 0.3);
        filter: blur(10px);
        animation: pulseGlow 4s infinite ease-in-out;
        z-index: -1;
    }
    @keyframes pulseGlow {
        0%, 100% { opacity: 0.15; }
        50% { opacity: 0.4; }
    }
    .glass-card:hover {
        transform: scale(1.02);
        box-shadow: 0 0 15px rgba(255, 255, 255, 0.2);
    }
    .gradient-text {
        background: linear-gradient(90deg, #ffffff, #e5e7eb);
        background-clip: text;
        -webkit-background-clip: text;
        color: transparent;
        font-weight: 800;
        letter-spacing: 2px;
    }
</style>
</head>
<body class="min-h-screen flex items-center justify-center relative text-white">

<canvas id="particleCanvas"></canvas>

<!-- Dark Mode Button -->
<div class="absolute top-4 right-4 z-20">
    <button id="darkModeToggle" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition">
        🌙 Dark Mode
    </button>
</div>

<!-- LOGIN CARD -->
<div class="glass-card p-6 flex flex-col md:flex-row max-w-lg w-full z-10">
    <!-- Bagian Gambar -->
    <div class="hidden md:flex w-1/2 items-center justify-center p-3">
        <img src="../assets/4a903338c0e478248153bd8f3f6f6745.jpg" alt="Ilustrasi Login" class="max-w-full h-auto rounded-lg">
    </div>
    <!-- Bagian Form -->
    <div class="w-full md:w-1/2 flex flex-col justify-center">
        <h2 class="text-2xl mb-6 text-center gradient-text">LOGIN</h2>
        <form action="login_process.php" method="POST" class="space-y-4">
            <div>
                <label for="nama" class="block text-sm mb-1">Username:</label>
                <input type="text" id="nama" name="nama" required
                    class="w-full px-4 py-2 rounded-md bg-white/20 text-white placeholder-gray-200 focus:outline-none focus:ring-2 focus:ring-white">
            </div>
            <div class="relative">
                <label for="password" class="block text-sm mb-1">Password:</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-2 rounded-md bg-white/20 text-white placeholder-gray-200 focus:outline-none focus:ring-2 focus:ring-white">
                <span class="absolute top-9 right-3 cursor-pointer text-gray-200" id="togglePassword">👁</span>
            </div>
            <button type="submit"
                class="w-full flex items-center justify-center gap-2 bg-white text-black font-semibold py-2 rounded-md shadow-lg transform transition duration-300 hover:scale-105 hover:bg-gray-200">
                🔐 Login
            </button>
        </form>
        <p class="mt-4 text-sm text-center">
            Kembali ke beranda? <a href="../index.php" class="text-blue-300 hover:underline">Klik di sini</a>
        </p>
    </div>
</div>

<script>
/* === Particle Follow Mouse === */
const canvas = document.getElementById('particleCanvas');
const ctx = canvas.getContext('2d');
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

let particles = [];
const numParticles = 80;
const mouse = { x: null, y: null };

document.addEventListener('mousemove', (e) => {
    mouse.x = e.clientX;
    mouse.y = e.clientY;
});

class Particle {
    constructor() {
        this.x = Math.random() * canvas.width;
        this.y = Math.random() * canvas.height;
        this.size = 2;
        this.baseX = this.x;
        this.baseY = this.y;
        this.density = Math.random() * 30 + 1;
    }
    draw() {
        ctx.fillStyle = '#ffffff';
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
        ctx.closePath();
        ctx.fill();
    }
    update() {
        let dx = mouse.x - this.x;
        let dy = mouse.y - this.y;
        let distance = Math.sqrt(dx * dx + dy * dy);
        let maxDistance = 150;
        let force = (maxDistance - distance) / maxDistance;
        let directionX = dx / distance;
        let directionY = dy / distance;

        if (distance < maxDistance) {
            this.x -= directionX * force * this.density * 0.2;
            this.y -= directionY * force * this.density * 0.2;
        } else {
            if (this.x !== this.baseX) {
                let dx = this.x - this.baseX;
                this.x -= dx / 10;
            }
            if (this.y !== this.baseY) {
                let dy = this.y - this.baseY;
                this.y -= dy / 10;
            }
        }
    }
}

function init() {
    particles = [];
    for (let i = 0; i < numParticles; i++) {
        particles.push(new Particle());
    }
}
function animate() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    particles.forEach(p => {
        p.draw();
        p.update();
    });
    requestAnimationFrame(animate);
}
init();
animate();
window.addEventListener('resize', () => {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
    init();
});

/* Toggle Password */
const togglePassword = document.getElementById('togglePassword');
const passwordField = document.getElementById('password');
togglePassword.addEventListener('click', () => {
    const type = passwordField.type === 'password' ? 'text' : 'password';
    passwordField.type = type;
    togglePassword.textContent = type === 'password' ? '👁' : '🙈';
});

/* Dark Mode Toggle */
const darkModeToggle = document.getElementById('darkModeToggle');
const body = document.body;
darkModeToggle.addEventListener('click', () => {
    body.classList.toggle('dark-mode');
});
</script>

</body>
</html>
