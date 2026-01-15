<?php
session_start();
require 'config/db.php';
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit; }

$query = "SELECT items.*, users.name as pelapor_name FROM items JOIN users ON items.user_id = users.id WHERE type='found' AND status='available' ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Temuan - UMSIDA</title>
    <link rel="stylesheet" href="assets/css/style.css?v=<?= time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .modal {
            display: none; 
            position: fixed; 
            z-index: 2000; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            background-color: rgba(0,0,0,0.8); 
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 15px;
            width: 90%;
            max-width: 400px;
            position: relative;
            text-align: center;
        }
        .close-modal {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 24px;
            cursor: pointer;
        }
        .modal-details {
            text-align: left;
            margin-top: 15px;
        }
        .detail-box {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 10px;
            font-size: 0.9rem;
        }
    </style>
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
            <a href="gallery.php" class="active">Galeri Temuan</a>
            <a href="lost_items.php">Laporan Kehilangan</a>
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
        <div class="gallery-grid">
            <?php while($item = mysqli_fetch_assoc($result)): ?>
                <div class="gallery-item" onclick='openModal(<?= json_encode($item) ?>)'>
                    <img src="<?= $item['image'] ? 'uploads/'.$item['image'] : 'https://placehold.co/150x150?text=No+Image' ?>" alt="<?= htmlspecialchars($item['item_name']) ?>">
                    <div class="item-info">
                        <b><?= htmlspecialchars($item['item_name']) ?></b><br>
                        <small style="color: #666;"><?= htmlspecialchars($item['location']) ?></small><br>
                        <span style="display: inline-block; background-color: #e74c3c; color: white; font-size: 0.7rem; padding: 2px 6px; border-radius: 4px; margin-top: 3px;">Belum Ditemukan</span>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <div id="itemModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal()">&times;</span>
            <h3 id="modalTitle" style="margin-bottom: 15px;">Nama Barang</h3>
            <img id="modalImg" src="" style="width: 150px; height: 150px; object-fit: cover; border-radius: 10px; margin-bottom: 15px;">
            
            <div class="modal-details">
                <div class="detail-box">
                    <p style="margin: 0;" id="modalDesc">
                    </p>
                </div>
                
                <div class="detail-box">
                    Ditemukan: <span id="modalDate"></span><br>
                </div>

                <div class="detail-box">
                    Lokasi: <span id="modalLocation"></span>
                </div>

                <div class="detail-box">
                    Pelapor: <span id="modalReporter"></span><br>
                    Telp: <span id="modalContact"></span>
                </div>

                <button class="btn-primary" onclick="closeModal()" style="background-color: var(--dark-blue);">Back</button>
            </div>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
    <script>
        function openModal(item) {
            document.getElementById('itemModal').style.display = 'flex';
            document.getElementById('modalTitle').innerText = item.item_name;
            document.getElementById('modalImg').src = item.image ? 'uploads/' + item.image : 'https://placehold.co/150x150?text=No+Image';
            
            document.getElementById('modalDesc').innerText = item.description;
            
            if (item.found_date) {
                let parts = item.found_date.split('-');
                let formattedDate = `${parts[2]}/${parts[1]}/${parts[0]}`;
                document.getElementById('modalDate').innerText = formattedDate;
            } else {
                document.getElementById('modalDate').innerText = '-';
            }
            document.getElementById('modalLocation').innerText = item.location;
            document.getElementById('modalReporter').innerText = item.pelapor_name;
            document.getElementById('modalContact').innerText = item.contact_phone;
        }

        function closeModal() {
            document.getElementById('itemModal').style.display = 'none';
        }

        window.onclick = function(event) {
            let modal = document.getElementById('itemModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>
