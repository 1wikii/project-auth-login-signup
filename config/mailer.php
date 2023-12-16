<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require_once ("./vendor/autoload.php");

$mail = new PHPMailer(true);   // enable Authentication

// $mail->SMTPDebug = SMTP::DEBUG_SERVER;      // uncomment this if have an issue with sending email

$mail->isSMTP();
$mail->SMTPAuth = true;

$mail->Host = "smtp.gmail.com";
$mail->Username   = 'sssttt9111@gmail.com';
$mail->Password   = 'uqtypdyvjpowrqmo';
$mail->SMTPSecure = PHPMailer:: ENCRYPTION_STARTTLS;   // PHPMailer:: ENCRYPTION_STARTTLS using port 587
$mail->Port = 587;

$mail->isHTML(true);    // enable HTML content

return $mail;
?>