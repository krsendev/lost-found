<?php
include '../config/database.php';

$email = $_POST['email'];
$otp   = $_POST['otp'];

$user = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM users WHERE email='$email'"));

if($user['otp_attempt'] >= 3) die("OTP diblokir");

$check = mysqli_query($conn,"
    SELECT * FROM users 
    WHERE email='$email' 
    AND otp='$otp' 
    AND otp_expired >= NOW()
");

if(mysqli_num_rows($check)){
    header("Location: ../view/reset.php?email=$email");
} else {
    mysqli_query($conn,"UPDATE users SET otp_attempt=otp_attempt+1 WHERE email='$email'");
    echo "OTP salah";
}