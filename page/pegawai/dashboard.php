<?php
include '../../db.php';
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['bidang_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_nama = $_SESSION['nama'];
$bidang_id = $_SESSION['bidang_id'];

// Ambil info bidang
$q_bidang = mysqli_query($conn, "SELECT * FROM bidang WHERE id = '$bidang_id'");
$bidang = mysqli_fetch_assoc($q_bidang);
$nama_bidang = $bidang['nama'];

// Proses kirim pengumuman
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['teks'])) {
    $id_user = $_SESSION['user_id'];
    $teks = mysqli_real_escape_string($conn, $_POST['teks']);

    $query = "INSERT INTO pengumuman (user_id, teks, bidang_id, created_at) 
              VALUES ('$id_user', '$teks', '$bidang_id', NOW())";

    mysqli_query($conn, $query);
    header("Location: dashboard.php");
    exit;
}

// Proses kirim komentar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['komentar'], $_POST['id_pengumuman'])) {
    $id_user = $_SESSION['user_id'];
    $id_pengumuman = (int) $_POST['id_pengumuman'];
    $konten = mysqli_real_escape_string($conn, $_POST['komentar']);

    $query = "INSERT INTO komentar_pengumuman (id_pengumuman, user_id, komentar, created_at) 
              VALUES ('$id_pengumuman', '$id_user', '$konten', NOW())";

    mysqli_query($conn, $query);
    header("Location: dashboard.php");
    exit;
}

// Ambil semua pengumuman + user pembuat
$q_pengumuman = mysqli_query($conn, "
    SELECT p.*, u.nama AS nama_user
    FROM pengumuman p
    JOIN user u ON p.user_id = u.id
    WHERE p.bidang_id = '$bidang_id'
    ORDER BY p.created_at DESC
");


$posts = [];
while ($row = mysqli_fetch_assoc($q_pengumuman)) {
    $id_pengumuman = $row['id']; // ini ID dari tabel pengumuman
   $q_komen = mysqli_query($conn, "
    SELECT k.*, u.nama AS nama_user
    FROM komentar_pengumuman k
    JOIN user u ON k.user_id = u.id
    WHERE k.id_pengumuman = '$id_pengumuman'
    ORDER BY k.created_at ASC
");

    $row['komentar'] = mysqli_fetch_all($q_komen, MYSQLI_ASSOC);
    $posts[] = $row;
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Dashboard Pegawai - <?= htmlspecialchars($nama_bidang ?? 'Bidang') ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; }
    /* overlay untuk teks header agar kontras di banner */
    .banner-overlay {
      background: linear-gradient(180deg, rgba(0,0,0,0.35) 0%, rgba(0,0,0,0.45) 100%);
    }
  </style>
</head>
<body class="bg-gray-100 min-h-screen">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Header/banner -->
    <div class="relative rounded-xl overflow-hidden shadow">
      <img src="<?= htmlspecialchars($banner ?? '../../assets/dpmptsp.png') ?>" alt="Banner" class="w-fu h-44 md:h-56 object-cover">
      <div class="absolute inset-0 banner-overlay"></div>
      <div class="absolute inset-0 flex items-center">
        <div class="px-6 md:px-12">
          <h1 class="text-white text-2xl md:text-4xl font-extrabold leading-tight drop-shadow"><?= htmlspecialchars($nama_bidang ?? 'Nama Bidang') ?></h1>
          <p class="text-white/90 mt-1 md:mt-2 text-sm md:text-base"><?= htmlspecialchars($deskripsi_bidang ?? '') ?></p>
        </div>
      </div>
      <div class="absolute right-4 bottom-4">
        <!-- optional icon -->
        <button class="bg-white/20 backdrop-blur-sm text-white px-3 py-1 rounded-full hover:bg-white/30">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6l4 2" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Tabs & left column layout -->
    <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Left column: summary / upcoming / people (small card) -->
      <aside class="space-y-4">
        <div class="bg-white p-4 rounded-lg shadow-sm">
          <h3 class="text-sm font-semibold text-gray-700">Upcoming</h3>
          <p class="mt-3 text-sm text-gray-500">Woohoo, no work due soon!</p>
          <a href="ditugaskan.php" class="text-sm text-blue-600 mt-3 inline-block">View all</a>
        </div>
      </aside>

      <!-- Main column: stream/posts -->
      <main class="lg:col-span-2">
        <!-- Tabs (Stream / Classwork / People) -->
        <nav class="bg-white rounded-lg shadow-sm px-4 py-3 flex items-center gap-4">
          <a href="dashboard.php" class="text-sm font-medium text-blue-600 border-b-2 border-blue-600 pb-1">Stream</a>
          <a href="ditugaskan.php" class="text-sm text-gray-600 hover:text-gray-800">Ditugaskan</a>
          <a href="belum_diserahkan.php" class="text-sm text-gray-600 hover:text-gray-800">Dikerjakan</a>
          <a href="selesai.php" class="text-sm text-gray-600 hover:text-gray-800">Selesai</a>
         <!--<a href="tugas_harian.php" class="text-sm text-gray-600 hover:text-gray-800">Tugas Harian</a>-->
          <div class="ml-auto text-sm text-gray-500">You are viewing as: <span class="ml-2 font-medium text-gray-700"><?= htmlspecialchars($_SESSION['nama'] ?? 'Pegawai') ?></span></div>
        </nav>

        <!-- Post composer -->
         <div class="bg-white mt-4 p-4 rounded-lg shadow-sm">
          <!-- Placeholder awal -->
           <div id="composer-placeholder" class="flex items-center gap-3 cursor-pointer">
            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-sm font-medium text-gray-700">
              <?= strtoupper(substr($_SESSION['nama'] ?? 'U',0,1)) ?>
            </div>
            <div class="flex-1 text-gray-500 border border-gray-300 rounded-full px-4 py-2 hover:bg-gray-50">Umumkan sesuatu ke bidang...</div>
          </div>

          <!-- Form asli, awalnya hidden -->
           <form id="form-post" method="POST" action="buat_post.php" class="space-y-3 mt-3 hidden">
            <input type="hidden" name="id_bidang" value="<?= (int)($id_bidang ?? 0) ?>">
            <div class="flex items-start gap-3">
              <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-sm font-medium text-gray-700">
                <?= strtoupper(substr($_SESSION['nama'] ?? 'U',0,1)) ?>
              </div>
              <!-- Editor contenteditable -->
              <div id="editor" contenteditable="true"
               class="flex-1 min-h-[80px] p-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300"
               placeholder="Tulis pengumuman kamu..."></div>
               <input type="hidden" name="konten" id="konten">
              </div>
              <div class="flex items-center justify-between">
                <!-- Ikon editor -->
                 <!-- Toolbar atas -->
                  <div class="flex items-center gap-1 text-sm text-gray-500 border-b pb-1">
                    <!-- Bold -->
                     <button type="button" class="format-btn px-2 py-1 rounded hover:bg-gray-100 font-bold" data-cmd="bold" title="Bold">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 4H6v16h7a5 5 0 000-10H6" />
                      </svg>
                    </button>
                    <!-- Italic -->
                     <button type="button" class="format-btn px-2 py-1 rounded hover:bg-gray-100 italic" data-cmd="italic" title="Italic">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 5h8M6 19h8M14 5l-4 14" />
                      </svg>
                    </button>
                    <!-- Underline -->
                     <button type="button" class="format-btn px-2 py-1 rounded hover:bg-gray-100 underline" data-cmd="underline" title="Underline">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 4v6a6 6 0 0012 0V4M4 20h16" />
                      </svg>
                    </button>
                    <!-- Bullet List -->
                     <button type="button" class="format-btn px-2 py-1 rounded hover:bg-gray-100" data-cmd="insertUnorderedList" title="Bullet List">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                      </svg>
                    </button>
                    <!-- Clear -->
                     <button type="button" id="clear-format" class="px-2 py-1 rounded hover:bg-gray-100" title="Clear Formatting">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 4H8a2 2 0 00-2 2v12a2 2 0 002 2h8m0-4h6m-3-3v6" />
                      </svg>
                    </button>
                  </div>
                  <!-- Toolbar bawah -->
                   <div class="flex items-center justify-between mt-2">
                    <div class="flex gap-2">
                      <!-- Upload File -->
                       <button type="button" id="btn-upload" class="p-2 rounded-full hover:bg-gray-100" title="Upload File">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M4 16v4h16v-4m-4-4l-4-4-4 4m4-4v12" />
                        </svg>
                      </button>
                      <!-- Add Link -->
                       <button type="button" id="btn-link" class="p-2 rounded-full hover:bg-gray-100" title="Add Link">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M13.828 10.172a4 4 0 015.656 5.656
                          m-1.415-4.242l-4.243 4.243
                          a4 4 0 01-5.656-5.656l1.414-1.414" />
                        </svg>
                      </button>
                    </div>
                    <!-- Hidden file input -->
                     <input type="file" id="file-input" name="file_upload" class="hidden">
                     <input type="text" name="teks" class="hidden">
                     <textarea name="teks" id="teks" hidden></textarea>

<!-- Editor -->
<div id="editor" contenteditable="true" class="border p-2 rounded min-h-[100px]">
</div>

                     <div id="editor" contenteditable="true" class="border p-2 rounded">
                     <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700">Kirim</button>
                    </div>
                </div>
              </form>
            </div>
            <!-- Posts list -->
             <div class="mt-4 space-y-4">
              <?php if (!empty($posts)): ?>
                <?php foreach($posts as $post): ?>
                  <article class="bg-white rounded-lg shadow-sm p-4">
                    <div class="flex items-start gap-3">
                      <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center text-sm font-semibold text-gray-700">
                        <?= strtoupper(substr($post['nama_user'],0,1)) ?>
                      </div>
                      <div class="flex-1">
                        <div class="flex items-center justify-between">
                          <div>
                            <div class="text-sm font-semibold text-gray-800"><?= htmlspecialchars($post['nama_user']) ?></div>
                            <div class="text-xs text-gray-500"><?= date("d M Y H:i", strtotime($post['created_at'])) ?></div>
                            <div class="text-sm font-semibold text-gray-800"><?= htmlspecialchars($post['teks']) ?></div>
                            
                          </div>
                          <div class="relative">
                            <!-- Tombol titik tiga -->
                             <button type="button" onclick="toggleMenu(this)" class="text-gray-400 hover:text-gray-600 focus:outline-none">...</button>
                            <!-- Menu dropdown -->
                            <div class="hidden absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-lg shadow-lg z-10">
                                <button onclick="copyLink('dashboard.php?id=<?= $post['id'] ?>')" 
                                    class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100">Copy Link</button>

                                <!-- Tombol delete -->
                                <button onclick="deletePengumuman(<?= $post['id'] ?>)" 
                                    class="block w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-100">Delete</button>
                </div>
                        <!-- Comments -->
                         <div class="mt-4 border-t pt-3">
                          <?php if (!empty($post['komentar'])): ?>
                            <?php foreach($post['komentar'] as $komen): ?>
                              <div class="flex items-start gap-3 py-2">
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-xs text-gray-700"><?= strtoupper(substr($komen['nama_user'],0,1)) ?></div>
                                <div class="flex-1">
                                  <div class="text-sm"><span class="font-medium"><?= htmlspecialchars($komen['nama_user']) ?></span> <span class="text-gray-500 text-xs">· <?= date("d M Y H:i", strtotime($komen['created_at'])) ?></span></div>
                                  <div class="text-sm text-gray-700 mt-1"><?= htmlspecialchars($komen['komentar']) ?></div>
                                </div>
                              </div>
                              <?php endforeach; ?>
                              <?php endif; ?>
                              <!-- comment form -->
                               <form method="POST" action="dashboard.php" class="flex items-center gap-3 mt-2">
                                <input type="hidden" name="id_pengumuman" value="<?= (int)$post['id'] ?>">
                                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs text-gray-700"><?= strtoupper(substr($_SESSION['nama'] ?? 'U',0,1)) ?></div>
                                <input name="konten" placeholder="Tambahkan komentar..." class="flex-1 px-3 py-2 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" />
                                <button type="submit" class="text-blue-600 text-sm font-medium">Kirim</button>
                              </form>
                            </div>
                          </div>
                        </div>
                      </article>
                      <?php endforeach; ?>
                      <?php else: ?>
                        <div class="bg-white p-6 rounded-lg shadow-sm text-center text-gray-500">Belum ada pengumuman. Buat pengumuman pertama!</div>
                        <?php endif; ?>
                      </div>
                    </main>
                  </div>
                </div>

                <!-- Opsional: kirim AJAX untuk Post/Comment (supaya tidak reload) -->
              <script>
                
                 // contoh AJAX post sederhana (optional)
                 document.getElementById('form-post')?.addEventListener('submit', function(e){
                  document.getElementById('teks').value = document.getElementById('editor').innerHTML;
                 // jika ingin kirim ajax, cegah submit default dan kirim fetch ke buat_post.php
                 //Otherwise remove this block to use normal form submit.
                 e.preventDefault();
                 const fd = new FormData(this);
                 fetch('buat_post.php', {
                  method: 'POST',
                  body: fd
                 }).then(r => r.text()).then(res => {
                  // setelah sukses reload atau append hasil
                 location.reload();
                }).catch(err => console.error(err));
              });
              const placeholder = document.getElementById('composer-placeholder');
              const formPost = document.getElementById('form-post');
              
              if (placeholder && formPost) {
                placeholder.addEventListener('click', () => {
                  placeholder.classList.add('hidden');
                  formPost.classList.remove('hidden');
                });
              }
              document.querySelectorAll(".format-btn").forEach(btn => {
                btn.addEventListener("click", () => {
                  const cmd = btn.getAttribute("data-cmd");
                  document.execCommand(cmd, false, null);
                });
              });
              document.getElementById("clear-format").addEventListener("click", () => {
                document.execCommand("removeFormat", false, null);
              });
              document.getElementById("form-post").addEventListener("submit", function() {
                document.getElementById("konten").value = document.getElementById("editor").innerHTML;
              });
              // === Upload File ===
              document.getElementById("btn-upload").addEventListener("click", () => {
              document.getElementById("file-input").click();
              });
              document.getElementById("file-input").addEventListener("change", function() {
                if (this.files.length > 0) {
                  const fileName = this.files[0].name;
                  document.getElementById("editor").innerHTML += `<p><strong>File:</strong> ${fileName}</p>`;
                }
              });
              // === Add Link ===
              document.getElementById("btn-link").addEventListener("click", () => {
                const url = prompt("Masukkan URL:");
                if (url) {
                  document.execCommand("createLink", false, url);
                }
              });
              function deletePengumuman(id) {
    if (confirm("Yakin ingin menghapus pengumuman ini?")) {
        window.location.href = "hapus_pengumuman.php?id=" + id;
    }
}
              function toggleMenu(el) {
              // Tutup semua menu lain
              document.querySelectorAll('.relative .absolute').forEach(menu => menu.classList.add('hidden'));
              // Toggle menu ini
              el.nextElementSibling.classList.toggle('hidden');
              }
              
              function copyLink(link) {
              navigator.clipboard.writeText(window.location.origin + '/' + link);
              alert("Link copied!");
              }
              function reportAbuse(id) {
              alert("Report Abuse for Post ID: " + id);
              }
              
              // Tutup menu kalau klik di luar
              document.addEventListener('click', function(e) {
              if (!e.target.closest('.relative')) {
              document.querySelectorAll('.relative .absolute').forEach(menu => menu.classList.add('hidden'));
              }
              

                            });

</script>

              </script>
  </body>
</html>
