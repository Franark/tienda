<?php
ini_set('display_errors', 1);
require_once('../model/usuario.php');
require_once('../model/rolUsuario.php');

session_start();

if (isset($_POST['submit'])) {
    $nickname = $_POST['nickname'];
    $password = $_POST['password'];

    if (empty($nickname) || empty($password)) {
        echo '<p>Nombre de usuario o contraseña vacío</p>';
    } else {
        $usuario = new Usuario();
        
        if ($usuario->login($nickname, $password)) {
            echo '<p>Login exitoso</p>'; 
            
            $usuarioData = $usuario->obtenerUsuarioPorId($usuario->getId());
            if ($usuarioData) {
                $_SESSION['idUsuario'] = $usuarioData['idUsuario'];
                $_SESSION['nickname'] = $nickname;

                echo "idUsuario en la sesión: " . $_SESSION['idUsuario'];

                if ($nickname === $password) {
                    header('Location: ../?page=cambiarPassword');
                    exit();
                }

                $rolUsuario = new RolUsuario();
                $rolUsuarioId = $usuario->getRolUsuarioId($nickname);
                $nombreRol = $rolUsuario->listarRoles($rolUsuarioId);
                if ($nombreRol) {
                    $_SESSION['nombreRol'] = $nombreRol;
                    $_SESSION['idRolUsuario'] = $rolUsuarioId;
                    header('Location: ../?page=gestionUsuarios');
                    exit();
                } else {
                    echo "<p>Error al obtener el rol del usuario</p>";
                }
            } else {
                echo "<p>Error al obtener los datos del usuario</p>";
            }
        } else {
            echo "<p>Usuario no existe o contraseña incorrecta</p>";
        }
    }
}
?>
