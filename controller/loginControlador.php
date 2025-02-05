<?php
ini_set('display_errors', 1);
require_once('../model/usuario.php');
require_once('../model/rolUsuario.php');

session_start();

if (isset($_POST['submit'])) {
    $nickname = $_POST['nickname'];
    $password = $_POST['password'];

    if (empty($nickname) || empty($password)) {
        header('Location: ../?page=login&error=Nombre de usuario o contraseña vacío');
        exit();
    } else {
        $usuario = new Usuario();
        
        if ($usuario->login($nickname, $password)) {
            $usuarioData = $usuario->obtenerUsuarioPorId($usuario->getId());
            if ($usuarioData) {
                if ($usuarioData['confirmacion'] == 0) {
                    header('Location: ../?page=login&error=Por favor confirma tu cuenta antes de iniciar sesión.');
                    exit();
                }

                $_SESSION['idUsuario'] = $usuarioData['idUsuario'];
                $_SESSION['nickname'] = $nickname;
                $_SESSION['cliente_id'] = $usuarioData['idCliente'];
                $_SESSION['email'] = $usuarioData['email'];

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

                    if ($nombreRol == 'Administrador') {
                        header('Location: ../?page=gestionUsuarios');
                    } elseif ($nombreRol == 'Cliente') {
                        header('Location: ../?page=catalogoProductos');
                    } elseif ($nombreRol == 'Empleado') {
                        header('Location: ../?page=gestionInventario');
                    } elseif ($nombreRol == 'Repartidor') {
                        header('Location: ../?page=seguimientoPedidos');
                    } else {
                        header('Location: ../?page=login&error=Rol desconocido');
                    }
                    exit();      
                } else {
                    header('Location: ../?page=login&error=Error al obtener el rol del usuario');
                    exit();
                }
            } else {
                header('Location: ../?page=login&error=Error al obtener los datos del usuario');
                exit();
            }
        } else {
            header('Location: ../?page=login&error=credenciales incorrectas');
            exit();
        }
    }
}
?>
