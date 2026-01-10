<?php
// include 'config/smtpMail.php';

$to = "ganeesha.chiranthani@gmail.com";
$subject = "Job Offer";
$message = "
<h2>Congratulations!</h2>
<p>You have been offered the position.</p>
";

$headers = "From: no-reply@careerbridge.click\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

if (mail($to, $subject, $message, $headers)) {
    echo "Email sent successfully!";
} else {
    echo "Email failed!";
}

// $result = sendSMTPMail($to, $subject, $message);

// if ($result === true) {
//     echo "Email sent successfully!";
// } else {
//     echo "Error: $result";
// }