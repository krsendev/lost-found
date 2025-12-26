<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lapor Temuan - UMSIDA</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <header class="header">
        <div class="logo-container">
            <span class="hamburger" onclick="openNav()">&#9776;</span>
            <div class="logo-text">
                <img src="assets/images/logo-umsida.png" alt="UMSIDA">
            </div>
        </div>

        <nav class="desktop-nav">
             <a href="index.php">Beranda</a>
             <a href="gallery.php">Galeri Temuan</a>
             <a href="dashboard.php">Laporan Kehilangan</a>
             <a href="form_selection.php" class="active">Formulir</a>
        </nav>

        <div class="header-icons">
            <a href="profile.php" style="text-decoration: none;">
                <span style="font-size: 24px;"><i class="fa fa-user" aria-hidden="true" style="color: white;"></i></span>
            </a>
        </div>
    </header>

    <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a href="index.php">Beranda</a>
        <a href="gallery.php">Galeri Temuan</a>
        <a href="dashboard.php">Laporan Kehilangan</a>
        <a href="form_selection.php">Formulir</a>
        <a href="process/auth.php?action=logout" style="color: #ff6b6b;">Logout</a>
    </div>

    <div id="main">
        <div class="form-container">
            <form action="process/submit_report.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="type" value="found">
                
                <div class="form-group">
                    <label>Barang yang ditemukan</label>
                    <input type="text" name="item_name" placeholder="Contoh: KTP, Tas" required>
                </div>

                <div class="form-group">
                    <label>Deskripsi Barang</label>
                    <textarea name="description" rows="4" placeholder="Ciri-ciri barang..." required></textarea>
                </div>

                <div class="form-group">
                    <label>Kapan anda menemukan barang tersebut?</label>
                    <input type="date" name="date" required>
                </div>

                <div class="form-group">
                    <label>Dimana anda menemukan barang tersebut?</label>
                    <input type="text" name="location" placeholder="Lokasi ditemukan" required>
                </div>
                
                 <div class="form-group">
                    <label>Foto Barang Temuan (Wajib)</label>
                    <input type="file" name="image" accept="image/*" required>
                </div>
                
                <div class="form-group">
                    <label>Nomor Telepon Anda (Untuk dihubungi)</label>
                    <input type="text" name="phone" placeholder="08xxxxx" required>
                </div>

                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button type="button" class="btn-primary" style="background: gray;" onclick="window.history.back()">Kembali</button>
                    <button type="submit" class="btn-primary">Kirim</button>
                </div>
            </form>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>
