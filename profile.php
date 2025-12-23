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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <style>
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.8); }
        .modal-content { background-color: #fefefe; margin: 5% auto; padding: 20px; border: 1px solid #888; width: 90%; max-width: 600px; border-radius: 10px; }
        .img-container { width: 100%; max-height: 400px; margin-bottom: 20px; }
        .img-container img { max-width: 100%; }
        .modal-buttons { text-align: right; }
        .btn-cancel { background-color: #ccc; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; margin-right: 10px; }
        .btn-save { background-color: #4CAF50; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>

    <header class="header">
        <div class="logo-container" onclick="openNav()">
            <span style="font-size: 30px; cursor: pointer;">&#9776;</span>
        </div>
        <div class="logo-text">PROFIL SAYA</div>
        <div class="header-icons">
             <a href="profile.php" style="text-decoration: none; color: white;">
                <span style="font-size: 24px;">👤</span>
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
                <?php 
                $profile_img = !empty($user['profile_image']) ? 'assets/uploads/profiles/'.$user['profile_image'] : '';
                ?>
                <!-- Form without auto-submit -->
                <form id="profileForm" enctype="multipart/form-data">
                    <input type="file" name="profile_image" id="profileImageInput" style="display: none;" accept="image/*">
                </form>
                    
                <div onclick="document.getElementById('profileImageInput').click()" style="width: 100px; height: 100px; background: #ddd; border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; font-size: 40px; overflow: hidden; cursor: pointer; position: relative;">
                    <?php if ($profile_img): ?>
                        <img src="<?= htmlspecialchars($profile_img) ?>?t=<?= time() ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        👤
                    <?php endif; ?>
                    
                    <div style="position: absolute; bottom: 0; width: 100%; background: rgba(0,0,0,0.5); color: white; font-size: 12px; padding: 2px 0;">
                        Edit
                    </div>
                </div>
                
                <h2 style="margin-bottom: 5px;"><?= htmlspecialchars($user['name']) ?></h2>
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
            </div>
        </div>
    </div>

    <!-- Crop Modal -->
    <div id="cropModal" class="modal">
        <div class="modal-content">
            <h3>Sesuaikan Foto Profil</h3>
            <div class="img-container">
                <img id="imageToCrop" src="">
            </div>
            <div class="modal-buttons">
                <button class="btn-cancel" onclick="closeCropModal()">Batal</button>
                <button class="btn-save" onclick="cropAndUpload()">Simpan</button>
            </div>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script>
        let cropper;
        const input = document.getElementById('profileImageInput');
        const modal = document.getElementById('cropModal');
        const image = document.getElementById('imageToCrop');

        input.addEventListener('change', function (e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                const file = files[0];
                const reader = new FileReader();
                reader.onload = function (e) {
                    image.src = e.target.result;
                    modal.style.display = 'block';
                    if (cropper) {
                        cropper.destroy();
                    }
                    cropper = new Cropper(image, {
                        aspectRatio: 1,
                        viewMode: 1,
                        autoCropArea: 1,
                    });
                };
                reader.readAsDataURL(file);
            }
        });

        function closeCropModal() {
            modal.style.display = 'none';
            input.value = ''; // Reset input
            if (cropper) {
                cropper.destroy();
            }
        }

        function cropAndUpload() {
            if (!cropper) return;

            const canvas = cropper.getCroppedCanvas({
                width: 500,
                height: 500,
                imageSmoothingQuality: 'high',
            });

            canvas.toBlob(function (blob) {
                const formData = new FormData();
                formData.append('profile_image', blob, 'profile.jpg');
                formData.append('action', 'update_profile');

                fetch('process/auth.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    // Since auth.php returns a full HTML page with alert and redirect script, 
                    // we can't easily parse JSON. Ideally we'd change backend to JSON, 
                    // but for minimal changes, we'll just reload.
                    // Or we could check if response text contains "success" keywords if we were returning clean text.
                    // Given the current backend echoes script tags, reloading the page will actually NOT show the alert 
                    // from the backend response because that response is just discarded here.
                    // We should trigger a reload which will show the new image.
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat upload.');
                });
            }, 'image/jpeg', 0.8); // 80% quality JPEG
        }

        // Close modal if user clicks outside
        window.onclick = function(event) {
            if (event.target == modal) {
                closeCropModal();
            }
        }
    </script>
</body>
</html>