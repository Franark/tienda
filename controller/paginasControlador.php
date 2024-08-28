<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

require_once('../model/conexion.php');
require_once('../model/rolPermiso.php');

$rolPermiso = new RolPermiso();
$idRolUsuario = $_POST['idRolUsuario'];
$permisos = $_POST['permisos'];

if (isset($idRolUsuario) && !empty($permisos)) {
    $rolPermiso->eliminarRolPermiso($idRolUsuario);

    foreach ($permisos as $permiso) {
        $rolPermiso->agregarRolPermiso($idRolUsuario, $permiso);
    }

    header('Location: gestionPaginas');
} else {
    echo "Error: No se recibieron los datos necesarios.";
}

?>