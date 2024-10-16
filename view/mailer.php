<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Una vez declarados los namespaces simplemente tenemos que instanciar las clases por su nombre
$mail = new PHPMailer(true);

?>