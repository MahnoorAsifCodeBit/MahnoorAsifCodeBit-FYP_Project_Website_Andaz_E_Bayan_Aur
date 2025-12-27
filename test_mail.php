<?php
require 'mailer_config.php'; // Include this to load the sendMail function

$result = sendMail('mominasheikh851@gmail.com', 'Testing from Brevo SMTP', 'Hello! This is a test email.');

if ($result === true) {
    echo "Mail sent successfully!";
} else {
    echo "Mail failed to send. Error: " . $result;
}
?>
