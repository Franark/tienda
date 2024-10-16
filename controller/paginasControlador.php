<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../model/conexion.php');
require_once('../model/rolPermiso.php');

$rolPermiso = new RolPermiso();

$idRolUsuario = isset($_POST['idRolUsuario']) ? $_POST['idRolUsuario'] : null;
$permisos = isset($_POST['permisos']) ? $_POST['permisos'] : [];

if ($idRolUsuario !== null) {
    if ($rolPermiso->eliminarRolPermiso($idRolUsuario)) {
        if (!empty($permisos)) {
            foreach ($permisos as $permiso) {
                if (!empty($permiso)) {
                    $rolPermiso->agregarRolPermiso($idRolUsuario, $permiso);
                }
            }
        }
    } else {
        echo "Error al eliminar permisos.";
    }
    header("Location: ../?page=gestionPaginas&idRolUsuario=$idRolUsuario");
    exit();
} else {
    echo "Error: No se recibieron los datos necesarios.";
}


?>
