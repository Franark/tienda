<?php
require_once('../model/conexion.php');
require_once('../model/rolPermiso.php');

$rolPermiso = new RolPermiso();
$idRolUsuario = isset($_GET['idRolUsuario']) ? intval($_GET['idRolUsuario']) : 0;

$permisos = $rolPermiso->obtenerPermisosPorRol($idRolUsuario);

$permisosIds = array_column($permisos, 'permiso_idPermiso');

echo json_encode($permisosIds);
?>
