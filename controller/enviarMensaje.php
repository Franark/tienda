<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'oscarfranciscooscar1@gmail.com';
    $mail->Password   = 'fhkl gysh pkvf wahe'; 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    $mail->setFrom('oscarfranciscooscar1@gmail.com', 'Mailer');

    if (isset($userEmail) && filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
        $mail->addAddress($userEmail);
    } else {
        echo "No se pudo enviar el correo. Dirección de correo inválida.";
        exit;
    }

    $mail->isHTML(true);
    $mail->Subject = 'Confirma tu cuenta';

    $confirmationLink = "https://localhost/tienda/controller/confirmarCuenta.php?email=$userEmail&token=$token";

    $mail->Body = "
        <h1>Gracias por registrarte</h1>
        <p>Para confirmar tu cuenta, haz clic en el siguiente enlace:</p>
        <a href='$confirmationLink'>Confirmar mi cuenta</a>
    ";
    $mail->AltBody = "Gracias por registrarte. Por favor, confirma tu cuenta utilizando el siguiente enlace: $confirmationLink";

    $mail->send();
    header('Location: ../?page=signup&success=Correo de confirmación enviado. Por favor revisa tu email.');
    exit();
} catch (Exception $e) {
    echo "No se pudo enviar el correo. Error: {$mail->ErrorInfo}";
}
