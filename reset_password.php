<?php
session_start();
if (!isset($_SESSION['reset_verified']) || !$_SESSION['reset_verified']) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - UMSIDA Barang Hilang</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="auth-page">

    <div class="auth-card">
        <h2 style="font-size: 24px; font-weight: bold; margin-bottom: 20px;">Reset Password</h2>
        
        <form action="process/auth.php" method="POST">
            <input type="hidden" name="action" value="reset_password">
            
            <div class="form-group">
                <input type="password" name="password" placeholder="Password Baru" required>
            </div>

            <div class="form-group">
                <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required>
            </div>

            <button type="submit" class="btn-primary">Ubah Password</button>
        </form>
    </div>

</body>
</html>
