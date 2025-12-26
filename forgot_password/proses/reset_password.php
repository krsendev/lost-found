<?php
include '../config/database.php';

$email = $_POST['email'];
$pass  = password_hash($_POST['password'],PASSWORD_DEFAULT);

mysqli_query($conn,"
    UPDATE users SET 
    password='$pass', otp=NULL, otp_expired=NULL, otp_attempt=0 
    WHERE email='$email'
");

echo "Password berhasil diubah";