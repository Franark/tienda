<?php
//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/Exception.php';
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';

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

    // Recipientes
    $mail->setFrom('oscarfranciscooscar1@gmail.com', 'Mailer');
    
    // Asegúrate de que $userEmail esté definida y la uses correctamente
    if (isset($userEmail) && filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
        $mail->addAddress($userEmail);  // Usar la dirección de correo del usuario
    } else {
        echo "No se pudo enviar el correo. Dirección de correo inválida.";
        exit;
    }

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = 'Confirma tu cuenta';

    // Generar el enlace de confirmación
    $confirmationLink = "https://localhost/tienda/controller/confirmarCuenta.php?email=$userEmail&token=$token";

    $mail->Body = "
        <h1>Gracias por registrarte</h1>
        <p>Para confirmar tu cuenta, haz clic en el siguiente enlace:</p>
        <a href='$confirmationLink'>Confirmar mi cuenta</a>
    ";
    $mail->AltBody = "Gracias por registrarte. Por favor, confirma tu cuenta utilizando el siguiente enlace: $confirmationLink";

    $mail->send();
    echo 'Correo de confirmación enviado';
} catch (Exception $e) {
    echo "No se pudo enviar el correo. Error: {$mail->ErrorInfo}";
}
