<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../config/db.php';

echo "<h1>Perbaikan Database</h1>";

// Check connection
if (!$conn) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}
echo "Koneksi Database OK.<br>";

// Check if users table exists
$check_table = mysqli_query($conn, "SHOW TABLES LIKE 'users'");
if (mysqli_num_rows($check_table) == 0) {
    die("Tabel 'users' tidak ditemukan! Pastikan Anda sudah mengimport database.sql awal.");
}
echo "Tabel 'users' ditemukan.<br>";

// Check NIM column
$check_nim = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'nim'");
if (mysqli_num_rows($check_nim) > 0) {
    echo "<h2 style='color:green;'>Kolom NIM SUDAH ADA. Tidak perlu tindakan.</h2>";
} else {
    echo "Kolom NIM belum ada. Menambahkan...<br>";
    
    $sql = "ALTER TABLE users ADD COLUMN nim VARCHAR(20) NOT NULL UNIQUE AFTER email";
    
    if (mysqli_query($conn, $sql)) {
        echo "<h2 style='color:green;'>BERHASIL! Kolom NIM telah ditambahkan.</h2>";
    } else {
        echo "<h2 style='color:red;'>GAGAL: " . mysqli_error($conn) . "</h2>";
        // Try without UNIQUE if duplicate data exists
        if (strpos(mysqli_error($conn), 'Duplicate entry') !== false) {
             echo "Mencoba menambahkan tanpa UNIQUE constraint (karena ada data kembar)...<br>";
             $sql_relaxed = "ALTER TABLE users ADD COLUMN nim VARCHAR(20) NOT NULL AFTER email";
             if (mysqli_query($conn, $sql_relaxed)) {
                 echo "<h2 style='color:orange;'>BERHASIL (Relaxed). Kolom NIM ditambahkan (tetapi tidak unik karena data lama).</h2>";
                 echo "Silakan update data NIM user lama secara manual.";
             }
        }
    }
}

// Check Role column
$check_role = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'role'");
if (mysqli_num_rows($check_role) > 0) {
    echo "<h2 style='color:green;'>Kolom ROLE SUDAH ADA. Tidak perlu tindakan.</h2>";
} else {
    echo "Kolom ROLE belum ada. Menambahkan...<br>";
    $sql_role = "ALTER TABLE users ADD COLUMN role ENUM('admin', 'user') DEFAULT 'user' AFTER email"; // Adding after email/nim
    if (mysqli_query($conn, $sql_role)) {
        echo "<h2 style='color:green;'>BERHASIL! Kolom ROLE telah ditambahkan.</h2>";
    } else {
        echo "<h2 style='color:red;'>GAGAL menambahkan ROLE: " . mysqli_error($conn) . "</h2>";
    }
}

echo "<br><a href='../login.php'>Kembali ke Login</a>";
?>
