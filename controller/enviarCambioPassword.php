<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

function enviarCorreoCambioPassword($userEmail, $resetLink) {
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
        $mail->addAddress($userEmail);

        $mail->isHTML(true);
        $mail->Subject = 'Recupera tu contraseña';
        $mail->Body    = "Haz clic en el siguiente enlace para restablecer tu contraseña: <a href='$resetLink'>$resetLink</a>";

        $mail->send();
        echo 'Correo enviado con éxito';
    } catch (Exception $e) {
        echo "No se pudo enviar el correo. Error: {$mail->ErrorInfo}";
    }
}
