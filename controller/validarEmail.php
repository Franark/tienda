<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

require_once('../model/usuario.php');

$email = $_POST['email'];
$usuario = new Usuario();
$usuarioInfo = $usuario->obtenerUsuarioPorEmail($email);

if ($usuarioInfo) {
    echo 'ok';
} else {
    echo 'not_found';
}
?>
