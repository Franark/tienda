<header>
    <h1>Perfil</h1>
</header>
<main>
    <?php
    require_once('model/persona.php');
    require_once('model/tipoContacto.php');
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
    $contactos = $datosPersona[0]['contactos'];
    ?>
    <form action="controller/perfilControlador.php" method="post">
        <input type="hidden" name="idPersona" value="<?php echo $idPersona; ?>">
        <label for="nombrePersona">Nombre:</label>
        <input type="text" id="nombrePersona" name="nombrePersona" value="<?php echo $nombreUsuario; ?>" required>
        <br><br>
        <label for="apellidoPersona">Apellido:</label>
        <input type="text" id="apellidoPersona" name="apellidoPersona" value="<?php echo $apellidoUsuario; ?>" required>
        <br><br>
        <label for="edadPersona">Edad:</label>
        <input type="number" id="edadPersona" name="edadPersona" value="<?php echo $edadUsuario; ?>" min="18" required>
        <br><br>
        <label for="tipoSexo">Sexo:</label>
        <select name="tipoSexo" id="tipoSexo">
            <?php
            require_once('model/tipoSexo.php');
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
        <select name="idTipoDocumento" id="idTipoDocumento">
            <?php
            require_once('model/tipoDocumento.php');
            $tipoDocumentoObj = new TipoDocumento();
            $tipoDocumentos = $tipoDocumentoObj->listarTiposDocumento(); 
            
            foreach ($tipoDocumentos as $td) {
                $selected = ($td['idTipoDocumento'] == $tipoDocumento) ? "selected" : "";
                echo "<option value='{$td['idTipoDocumento']}' {$selected}>{$td['nombreTipoDocumento']}</option>";
            }
            ?>
        </select>
        <br><br>
        <label for="valorDocumento">Numero de Documento:</label>
        <input type="number" id="valorDocumento" name="valorDocumento" value="<?php echo $personaDocumento; ?>" required>
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
                echo '<input type="text" id="contactoValor' . $index . '" name="contactos[' . $index . '][valor]" value="' . $contacto['valor'] . '" required>';
                echo '<button type="button" class="eliminarContacto" data-index="' . $index . '">Eliminar</button>';
                echo '</div>';
            }
            ?>
        </div>
        <button type="button" id="agregarContacto">Agregar Contacto</button>
        <br><br>
        <input type="submit" name="actualizar" value="Actualizar">
    </form>
    <script>
        document.getElementById('agregarContacto').addEventListener('click', function() {
            var contactosDiv = document.getElementById('contactos');
            var index = contactosDiv.children.length;

            var newContactoDiv = document.createElement('div');
            newContactoDiv.classList.add('contacto');

            var tipoContactoLabel = document.createElement('label');
            tipoContactoLabel.setAttribute('for', 'tipoContacto' + index);
            tipoContactoLabel.textContent = 'Tipo de Contacto:';
            newContactoDiv.appendChild(tipoContactoLabel);

            var tipoContactoSelect = document.createElement('select');
            tipoContactoSelect.setAttribute('id', 'tipoContacto' + index);
            tipoContactoSelect.setAttribute('name', 'contactos[' + index + '][tipoContacto_idTipoContacto]');
            tipoContactoSelect.required = true;
            <?php
            $tipoContactoOptions = '';
            foreach ($tiposContacto as $tc) {
                $tipoContactoOptions .= "<option value='{$tc['idTipoContacto']}'>{$tc['nombreTipoContacto']}</option>";
            }
            ?>
            tipoContactoSelect.innerHTML = `<?php echo $tipoContactoOptions; ?>`;
            newContactoDiv.appendChild(tipoContactoSelect);

            var contactoValorLabel = document.createElement('label');
            contactoValorLabel.setAttribute('for', 'contactoValor' + index);
            contactoValorLabel.textContent = 'Contacto:';
            newContactoDiv.appendChild(contactoValorLabel);

            var contactoValorInput = document.createElement('input');
            contactoValorInput.setAttribute('type', 'text');
            contactoValorInput.setAttribute('id', 'contactoValor' + index);
            contactoValorInput.setAttribute('name', 'contactos[' + index + '][valor]');
            contactoValorInput.required = true;
            newContactoDiv.appendChild(contactoValorInput);

            var eliminarContactoBtn = document.createElement('button');
            eliminarContactoBtn.setAttribute('type', 'button');
            eliminarContactoBtn.classList.add('eliminarContacto');
            eliminarContactoBtn.setAttribute('data-index', index);
            eliminarContactoBtn.textContent = 'Eliminar';
            newContactoDiv.appendChild(eliminarContactoBtn);

            contactosDiv.appendChild(newContactoDiv);
        });

        document.getElementById('contactos').addEventListener('click', function(event) {
            if (event.target.classList.contains('eliminarContacto')) {
                var index = event.target.getAttribute('data-index');
                var contactoDiv = document.getElementById('tipoContacto' + index).closest('.contacto');
                contactoDiv.remove();
            }
        });
    </script>
</main>
