<?php
session_start();
include '../db.php';
$user_id = $_SESSION['user_id'] ?? 0;
?>
<div class="pengumuman-box">
    <form id="formPengumuman" action="../actions/simpan_pengumuman.php" method="POST" enctype="multipart/form-data">
        <textarea id="pengumumanText" name="teks" placeholder="Bagikan pengumuman ke kelas Anda..."></textarea>
        
        <div class="toolbar">
            <!-- Bold, Italic, Underline -->
            <button type="button" class="tool-btn" onclick="formatText('bold')"><b>B</b></button>
            <button type="button" class="tool-btn" onclick="formatText('italic')"><i>I</i></button>
            <button type="button" class="tool-btn" onclick="formatText('underline')"><u>U</u></button>

            <!-- Lampiran -->
            <label class="tool-btn" title="Upload File">
                📎
                <input type="file" name="file" style="display:none;">
            </label>
            <button type="button" class="tool-btn" onclick="tambahLink()">🔗</button>
            <button type="button" class="tool-btn" onclick="tambahYouTube()">📺</button>
            <button type="button" class="tool-btn" onclick="tambahGDrive()">☁</button>
        </div>

        <div class="actions">
            <button type="submit" class="btn-kirim">Kirim</button>
        </div>
    </form>
</div>
<script src="../assets/js/pengumuman.js"></script>
<link rel="stylesheet" href="../assets/css/pengumuman.css">
