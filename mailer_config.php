<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

function sendMail($to, $subject, $body) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp-relay.brevo.com'; // Brevo SMTP host
        $mail->SMTPAuth = true;
        $mail->Username = '88820b002@smtp-brevo.com'; // Your Brevo SMTP username
        $mail->Password = 'x7B5IOkKE2gaCUGz'; // Your generated Brevo SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender & recipient
        $mail->setFrom('88820b001@smtp-brevo.com', 'Andaz-e-Bayan Aur');
        $mail->addAddress($to); // Dynamic address here!

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
        return true;
    } 
    catch (Exception $e) {
    return "Mailer Error: {$mail->ErrorInfo}";
}
}
?>
