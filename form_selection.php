<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir - UMSIDA</title>
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
            <h3>Apa yang ingin anda Laporkan?</h3>
            <br>
            
            <a href="form_lost.php" style="text-decoration: none;">
                <button class="btn-primary" style="margin-bottom: 20px;">Kehilangan Barang</button>
            </a>
            
            <a href="form_found.php" style="text-decoration: none;">
                <button class="btn-primary">Menemukan Barang</button>
            </a>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>
