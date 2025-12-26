<?php
session_start();
require 'config/db.php';
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit; }

$query = "SELECT items.*, users.name as pelapor_name FROM items JOIN users ON items.user_id = users.id WHERE type='lost' ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Masuk - UMSIDA</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <header class="header">
        <div class="logo-container" onclick="openNav()">
            <span style="font-size: 30px; cursor: pointer;">&#9776;</span>
        </div>
        <div class="logo-text">LAPORAN MASUK</div>
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
        <div style="padding: 10px;">
            <?php while($item = mysqli_fetch_assoc($result)): ?>
                <div class="procedure-card" style="background: white; border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; display: flex; align-items: start; gap: 15px;">
                     <div style="width: 5px; height: 100px; background-color: <?= $item['type'] == 'found' ? 'green' : 'red' ?>; border-radius: 5px;"></div>
                     
                     <div style="flex-grow: 1;">
                        <h4 style="margin-bottom: 5px; color: var(--primary-blue);">Laporan <?= ucfirst($item['type'] == 'found' ? 'Penemuan' : 'Kehilangan') ?> - <?= htmlspecialchars($item['item_name']) ?> <small style="color: #666; font-size: 12px;">oleh <?= htmlspecialchars($item['pelapor_name'] ?? 'Unknown') ?></small></h4>
                        
                        <div style="font-size: 0.85rem; color: #555;">
                            <ul>
                                <li><b>Deskripsi:</b> <?= htmlspecialchars($item['description']) ?></li>
                                <li><b>Lokasi:</b> <?= htmlspecialchars($item['location']) ?></li>
                                <li><b>Tanggal:</b> <?= htmlspecialchars($item['found_date']) ?></li>
                                <li><b>Kontak:</b> <?= htmlspecialchars($item['contact_phone']) ?></li>
                            </ul>
                        </div>
                     </div>
                </div>
            <?php endwhile; ?>
            
            <?php if(mysqli_num_rows($result) == 0): ?>
                <p style="text-align: center; color: #666; margin-top: 50px;">Belum ada laporan yang anda buat.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>
