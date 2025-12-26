<?php
session_start();
require 'config/db.php';
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit; }

$user = $_SESSION['user']; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - UMSIDA</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .post-history { margin-top: 30px; text-align: left; }
        .post-card { background: #f9f9f9; padding: 15px; border-radius: 10px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; border: 1px solid #eee;}
        .post-info h4 { margin: 0 0 5px 0; color: #333; }
        .post-info p { margin: 0; color: #666; font-size: 14px; }
        .btn-delete { background: #ff6b6b; color: white; border: none; padding: 5px 15px; border-radius: 5px; cursor: pointer; text-decoration: none; font-size: 14px;}
        .btn-delete:hover { background: #ee5253; }
        .badge { padding: 3px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .badge-lost { background: #ffeaea; color: #ff6b6b; }
        .badge-found { background: #eaffea; color: #2ecc71; }
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
             <a href="gallery.php">Galeri Temuan</a>
             <a href="dashboard.php">Laporan Kehilangan</a>
             <a href="form_selection.php">Formulir</a>
        </nav>

        <div class="header-icons">
             <a href="profile.php" style="text-decoration: none; color: white;">
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
            <div style="background: white; padding: 20px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center;">
                
                <!-- White Profile Icon -->
                <div style="width: 100px; height: 100px; background: #ddd; border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; font-size: 40px; color: white;">
                    <i class="fa fa-user" aria-hidden="true" style="color: white;"></i>
                </div>
                
                <h2 style="margin-bottom: 5px;"><?= htmlspecialchars($user['name']) ?></h2>
                <p style="color: #666; margin-bottom: 5px; font-weight: bold;"><?= isset($user['nim']) ? htmlspecialchars($user['nim']) : '-' ?></p>
                <p style="color: #666; margin-bottom: 20px;"><?= htmlspecialchars($user['email']) ?></p>
                
                <div style="text-align: left; border-top: 1px solid #eee; padding-top: 20px;">
                    <div class="form-group">
                        <label>Role</label>
                        <input type="text" value="<?= htmlspecialchars($user['role']) ?>" disabled>
                    </div>
                </div>

                <a href="process/auth.php?action=logout">
                    <button class="btn-primary" style="background-color: #ff6b6b; margin-top: 10px;">Logout</button>
                </a>
            
                <!-- Riwayat Postingan -->
                <div class="post-history">
                    <h3>Riwayat Postingan</h3>
                    <?php
                    $uid = $user['id'];
                    $query = "SELECT * FROM items WHERE user_id = '$uid' ORDER BY created_at DESC";
                    $result = mysqli_query($conn, $query);
                    
                    if (mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) {
                            $typeClass = ($row['type'] == 'lost') ? 'badge-lost' : 'badge-found';
                            $typeLabel = ($row['type'] == 'lost') ? 'Kehilangan' : 'Ditemukan';
                            ?>
                            <div class="post-card">
                                <div class="post-info">
                                    <h4><?= htmlspecialchars($row['item_name']) ?> <span class="badge <?= $typeClass ?>"><?= $typeLabel ?></span></h4>
                                    <p><?= date('d M Y', strtotime($row['created_at'])) ?> - <?= htmlspecialchars($row['location']) ?></p>
                                </div>
                                <a href="process/delete_post.php?id=<?= $row['id'] ?>" class="btn-delete" onclick="return confirm('Yakin ingin menghapus postingan ini?')">Hapus</a>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<p style='color: #999; text-align: center;'>Belum ada postingan.</p>";
                    }
                    ?>
                </div>

            </div>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>