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
    body, html {
        margin: 0;
        padding: 0;
        overflow: hidden;
    }
    canvas {
        position: fixed;
        top: 0;
        left: 0;
        z-index: -1;
    }
</style>
</head>
<body class="bg-[#77BEF0] min-h-screen flex items-center justify-center relative">

<canvas id="particleCanvas"></canvas>

<!-- LOGIN CARD -->
<div class="bg-white shadow-lg rounded-lg flex max-w-4xl w-full overflow-hidden z-10">
    <div class="w-1/2 flex items-center justify-center p-10">
        <img src="../assets/4a903338c0e478248153bd8f3f6f6745.jpg" alt="Ilustrasi Login" class="max-w-full h-auto">
    </div>
    <div class="w-1/2 p-10 flex flex-col justify-center">
        <h2 class="text-2xl font-bold mb-8 text-black">LOGIN</h2>
        <form action="login_process.php" method="POST">
            <label for="nama" class="block text-sm text-gray-700 mb-1">Username:</label>
            <input type="text" id="nama" name="nama" required
                class="w-full px-4 py-2 rounded-md bg-[#dddddd] focus:outline-none focus:ring-2 focus:ring-blue-400">
            <div class="space-y-4 mt-4">
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

<script>
const canvas = document.getElementById('particleCanvas');
const ctx = canvas.getContext('2d');
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

let particles = [];
const numParticles = 80;
const maxDistance = 120;
const mouse = { x: null, y: null };

// Update mouse position
document.addEventListener('mousemove', (e) => {
    mouse.x = e.clientX;
    mouse.y = e.clientY;
});

// Particle class
class Particle {
    constructor() {
        this.x = Math.random() * canvas.width;
        this.y = Math.random() * canvas.height;
        this.size = 3;
        this.baseX = this.x;
        this.baseY = this.y;
        this.speedX = (Math.random() - 0.5) * 1.5;
        this.speedY = (Math.random() - 0.5) * 1.5;
    }
    draw() {
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
        ctx.fillStyle = '#fdfdfdff'; // Warna biru elegan
        ctx.fill();
    }
    update() {
        this.x += this.speedX;
        this.y += this.speedY;

        // Bounce
        if (this.x < 0 || this.x > canvas.width) this.speedX *= -1;
        if (this.y < 0 || this.y > canvas.height) this.speedY *= -1;
    }
}

// Inisialisasi partikel
function init() {
    particles = [];
    for (let i = 0; i < numParticles; i++) {
        particles.push(new Particle());
    }
}

// Gambar garis antar partikel
function connect() {
    for (let a = 0; a < particles.length; a++) {
        for (let b = a; b < particles.length; b++) {
            let dx = particles[a].x - particles[b].x;
            let dy = particles[a].y - particles[b].y;
            let distance = Math.sqrt(dx * dx + dy * dy);
            if (distance < maxDistance) {
                ctx.strokeStyle = 'rgba(255, 255, 255, 1)'; // Biru transparan
                ctx.lineWidth = 1;
                ctx.beginPath();
                ctx.moveTo(particles[a].x, particles[a].y);
                ctx.lineTo(particles[b].x, particles[b].y);
                ctx.stroke();
            }
        }

        // Garis ke mouse
        if (mouse.x && mouse.y) {
            let dx = particles[a].x - mouse.x;
            let dy = particles[a].y - mouse.y;
            let distance = Math.sqrt(dx * dx + dy * dy);
            if (distance < 150) {
                ctx.strokeStyle = 'rgba(255, 255, 255, 1)';
                ctx.lineWidth = 1.2;
                ctx.beginPath();
                ctx.moveTo(particles[a].x, particles[a].y);
                ctx.lineTo(mouse.x, mouse.y);
                ctx.stroke();
            }
        }
    }
}

// Animasi
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
</script>

</body>
</html>