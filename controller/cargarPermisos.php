<?php
require_once('../model/permiso.php');
require_once('../model/rolPermiso.php');

if (isset($_POST['idRolUsuario'])) {
    $idRolUsuario = $_POST['idRolUsuario'];

    $permiso = new Permiso();
    $rolPermiso = new RolPermiso();
    $permisos = $permiso->listarPermisos();
    $permisosMarcados = $rolPermiso->obtenerPermisosPorRol($idRolUsuario);

    foreach ($permisos as $permiso) {
        $checked = in_array($permiso['idPermiso'], $permisosMarcados) ? 'checked' : '';
        echo "<input type='checkbox' name='permisos[]' value='{$permiso['idPermiso']}' $checked> {$permiso['nombrePermiso']}<br>";
    }
}
?>