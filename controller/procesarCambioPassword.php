<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require_once '../model/usuario.php';

if (isset($_POST['nuevaContraseña']) && isset($_POST['confirmarContraseña']) && isset($_POST['email']) && isset($_POST['token'])) {
    $nuevaContraseña = trim($_POST['nuevaContraseña']);
    $confirmarContraseña = trim($_POST['confirmarContraseña']);
    $email = $_POST['email'];
    $token = $_POST['token'];

    if (empty($nuevaContraseña) || empty($confirmarContraseña)) {
        header('Location: ../?page=reestablecerContraseña&error=Las contraseñas no pueden estar vacías.');
        exit();
    }

    if ($nuevaContraseña === $confirmarContraseña) {
        $usuario = new Usuario();
        
        $contraseñaActualizada = $usuario->restablecerContraseña($email, $nuevaContraseña);

        if ($contraseñaActualizada) {
            header('Location: ../?page=login&success=Se ha cambiado la contraseña con éxito.');
            exit();
        } else {
            header('Location: ../?page=reestablecerContraseña&error=Ocurrió un error al actualizar la contraseña.');
            exit();
        }
    } else {
        header('Location: ../?page=reestablecerContraseña&error=Las contraseñas no coinciden.');
        exit();
    }
} else {
    header('Location: ../?page=reestablecerContraseña&error=Faltan campos requeridos.');
    exit();
}
?>
