<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);
session_start();
require_once ('../model/usuario.php');

if (isset($_GET['token']) && isset($_GET['email'])) {
    $token = $_GET['token'];
    $email = $_GET['email'];

    $usuario = new Usuario();
    $usuarioEncontrado = $usuario->verificarToken($token, $email);

    if ($usuarioEncontrado) {
        header("Location: ../?page=reestablecerContraseña&token=$token&email=$email");
        exit();
    } else {
        echo "El token es inválido o ha expirado.";
    }
} else {
    echo "No se recibieron los parámetros necesarios.";
}
?>
