<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - UMSIDA</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- FontAwesome for icons (optional, using simple chars for now if no net) -->
</head>
<body>

    <!-- Header -->
    <header class="header">
        <div class="logo-container" onclick="openNav()">
            <span style="font-size: 30px; cursor: pointer;">&#9776;</span> <!-- Hamburger -->
        </div>
        
        <div class="logo-text">
            UMSIDA
            <div style="font-size: 0.6rem; font-weight: normal; letter-spacing: 1px;">DAN PENCARIAN BARANG</div>
        </div>

        <div class="header-icons">
            <a href="profile.php" style="text-decoration: none;">
                <span style="font-size: 24px;"><i class="fa fa-user" aria-hidden="true" style="color: white;"></i></span>
            </a>
        </div>
    </header>

    <!-- Sidebar -->
    <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        
        <!-- Profile Section in Sidebar can be added here if needed -->
        
        <a href="index.php">Beranda</a>
        <a href="gallery.php">Galeri Temuan</a>
        <a href="dashboard.php">Laporan Kehilangan</a>
        <a href="form_selection.php">Formulir</a>
        <a href="process/auth.php?action=logout" style="color: #ff6b6b;">Logout</a>
    </div>

    <!-- Main Content -->
    <div id="main">
        
        <!-- Hero Section -->
        <div class="hero">
            <!-- Placeholder for building image -->
            <div style="width: 100%; height: 200px; background: url('assets/images/gambar-beranda.png') no-repeat center center; background-size: cover;"></div>
            <!-- <div class="hero-text">
                KEHILANGAN BARANG &<br>PENEMUAN BARANG
            </div> -->
        </div>

        <!-- Procedure: Kehilangan Barang -->
        <div class="procedure-card">
            <div class="procedure-title">Prosedur Operasional<br>KEHILANGAN BARANG</div>
            <p style="font-size: 0.9rem; margin-bottom: 10px;">
                Jika kamu menemukan barang hilang di lingkungan <b>UMSIDA</b>, lakukan langkah-langkah berikut:
            </p>
            <ol style="padding-left: 20px; font-size: 0.85rem; margin-bottom: 15px;">
                <li>Jika kamu merasa kehilangan, ingat kapan terakhir kali kamu bersama dengan barang tersebut.</li>
                <li>Laporkan dengan mengisi formulir berikut.</li>
                <li>Isi formulir dengan rinci, termasuk deskripsi barang, lokasi dan waktu terakhir dilihat, serta informasi kontak kamu.</li>
                <li>Tunggu informasi lanjutan apabila barang ditemukan.</li>
            </ol>
        </div>

        <!-- Procedure: Penemuan Barang -->
        <div class="procedure-card">
            <div class="procedure-title">Prosedur Operasional<br>PENEMUAN BARANG</div>
            <p style="font-size: 0.9rem; margin-bottom: 10px;">
                Jika kamu menemukan barang hilang di lingkungan <b>UMSIDA</b>, lakukan langkah-langkah berikut:
            </p>
            <ol style="padding-left: 20px; font-size: 0.85rem; margin-bottom: 15px;">
                <li>Jika kamu menemukan barang hilang, amankan barang tersebut.</li>
                <li>Laporkan dengan mengisi formulir berikut.</li>
                <li>Isi formulir dengan rinci, termasuk deskripsi barang, lokasi dan waktu terakhir dilihat.</li>
                <li>Serahkan barang kepada satpam <b>UMSIDA</b>.</li>
            </ol>
        </div>

    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>