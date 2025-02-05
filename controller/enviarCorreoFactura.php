<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';


function enviarCorreoFactura($correoCliente, $asunto, $mensaje) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'oscarfranciscooscar1@gmail.com';
        $mail->Password   = 'fhkl gysh pkvf wahe'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $mail->Port = 587; 

        $mail->setFrom('oscarfranciscooscar1@gmail.com', 'Mailer');
        $mail->addAddress($correoCliente);

        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body    = $mensaje;

        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Error al enviar el correo: {$mail->ErrorInfo}";
        return false;
    }
}
