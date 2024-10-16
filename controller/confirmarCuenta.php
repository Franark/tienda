<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

require_once ('../model/usuario.php'); // Asegúrate de que la ruta sea correcta

$usuario = new Usuario(); // Crear instancia de Usuario

if (isset($_GET['email']) && isset($_GET['token'])) {
    $email = $_GET['email'];
    $token = $_GET['token'];

    // Verificar si el usuario existe y el token coincide
    $usuarioData = $usuario->obtenerUsuarioPorEmail($email);

    if ($usuarioData && $usuarioData['token_confirmacion'] === $token) {
        // Actualiza la cuenta como confirmada
        if ($usuario->confirmarCuenta($email, $token)) {
            header('Location: ../?page=login&success=Cuenta confirmada con éxito.');
            exit();
        } else {
            echo "Error al confirmar la cuenta.";
        }
    } else {
        echo "Token inválido o usuario no encontrado.";
    }
} else {
    echo "Faltan parámetros.";
}

?>
