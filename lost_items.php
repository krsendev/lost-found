<?php
session_start();
require 'config/db.php';
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit; }

$query = "SELECT items.*, users.name as pelapor_name FROM items JOIN users ON items.user_id = users.id WHERE type='lost' AND status='available' ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Masuk - UMSIDA</title>
    <link rel="stylesheet" href="assets/css/style.css?v=<?= time(); ?>">
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
            <a href="lost_items.php" class="active">Laporan Kehilangan</a>
            <a href="form_selection.php">Formulir</a>
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
        <a href="lost_items.php">Laporan Kehilangan</a>
        <a href="form_selection.php">Formulir</a>
        <a href="process/auth.php?action=logout" style="color: #ff6b6b;">Logout</a>
    </div>

    <div id="main">
        <div style="padding: 10px;">
            <?php 
            function tanggal_indo($tanggal) {
                $bulan = array (
                    1 =>   'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                );
                $hari_indo = array(
                    'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 
                    'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
                );
                $split = explode('-', $tanggal);
                $tgl_indo = $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
                
                $nama_hari_inggris = date('l', strtotime($tanggal));
                $nama_hari = $hari_indo[$nama_hari_inggris];
                
                return $nama_hari . ', ' . $tgl_indo;
            }
            ?>
            
            <?php while($item = mysqli_fetch_assoc($result)): ?>
                <div style="background-color: #0c2e59; color: white; border-radius: 15px; padding: 25px; margin-bottom: 20px; box-shadow: 0 4px 8px rgba(0,0,0,0.3); position: relative;">
                    
                    <h3 style="text-align: center; margin-bottom: 20px; font-weight: bold; font-size: 1.3rem;">
                        Laporan <?= ucfirst($item['type'] == 'found' ? 'Penemuan' : 'Kehilangan') ?> <?= htmlspecialchars($item['item_name']) ?>
                        <br>
                        <span style="display: inline-block; background-color: #e74c3c; color: white; font-size: 0.8rem; padding: 3px 8px; border-radius: 5px; margin-top: 5px;">Belum Ditemukan</span>
                    </h3>
                    
                    <div style="font-size: 0.95rem; line-height: 1.8; text-align: left;">
                        <div>
                            <strong>Nama barang:</strong> <?= htmlspecialchars($item['item_name']) ?>
                        </div>
                        
                        <div style="margin-top: 5px;">
                            <strong>Ciri-ciri:</strong><br>
                            <span style="display: block; padding-left: 0; color: #e0e0e0;">
                                <?= nl2br(htmlspecialchars($item['description'])) ?>
                            </span>
                        </div>

                        <div style="margin-top: 5px;">
                            <strong>Waktu <?= $item['type'] == 'found' ? 'Ditemukan' : 'Kehilangan' ?>:</strong><br>
                            <span style="padding-left: 0; color: #e0e0e0;">
                                <?= tanggal_indo($item['found_date']) ?>
                            </span>
                        </div>

                        <div style="margin-top: 5px;">
                            <strong>Lokasi terakhir:</strong> <?= htmlspecialchars($item['location']) ?>
                        </div>

                        <div style="margin-top: 5px;">
                            <strong>Hubungi:</strong> <?= htmlspecialchars($item['pelapor_name']) ?> / <?= htmlspecialchars($item['contact_phone']) ?>
                        </div>
                    </div>

                    <?php if(!empty($item['image'])): ?>
                    <div style="margin-top: 15px; text-align: center;">
                        <img src="uploads/<?= $item['image'] ?>" onclick="showImage(this.src)" alt="Foto Barang" style="max-width: 100%; height: auto; max-height: 200px; border-radius: 10px; border: 2px solid rgba(255,255,255,0.2); cursor: zoom-in; transition: transform 0.2s;">
                    </div>
                    <?php endif; ?>

                </div>
            <?php endwhile; ?>
            
            <?php if(mysqli_num_rows($result) == 0): ?>
                <p style="text-align: center; color: #666; margin-top: 50px;">Belum ada laporan yang anda buat.</p>
            <?php endif; ?>
        </div>

        <div id="imgModal" style="display: none; position: fixed; z-index: 3000; padding: 20px; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.9); justify-content: center; align-items: center;">
            <span onclick="document.getElementById('imgModal').style.display='none'" style="position: absolute; top: 20px; right: 30px; color: #f1f1f1; font-size: 40px; font-weight: bold; cursor: pointer;">&times;</span>
            <img class="modal-content" id="imgFull" style="margin: auto; display: block; max-width: 90%; max-height: 90%; border-radius: 10px;">
        </div>
        
        <script>
            function showImage(src) {
                var modal = document.getElementById("imgModal");
                var modalImg = document.getElementById("imgFull");
                modal.style.display = "flex";
                modalImg.src = src;
            }
            
            window.onclick = function(event) {
                var modal = document.getElementById("imgModal");
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        </script>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>
