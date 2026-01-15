<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '../config/db.php';
require '../config/mail.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'register') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $nim = mysqli_real_escape_string($conn, $_POST['nim']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        echo "<script>alert('Password tidak cocok!'); window.location='../register.php';</script>";
        exit;
    }

    $check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email' OR nim = '$nim'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Email atau NIM sudah terdaftar!'); window.location='../register.php';</script>";
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $otp_code = sprintf("%06d", mt_rand(1, 999999));
    
    $query = "INSERT INTO users (name, email, nim, password, otp_code, otp_expiry, is_verified) 
            VALUES ('$nama', '$email', '$nim', '$hashed_password', '$otp_code', DATE_ADD(NOW(), INTERVAL 15 MINUTE), 0)";
    
    if (mysqli_query($conn, $query)) {
        $subject = "Kode Verifikasi Registrasi - Lost & Found";
        $message = "Halo $nama,<br><br>Kode verifikasi (OTP) Anda adalah: <b>$otp_code</b>.<br>Kode ini berlaku selama 15 menit.";
        
        if (sendOTP($email, $subject, $message)) {
            $_SESSION['pending_email'] = $email;
            echo "<script>alert('Registrasi berhasil! Silakan cek email untuk kode OTP.'); window.location='../otp_verification.php';</script>";
        } else {
            echo "<script>alert('Gagal mengirim OTP. Silakan hubungi admin.'); window.location='../register.php';</script>";
        }
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "'); window.location='../register.php';</script>";
    }

} elseif ($action === 'login') {
    $input = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = '$input' OR nim = '$input'";
    $result = mysqli_query($conn, $query);
    if (!$result) { die("Error Database: " . mysqli_error($conn)); }

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            
            if ($user['is_verified'] == 0) {
                echo "<script>alert('Akun belum diverifikasi! Silakan cek email Anda atau login ulang untuk minta OTP baru.'); window.location='../login.php';</script>";
                exit;
            }

            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'nim' => $user['nim'],
                'role' => $user['role'],
                'profile_image' => $user['profile_image'] ?? null
            ];
            header("Location: ../index.php");
        } else {
            echo "<script>alert('Password salah!'); window.location='../login.php';</script>";
        }
    } else {
        echo "<script>alert('User tidak ditemukan!'); window.location='../login.php';</script>";
    }

} elseif ($action === 'forgot_password') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $nim = mysqli_real_escape_string($conn, $_POST['nim']);
    
    $query = "SELECT id, name FROM users WHERE email = '$email' AND nim = '$nim'";
    $check = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($check) > 0) {
        $user = mysqli_fetch_assoc($check);
        
        $otp_code = sprintf("%06d", mt_rand(1, 999999));
        
        $updateQuery = "UPDATE users SET otp_code = '$otp_code', otp_expiry = DATE_ADD(NOW(), INTERVAL 15 MINUTE) WHERE email = '$email'";
        mysqli_query($conn, $updateQuery);
        
        $subject = "Kode Reset Password - Lost & Found";
        $message = "Halo " . $user['name'] . ",<br><br>Kode verifikasi (OTP) untuk reset password adalah: <b>$otp_code</b>.<br>Kode ini berlaku selama 15 menit.";
        
        if (sendOTP($email, $subject, $message)) {
            $_SESSION['pending_email'] = $email;
            $_SESSION['is_reset_flow'] = true;
            echo "<script>alert('Kode OTP telah dikirim ke email Anda.'); window.location='../otp_verification.php';</script>";
        } else {
            echo "<script>alert('Gagal mengirim OTP.'); window.location='../forgot_password.php';</script>";
        }

    } else {
        echo "<script>alert('Kombinasi Email dan NIM tidak ditemukan!'); window.location='../forgot_password.php';</script>";
    }

} elseif ($action === 'verify_otp') {
    $otp_code = mysqli_real_escape_string($conn, $_POST['otp_code']);
    $email = $_SESSION['pending_email'] ?? '';
    
    if (empty($email)) {
        header("Location: ../login.php");
        exit;
    }
    
    $query = "SELECT * FROM users WHERE email = '$email' AND otp_code = '$otp_code' AND otp_expiry > NOW()";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        mysqli_query($conn, "UPDATE users SET otp_code = NULL, otp_expiry = NULL, is_verified = 1 WHERE id = " . $user['id']);
        
        if (isset($_SESSION['is_reset_flow']) && $_SESSION['is_reset_flow'] === true) {
            $_SESSION['reset_email'] = $email;
            $_SESSION['reset_verified'] = true;
            unset($_SESSION['is_reset_flow']);
            unset($_SESSION['pending_email']);
            header("Location: ../reset_password.php");
        } else {
            unset($_SESSION['pending_email']);
            echo "<script>alert('Verifikasi berhasil! Silakan login.'); window.location='../login.php';</script>";
        }
    } else {
        echo "<script>alert('Kode OTP salah atau sudah kadaluarsa!'); window.location='../otp_verification.php';</script>";
    }

} elseif ($action === 'reset_password') {
    if (!isset($_SESSION['reset_verified']) || !$_SESSION['reset_verified']) {
        header("Location: ../login.php");
        exit;
    }
    
    $email = $_SESSION['reset_email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($password !== $confirm_password) {
        echo "<script>alert('Password tidak cocok!'); window.location='../reset_password.php';</script>";
        exit;
    }
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $query = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";
    if (mysqli_query($conn, $query)) {
        unset($_SESSION['reset_email']);
        unset($_SESSION['reset_verified']);
        echo "<script>alert('Password berhasil diubah, silakan login.'); window.location='../login.php';</script>";
    } else {
        echo "<script>alert('Gagal mengubah password.'); window.location='../reset_password.php';</script>";
    }

} elseif ($action === 'logout') {
    session_destroy();
    header("Location: ../login.php");

} else {
    header("Location: ../login.php");
}
?>
