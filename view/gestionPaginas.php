<?php
require_once('model/rolUsuario.php');
require_once('model/rolPermiso.php');
require_once('model/permiso.php');

$rolUsuario = new RolUsuario();
$rolesUsuario = $rolUsuario->listarRoles();
$permiso = new Permiso();
$permisos = $permiso->listarPermisos();

$idRolUsuario = isset($_POST['idRolUsuario']) ? intval($_POST['idRolUsuario']) : (isset($_GET['idRolUsuario']) ? intval($_GET['idRolUsuario']) : 0);
$rolPermiso = new RolPermiso();
$permisosAsignados = $idRolUsuario > 0 ? $rolPermiso->obtenerPermisosPorRol($idRolUsuario) : [];

$permisosAsignadosIds = array_column($permisosAsignados, 'permiso_idPermiso');
?>

<h1>Gestión de Permisos</h1>

<form id="permisosForm" method="post" action="controller/paginasControlador.php">
    <label for="idRolUsuario">Rol de Usuario:</label>
    <select name="idRolUsuario" id="idRolUsuario">
        <?php foreach ($rolesUsuario as $rol): ?>
            <option value="<?php echo $rol['idRolUsuario']; ?>" <?php echo ($rol['idRolUsuario'] == $idRolUsuario) ? 'selected' : ''; ?>>
                <?php echo $rol['nombreRol']; ?>
            </option>
        <?php endforeach; ?>
    </select>
    <br><br>
    
    <label for="permisos">Seleccionar Páginas:</label><br>
    <?php foreach ($permisos as $p): ?>
        <?php
        $checked = in_array($p['idPermiso'], $permisosAsignadosIds) ? 'checked' : '';
        ?>
        <input type="checkbox" name="permisos[]" value="<?php echo $p['idPermiso']; ?>" <?php echo $checked; ?>> <?php echo $p['nombrePermiso']; ?><br>
    <?php endforeach; ?>
    <br>
    <button type="submit">Guardar</button>
</form>

<script>
function cargarPermisos(idRolUsuario) {
    var checkboxes = document.querySelectorAll('input[name="permisos[]"]');
    checkboxes.forEach(function(checkbox) {
        checkbox.checked = false;
    });

    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'controller/obtenerPermisosPorRol.php?idRolUsuario=' + idRolUsuario, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            var permisos = JSON.parse(xhr.responseText);
            permisos.forEach(function(idPermiso) {
                var checkbox = document.querySelector('input[name="permisos[]"][value="' + idPermiso + '"]');
                if (checkbox) {
                    checkbox.checked = true;
                }
            });
        }
    };
    xhr.send();
}

document.getElementById('idRolUsuario').addEventListener('change', function() {
    cargarPermisos(this.value);
});

window.addEventListener('DOMContentLoaded', function() {
    var idRolUsuario = document.getElementById('idRolUsuario').value;
    if (idRolUsuario) {
        cargarPermisos(idRolUsuario);
    }
});

</script>