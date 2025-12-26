<?php
include '../config/database.php';
include '../config/mail.php';

$email = $_POST['email'];
$q = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");
$user = mysqli_fetch_assoc($q);

if(!$user) die("Email tidak terdaftar");

$otp = rand(100000,999999);
$expired = date("Y-m-d H:i:s",strtotime("+5 minutes"));

mysqli_query($conn,"UPDATE users SET otp='$otp', otp_expired='$expired', otp_attempt=0 WHERE email='$email'");
sendMail($email,$otp);

header("Location: ../view/otp.php?email=$email");