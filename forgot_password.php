<?php
session_start();
if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - UMSIDA Barang Hilang</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="auth-page">

    <div class="auth-container" style="display: flex; flex-direction: column; align-items: center; width: 100%;">
        
        <div style="margin-bottom: 30px;">
            <img src="assets/images/logo-umsida.png" alt="UMSIDA Logo" style="max-width: 250px; display: block;">
        </div>

        <div class="auth-card">
            
            <h2 style="font-size: 24px; font-weight: bold; margin-bottom: 10px; color: #000;">Lupa Kata Sandi</h2>
            
            <p style="font-size: 14px; margin-bottom: 20px; color: #333;">
                Masukkan Email dan NIM Anda untuk memverifikasi bahwa ini adalah akun anda.
            </p>

            <form action="process/auth.php" method="POST">
                <input type="hidden" name="action" value="forgot_password">
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <input type="email" name="email" placeholder="Email" required 
                        style="background-color: #dcdcdc; color: #555; text-align: center; border-radius: 25px;">
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <input type="text" name="nim" placeholder="NIM" required 
                        style="background-color: #dcdcdc; color: #555; text-align: center; border-radius: 25px;">
                </div>

                <p style="font-size: 12px; margin-bottom: 20px; color: #333;">
                    Jika anda lupa email atau NIM yang terdaftar, silahkan mengirimkan email ke <a href="mailto:admin@senaverse.cloud" style="color: var(--primary-blue); text-decoration: none;">admin@senaverse.cloud</a>
                </p>

                <button type="submit" class="btn-primary" style="width: 150px; border-radius: 25px; padding: 10px 0; font-size: 18px;">Verifikasi</button>
            </form>
        </div>
    </div>

</body>
</html>
