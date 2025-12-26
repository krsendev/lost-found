<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '../config/db.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'register') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $nim = mysqli_real_escape_string($conn, $_POST['nim']); // New NIM input
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic validation
    if ($password !== $confirm_password) {
        echo "<script>alert('Password tidak cocok!'); window.location='../register.php';</script>";
        exit;
    }

    // Check email OR NIM uniqueness
    $check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email' OR nim = '$nim'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Email atau NIM sudah terdaftar!'); window.location='../register.php';</script>";
        exit;
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user with NIM
    $query = "INSERT INTO users (name, email, nim, password) VALUES ('$nama', '$email', '$nim', '$hashed_password')";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Pendaftaran berhasil! Silakan login.'); window.location='../login.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "'); window.location='../register.php';</script>";
    }

} elseif ($action === 'login') {
    $input = mysqli_real_escape_string($conn, $_POST['email']); // Can be email or NIM
    $password = $_POST['password'];

    // Check Email OR NIM
    // Also checking name just in case? No, login is usually email/nim.
    // Ensure we select * so we get 'name'
    $query = "SELECT * FROM users WHERE email = '$input' OR nim = '$input'";
    $result = mysqli_query($conn, $query);
    if (!$result) { die("Database Error: " . mysqli_error($conn)); }

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'], // Fixed: username -> name
                'email' => $user['email'],
                'nim' => $user['nim'], // Add NIM to session
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
    
    // Check if user exists with BOTH Email AND NIM
    $query = "SELECT id, email FROM users WHERE email = '$email' AND nim = '$nim'";
    $check = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($check) > 0) {
        // Valid credentials -> Allow reset
        $_SESSION['reset_email'] = $email;
        $_SESSION['reset_verified'] = true; // Directly verified
        
        echo "<script>window.location='../reset_password.php';</script>";
    } else {
        echo "<script>alert('Kombinasi Email dan NIM tidak ditemukan!'); window.location='../forgot_password.php';</script>";
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
    // If accessed directly without action
    header("Location: ../login.php");
}
?>
