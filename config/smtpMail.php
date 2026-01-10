<?php

// SMTP Configuration
function smtpConfig() {
    return [
        'host' => 'smtp.hostinger.com',
        'port' => 465, // TLS port
        'username' => 'no-reply@careerbridge.click', // SMTP username
        'password' => 'ffff', // SMTP password / App password
        'from_email' => 'no-reply@careerbridge.click',
        'from_name' => 'careerBridge'
    ];
}

/**
 * Send email via SMTP
 */
function sendSMTPMail($to, $subject, $message, $isHTML = true) {
    $config = smtpConfig();

    $eol = "\r\n";
    $socket = fsockopen($config['host'], $config['port'], $errno, $errstr, 30);
    if (!$socket) {
        return "Connection failed: $errstr ($errno)";
    }

    function send($socket, $cmd, $eol) {
        fwrite($socket, $cmd . $eol);
        return fgets($socket, 512);
    }

    // SMTP handshake
    send($socket, "EHLO localhost", $eol);
    send($socket, "AUTH LOGIN", $eol);
    send($socket, base64_encode($config['username']), $eol);
    send($socket, base64_encode($config['password']), $eol);

    send($socket, "MAIL FROM:<{$config['from_email']}>", $eol);
    send($socket, "RCPT TO:<$to>", $eol);
    send($socket, "DATA", $eol);

    // Headers
    $headers = "From: {$config['from_name']} <{$config['from_email']}>" . $eol;
    $headers .= "To: $to" . $eol;
    $headers .= "Subject: $subject" . $eol;
    if ($isHTML) {
        $headers .= "MIME-Version: 1.0" . $eol;
        $headers .= "Content-type: text/html; charset=UTF-8" . $eol;
    }

    // Send message + end with "."
    send($socket, $headers . $eol . $message . $eol . ".", $eol);
    send($socket, "QUIT", $eol);

    fclose($socket);
    return true;
}
