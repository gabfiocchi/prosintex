<?php

//Import the PHPMailer class into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require getcwd() . '/PHPMailer/src/Exception.php';
require getcwd() . '/PHPMailer/src/PHPMailer.php';
require getcwd() . '/PHPMailer/src/SMTP.php';


$err = false;
$msg = '';
$message = '';
$email = '';
$name = '';
$status = null;

if (array_key_exists('message', $_POST)) {
    $message = substr(strip_tags($_POST['message']), 0, 16384);
} else {
    $message = '';
    $msg = 'No message provided!';
    $status = 400;
    $err = true;
}

if (array_key_exists('name', $_POST)) {
    $name = substr(strip_tags($_POST['name']), 0, 255);
} else {
    $name = '';
}

if (array_key_exists('email', $_POST) and PHPMailer::validateAddress($_POST['email'])) {
    $email = $_POST['email'];
} else {
    $msg = "Error: invalid email address provided";
    $status = 400;
    $err = true;
}
//$err = false;
if (!$err) {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    //    $mail->SMTPAuth = false;
    $mail->Host = 'localhost';
    //    $mail->Username = "info@igworkshop.com";
    //    $mail->Password = "6KUWf9XTLvEPJSL";
    // $mail->Username = "server@igworkshop.com";
    // $mail->Password = "iRlVO)X+oplM";
    // $mail->Port = 25;
    $mail->isSendmail();
    $mail->CharSet = 'utf-8';
    $mail->setFrom('info@prosintex.com.ar', 'Contacto Web');
    $mail->addAddress('info@prosintex.com.ar');
    $mail->addReplyTo($email, $name);
    $mail->Subject = $_POST['subject'];
    $mail->Body = "Nueva consulta de https://prosintex.com.ar\n\n" . $message;
    if (!$mail->send()) {
        $msg .= "Mailer Error: " . $mail->ErrorInfo;
        $status = 400;
    } else {
        $msg .= "¡Mensaje enviado! Nos pondremos en contacto con usted pronto..";
        $status = 200;
    }
}

header('Content-Type: application/json');

$myObject = [];
$myObject['message'] = $msg;
$myObject['status'] = $status;


echo (json_encode($myObject));
