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
    <title>Verifikasi OTP - UMSIDA</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="auth-page">

    <div class="auth-container" style="display: flex; flex-direction: column; align-items: center; width: 100%;">
        
        <div style="margin-bottom: 30px;">
             <img src="assets/images/logo-umsida.png" alt="UMSIDA Logo" style="max-width: 250px; display: block;">
        </div>

        <div class="auth-card">
            <div class="auth-header">
                <h3>Verifikasi OTP</h3>
                <p style="font-size: 0.9rem; color: #666;">Silakan masukkan kode 6 digit yang dikirim ke email Anda.</p>
            </div>
    
            <form action="process/auth.php" method="POST">
                <input type="hidden" name="action" value="verify_otp">
                
                <div class="form-group">
                    <input type="text" name="otp_code" placeholder="Kode OTP" maxlength="6" required style="text-align: center; letter-spacing: 5px; font-size: 1.2rem;">
                </div>
    
                <button type="submit" class="btn-primary" style="width: 100%;">Verifikasi</button>
            </form>
    
            <div style="margin-top: 20px; font-size: 0.8rem;">
                <a href="login.php" style="color: var(--primary-blue);">Kembali ke Login</a>
            </div>
        </div>
    </div>

</body>
</html>
