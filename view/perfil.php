<?php
require_once('model/persona.php');
require_once('model/tipoContacto.php');
require_once('model/atributoDomicilio.php');
require_once('model/tipoSexo.php');
require_once('model/tipoDocumento.php');

$idUsuario = $_SESSION['idUsuario'];

$persona = new Persona();
$datosPersona = $persona->listarPersona($idUsuario);

$idPersona = $datosPersona[0]['idPersona'];
$nombreUsuario = $datosPersona[0]['nombrePersona'];
$apellidoUsuario = $datosPersona[0]['apellidoPersona'];
$edadUsuario = $datosPersona[0]['edadPersona'];
$tipoSexo = $datosPersona[0]['tipoSexo_idTipoSexo'];
$personaDocumento = $datosPersona[0]['valorDocumento'];
$idTipoDocumento = $datosPersona[0]['idTipoDocumento'];
$contactos = isset($datosPersona[0]['contactos']) ? $datosPersona[0]['contactos'] : [];
$direcciones = isset($datosPersona[0]['direcciones']) ? $datosPersona[0]['direcciones'] : [];
?>
<header>
    <h1>Perfil</h1>
</header>
<main>
    <form action="controller/perfilControlador.php" method="post">
        <input type="hidden" name="idPersona" value="<?php echo htmlspecialchars($idPersona); ?>">

        <label for="nombrePersona">Nombre:</label>
        <input type="text" id="nombrePersona" name="nombrePersona" value="<?php echo htmlspecialchars($nombreUsuario); ?>" required>
        <br><br>

        <label for="apellidoPersona">Apellido:</label>
        <input type="text" id="apellidoPersona" name="apellidoPersona" value="<?php echo htmlspecialchars($apellidoUsuario); ?>" required>
        <br><br>

        <label for="edadPersona">Edad:</label>
        <input type="number" id="edadPersona" name="edadPersona" value="<?php echo htmlspecialchars($edadUsuario); ?>" min="18" required>
        <br><br>

        <label for="tipoSexo">Sexo:</label>
        <select name="tipoSexo" id="tipoSexo" required>
            <?php
            $tipoSexoObj = new TipoSexo();
            $tipoSexos = $tipoSexoObj->listarTipoSexo();
            foreach ($tipoSexos as $tp) {
                $selected = ($tp['idTipoSexo'] == $tipoSexo) ? 'selected' : '';
                echo "<option value='{$tp['idTipoSexo']}' {$selected}>{$tp['nombreTipoSexo']}</option>";
            }
            ?>
        </select>
        <br><br>

        <label for="idTipoDocumento">Tipo de Documento:</label>
        <select name="idTipoDocumento" id="idTipoDocumento" required>
            <?php
            $tipoDocumentoObj = new TipoDocumento();
            $tipoDocumentos = $tipoDocumentoObj->listarTiposDocumento(); 
            foreach ($tipoDocumentos as $td) {
                $selected = ($td['idTipoDocumento'] == $idTipoDocumento) ? "selected" : "";
                echo "<option value='{$td['idTipoDocumento']}' {$selected}>{$td['nombreTipoDocumento']}</option>";
            }
            ?>
        </select>
        <br><br>

        <label for="valorDocumento">Número de Documento:</label>
        <input type="number" id="valorDocumento" name="valorDocumento" value="<?php echo htmlspecialchars($personaDocumento); ?>" required>
        <br><br>

        <h3>Contactos</h3>
        <div id="contactos">
            <?php
            foreach ($contactos as $index => $contacto) {
                echo '<div class="contacto">';
                echo '<label for="tipoContacto' . $index . '">Tipo de Contacto:</label>';
                echo '<select id="tipoContacto' . $index . '" name="contactos[' . $index . '][tipoContacto_idTipoContacto]" required>';
                
                $tipoContactoObj = new TipoContacto();
                $tiposContacto = $tipoContactoObj->listarTipoContacto();
                
                foreach ($tiposContacto as $tc) {
                    $selected = ($tc['idTipoContacto'] == $contacto['tipoContacto_idTipoContacto']) ? "selected" : "";
                    echo "<option value='{$tc['idTipoContacto']}' {$selected}>{$tc['nombreTipoContacto']}</option>";
                }
                
                echo '</select>';
                echo '<label for="contactoValor' . $index . '">Contacto:</label>';
                echo '<input type="text" id="contactoValor' . $index . '" name="contactos[' . $index . '][valor]" value="' . htmlspecialchars($contacto['valor']) . '">';
                echo '<button type="button" class="eliminarContacto" data-index="' . $index . '">Eliminar</button>';
                echo '<br><br>';
                echo '</div>';
            }
            ?>
        </div>
        <button type="button" id="agregarContacto">Agregar Contacto</button>
        <br><br>
        <h3>Domicilio</h3>
        <div id="direccionesUsuario">
            <?php
            if (!empty($direcciones)) {
                foreach ($direcciones as $index => $direccion) {
                    echo '<div class="domicilio">';
                    echo '<input type="hidden" name="direcciones[' . $index . '][idDomicilio]" value="' . htmlspecialchars($direccion['idDomicilio']) . '">';
                    echo '<label for="barrio' . $index . '">Barrio:</label>';
                    echo '<input type="text" id="barrio' . $index . '" name="direcciones[' . $index . '][barrio]" value="' . htmlspecialchars($direccion['barrio']) . '" required>';
                    echo '<br>';

                    echo '<label for="numeroCasa' . $index . '">Número de Casa:</label>';
                    echo '<input type="number" id="numeroCasa' . $index . '" name="direcciones[' . $index . '][numeroCasa]" value="' . htmlspecialchars($direccion['numeroCasa']) . '" required>';
                    echo '<br>';

                    echo '<label for="piso' . $index . '">Piso:</label>';
                    echo '<input type="number" id="piso' . $index . '" name="direcciones[' . $index . '][piso]" value="' . htmlspecialchars($direccion['piso']) . '">';
                    echo '<br>';

                    echo '<label for="descripcion' . $index . '">Descripción:</label>';
                    echo '<textarea id="descripcion' . $index . '" name="direcciones[' . $index . '][descripcion]">' . htmlspecialchars($direccion['descripcion']) . '</textarea>';
                    echo '<br>';

                    echo '<button type="button" class="eliminarDomicilio" data-index="' . $index . ' ">Eliminar</button>';
                    echo '</div>';

                    
                }
            }
            ?>
        </div>


        <button type="button" id="agregarDomicilio">Agregar Dirección</button>

        <br><br>


        <input type="submit" name="actualizar" value="Actualizar">
    </form>
</main>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAozDM3DCVNPv0mTQ1vgKOLq8pBcNdwSoQ&libraries=places&callback=initAutocomplete" async defer></script>
<script>
    let autocomplete;

    const formosaBounds = {
        north: -24.522,
        south: -26.921,
        east: -58.140,
        west: -61.904
    };

    document.addEventListener("DOMContentLoaded", function () {
        const addressInputs = document.querySelectorAll("[id^='barrio']");
        addressInputs.forEach(initializeAutocomplete);

        document.getElementById('agregarDomicilio').addEventListener('click', function () {
            const direccionesDiv = document.getElementById('direccionesUsuario');
            const index = direccionesDiv.children.length;

            const newDomicilioDiv = document.createElement('div');
            newDomicilioDiv.classList.add('domicilio');
            newDomicilioDiv.innerHTML = `
                <label for="barrio${index}">Barrio:</label>
                <input type="text" id="barrio${index}" name="direcciones[${index}][barrio]" required placeholder="Introduce tu barrio">
                <br>

                <label for="numeroCasa${index}">Número de Casa:</label>
                <input type="number" id="numeroCasa${index}" name="direcciones[${index}][numeroCasa]" required placeholder="Ejemplo: 123">
                <br>

                <label for="piso${index}">Piso:</label>
                <input type="number" id="piso${index}" name="direcciones[${index}][piso]" placeholder="Ejemplo: 2A">
                <br>

                <label for="descripcion${index}">Descripción:</label>
                <textarea id="descripcion${index}" name="direcciones[${index}][descripcion]" placeholder="Añade una descripción (opcional)"></textarea>
                <br>

                <button type="button" class="eliminarDomicilio" data-index="' . $index . '">Eliminar</button>
                <br><br>
            `;
            direccionesDiv.appendChild(newDomicilioDiv);

            const newAddressInput = document.getElementById(`barrio${index}`);
            initializeAutocomplete(newAddressInput);
        });

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('eliminarDomicilio')) {
                e.target.closest('.domicilio').remove();
            }
        });
    });

    function initializeAutocomplete(addressInput) {
        const autocomplete = new google.maps.places.Autocomplete(addressInput, {
            bounds: new google.maps.LatLngBounds(
                new google.maps.LatLng(formosaBounds.south, formosaBounds.west),
                new google.maps.LatLng(formosaBounds.north, formosaBounds.east)
            ),
            types: ["geocode"],
            strictBounds: true
        });

        autocomplete.addListener("place_changed", function () {
            const place = autocomplete.getPlace();
            const isFormosaCapital = place.address_components.some(component =>
                component.types.includes("administrative_area_level_2") &&
                component.long_name === "Formosa"
            );
            if (!isFormosaCapital) {
                Swal.fire({
                    icon: 'error',
                    title: 'Dirección no válida',
                    text: 'Por favor, selecciona una dirección dentro de Formosa Capital.',
                    confirmButtonText: 'Entendido'
                }).then(() => {
                    addressInput.value = "";
                });
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const botonesEliminar = document.querySelectorAll('.eliminarDomicilio');

        botonesEliminar.forEach(boton => {
            boton.addEventListener('click', function () {
                const index = this.dataset.index.trim();
                const idDomicilio = document.querySelector(`input[name="direcciones[${index}][idDomicilio]"]`).value;

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'Esta acción no se puede deshacer.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('controller/perfilControlador.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                action: 'eliminar',
                                idDomicilio: idDomicilio
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Eliminado',
                                    text: 'La dirección ha sido eliminada correctamente.',
                                    icon: 'success',
                                    confirmButtonText: 'Aceptar'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Hubo un problema al eliminar la dirección.',
                                    icon: 'error',
                                    confirmButtonText: 'Aceptar'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Error',
                                text: 'Ocurrió un error al intentar eliminar la dirección.',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        });
                    }
                });
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const contactosDiv = document.getElementById('contactos');
        const agregarContactoBtn = document.getElementById('agregarContacto');

        agregarContactoBtn.addEventListener('click', function () {
            const index = contactosDiv.children.length;
            const nuevoContacto = document.createElement('div');
            nuevoContacto.classList.add('contacto');

            nuevoContacto.innerHTML = `
                <label for="tipoContacto${index}">Tipo de Contacto:</label>
                <select id="tipoContacto${index}" name="contactos[${index}][tipoContacto_idTipoContacto]" required>
                    <!-- Aquí debes cargar los tipos de contacto desde el servidor -->
                    <?php
                    $tipoContactoObj = new TipoContacto();
                    $tiposContacto = $tipoContactoObj->listarTipoContacto();
                    foreach ($tiposContacto as $tc) {
                        echo "<option value='{$tc['idTipoContacto']}'>{$tc['nombreTipoContacto']}</option>";
                    }
                    ?>
                </select>
                <label for="contactoValor${index}">Contacto:</label>
                <input type="text" id="contactoValor${index}" name="contactos[${index}][valor]" placeholder="Ejemplo: +123456789" required>
                <button type="button" class="eliminarContacto" data-index="${index}">Eliminar</button>
                <br><br>
            `;

            contactosDiv.appendChild(nuevoContacto);

            nuevoContacto.querySelector('.eliminarContacto').addEventListener('click', function () {
                nuevoContacto.remove();
            });
        });
    });
</script>
