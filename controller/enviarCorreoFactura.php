<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/Exception.php';
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';

function enviarCorreoFactura($correoCliente, $asunto, $mensaje) {
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'oscarfranciscooscar1@gmail.com';
        $mail->Password   = 'fhkl gysh pkvf wahe'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // Remitente y destinatario
        $mail->setFrom('oscarfranciscooscar1@gmail.com', 'Mailer');
        $mail->addAddress($correoCliente);

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body    = $mensaje;

        // Enviar correo
        $mail->send();
        return true; // Cambiado para devolver verdadero si se envía correctamente
    } catch (Exception $e) {
        echo "Error al enviar el correo: {$mail->ErrorInfo}";
        return false; // Devuelve falso si hay un error
    }
}
