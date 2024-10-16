<h1>Gestión de Envíos</h1>
</header>
<main>
    <h2 onclick="toggleTable('pendientes')" style="cursor: pointer;">
        <span id="arrowPendientes">⏷</span> Envíos Pendientes
    </h2>
    <table id="pendientes" style="display: none;">
        <thead>
            <tr>
                <th>ID Envío</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="pendientesBody"></tbody>
    </table>
    <div id="paginationPendientes" style="display: none;"></div>

    <h2 onclick="toggleTable('enProceso')" style="cursor: pointer;">
        <span id="arrowEnProceso">⏷</span> Envíos En Proceso
    </h2>
    <table id="enProceso" style="display: none;">
        <thead>
            <tr>
                <th>ID Envío</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="enProcesoBody"></tbody>
    </table>
    <div id="paginationEnProceso" style="display: none;"></div>

    <h2 onclick="toggleTable('entregados')" style="cursor: pointer;">
        <span id="arrowEntregados">⏷</span> Envíos Entregados
    </h2>
    <table id="entregados" style="display: none;">
        <thead>
            <tr>
                <th>ID Envío</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="entregadosBody"></tbody>
    </table>
    <div id="paginationEntregados" style="display: none;"></div>

    <h2 onclick="toggleTable('cancelados')" style="cursor: pointer;">
        <span id="arrowCancelados">⏷</span> Envíos Cancelados
    </h2>
    <table id="cancelados" style="display: none;">
        <thead>
            <tr>
                <th>ID Envío</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody id="canceladosBody"></tbody>
    </table>
    <div id="paginationCancelados" style="display: none;"></div>
</main>

<script>
    function toggleTable(tableId) {
        var table = document.getElementById(tableId);
        var arrow = document.getElementById('arrow' + tableId.charAt(0).toUpperCase() + tableId.slice(1));
        var pagination = document.getElementById('pagination' + tableId.charAt(0).toUpperCase() + tableId.slice(1));

        if (table.style.display === "none") {
            table.style.display = "table";
            if (pagination) pagination.style.display = "block";
            arrow.textContent = '⏵';
            inicializarPaginacion(tableId); // Inicializa paginación al mostrar la tabla
        } else {
            table.style.display = "none";
            if (pagination) pagination.style.display = "none";
            arrow.textContent = '⏷';
        }
    }

    function cargarEnvios(pagina, tablaId) {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "controller/cargarEnvios.php?pagina=" + pagina + "&tabla=" + tablaId, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById(tablaId + "Body").innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    }

    function generarPaginacion(totalPaginas, paginaActual, tablaId) {
        var paginationContainer = document.getElementById('pagination' + tablaId.charAt(0).toUpperCase() + tablaId.slice(1));
        paginationContainer.innerHTML = '';

        for (let i = 1; i <= totalPaginas; i++) {
            let button = document.createElement('button');
            button.innerText = i;
            if (i === paginaActual) button.disabled = true;
            button.onclick = function() {
                cargarEnvios(i, tablaId);
                generarPaginacion(totalPaginas, i, tablaId);
            };
            paginationContainer.appendChild(button);
        }
    }

    function inicializarPaginacion(tablaId) {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "controller/obtenerTotalEnvios.php?tabla=" + tablaId, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var totalEnvios = parseInt(xhr.responseText);
                var enviosPorPagina = 5;
                var totalPaginas = Math.ceil(totalEnvios / enviosPorPagina);

                cargarEnvios(1, tablaId);
                generarPaginacion(totalPaginas, 1, tablaId);
            }
        };
        xhr.send();
    }

    function cancelarEnvio(idEnvio) {
        if (confirm("¿Estás seguro de que deseas cancelar este envío?")) {
            fetch('controller/envioControlador.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    accion: 'cancelarEnvio',
                    idEnvio: idEnvio
                })
            })
            .then(response => response.text())
            .then(data => {
                if (data === 'OK') {
                    alert('Envío cancelado correctamente.');
                    location.reload();
                } else {
                    alert('Error al cancelar el envío.');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }

</script>
