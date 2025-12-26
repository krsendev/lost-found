<?php
require '../config/db.php';

echo "<h2>Starting Database Migration...</h2>";

// 1. Add NIM Column
$check_nim = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'nim'");
if (mysqli_num_rows($check_nim) == 0) {
    echo "Adding 'nim' column to users table... ";
    $sql_nim = "ALTER TABLE users ADD COLUMN nim VARCHAR(20) NOT NULL UNIQUE AFTER email";
    if (mysqli_query($conn, $sql_nim)) {
        echo "<span style='color:green;'>SUCCESS</span><br>";
    } else {
        echo "<span style='color:red;'>FAILED: " . mysqli_error($conn) . "</span><br>";
    }
} else {
    echo "'nim' column already exists. <span style='color:blue;'>SKIPPED</span><br>";
}

// 2. Create password_resets table (Optional, kept for backward compatibility if needed, though we moved to NIM flow)
$check_table = mysqli_query($conn, "SHOW TABLES LIKE 'password_resets'");
if (mysqli_num_rows($check_table) == 0) {
    echo "Creating 'password_resets' table... ";
    $sql_reset = "CREATE TABLE password_resets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(100) NOT NULL,
        token VARCHAR(6) NOT NULL,
        expires_at DATETIME NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    if (mysqli_query($conn, $sql_reset)) {
        echo "<span style='color:green;'>SUCCESS</span><br>";
    } else {
        echo "<span style='color:red;'>FAILED: " . mysqli_error($conn) . "</span><br>";
    }
} else {
    echo "'password_resets' table already exists. <span style='color:blue;'>SKIPPED</span><br>";
}

echo "<h3>Migration Completed!</h3>";
echo "<a href='../login.php'>Go to Login</a>";
?>
