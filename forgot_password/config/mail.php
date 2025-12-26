<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

function sendMail($email, $otp){
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'email@gmail.com';
    $mail->Password   = 'APP_PASSWORD_GMAIL';
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('email@gmail.com', 'Reset Password');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Kode OTP Reset Password';
    $mail->Body    = "<h3>Kode OTP Anda</h3><b>$otp</b><br>Berlaku 5 menit";

    $mail->send();
}