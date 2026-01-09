<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/PHPMailer/src/Exception.php';
require __DIR__ . '/../vendor/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../vendor/PHPMailer/src/SMTP.php';

function sendOTP($to, $subject, $message) {
    $mail = new PHPMailer(true);

    try {
        //Konfigurasi server SMTP
        $mail->SMTPDebug  = 0; 
        $mail->isSMTP();
        $mail->Host       = 'mail.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'user@gmail.com';
        $mail->Password   = 'password_email_anda';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587; 

        $mail->setFrom('user@gmail.com', 'Sistem Barang Hilang');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
