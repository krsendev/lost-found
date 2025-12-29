<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/PHPMailer/src/Exception.php';
require __DIR__ . '/../vendor/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../vendor/PHPMailer/src/SMTP.php';

function sendOTP($to, $subject, $message) {
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug  = 2; // Enable verbose debug output
        $mail->Debugoutput = 'html';
        $mail->isSMTP();
        $mail->Host       = 'mail.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'user@gmail.com';
        $mail->Password   = 'password_email_anda'; // Pastikan ini sudah diisi benar!
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Coba STARTTLS dulu
        $mail->Port       = 587; // Port 587 biasanya lebih aman dari blokir ISP

        //Recipients
        $mail->setFrom('user@gmail.com', 'Sistem Barang Hilang');
        $mail->addAddress($to);

        //Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return false;
    }
}
