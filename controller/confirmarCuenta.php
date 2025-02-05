<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

require_once ('../model/usuario.php');

$usuario = new Usuario();

if (isset($_GET['email']) && isset($_GET['token'])) {
    $email = $_GET['email'];
    $token = $_GET['token'];

    $usuarioData = $usuario->obtenerUsuarioPorEmail($email);

    if ($usuarioData && $usuarioData['token_confirmacion'] === $token) {
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
