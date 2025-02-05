<header>
    <h1>Gestión de Órdenes</h1>
</header>
<main>
    <h2 onclick="toggleTable('pendientes')" style="cursor: pointer;">
        <span id="arrowPendientes">⏷</span> Pedidos Pendientes
    </h2>
    <table id="pendientes" style="display: none;">
        <thead>
            <tr>
                <th>Número del pedido</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Ver mas</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="pendientesBody"></tbody>
    </table>
    <div id="paginationPendientes" style="display: none;"></div>

    <h2 onclick="toggleTable('enProceso')" style="cursor: pointer;">
        <span id="arrowEnProceso">⏷</span> Pedidos En Proceso
    </h2>
    <table id="enProceso" style="display: none;">
        <thead>
            <tr>
                <th>Número del pedido</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Ver mas</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="enProcesoBody"></tbody>
    </table>
    <div id="paginationEnProceso" style="display: none;"></div>

    <h2 onclick="toggleTable('entregados')" style="cursor: pointer;">
        <span id="arrowEntregados">⏷</span> Pedidos Entregados
    </h2>
    <table id="entregados" style="display: none;">
        <thead>
            <tr>
                <th>Número del pedido</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Ver mas</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody id="entregadosBody"></tbody>
    </table>
    <div id="paginationEntregados" style="display: none;"></div>

    <h2 onclick="toggleTable('cancelados')" style="cursor: pointer;">
        <span id="arrowCancelados">⏷</span> Pedidos Cancelados
    </h2>
    <table id="cancelados" style="display: none;">
        <thead>
            <tr>
                <th>Número del pedido</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Ver mas</th>
                <th>Total</th>
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
        } else {
            table.style.display = "none";
            if (pagination) pagination.style.display = "none";
            arrow.textContent = '⏷';
        }
    }

    function cargarOrdenes(pagina, tablaId) {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "controller/cargarOrdenes.php?pagina=" + pagina + "&tabla=" + tablaId, true);
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
                cargarOrdenes(i, tablaId);
                generarPaginacion(totalPaginas, i, tablaId);
            };
            paginationContainer.appendChild(button);
        }
    }

    function inicializarPaginacion(tablaId) {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "controller/obtenerTotalOrdenes.php?tabla=" + tablaId, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var totalOrdenes = parseInt(xhr.responseText);
                var ordenesPorPagina = 10;
                var totalPaginas = Math.ceil(totalOrdenes / ordenesPorPagina);

                cargarOrdenes(1, tablaId);
                generarPaginacion(totalPaginas, 1, tablaId);
            }
        };
        xhr.send();
    }

    function cancelarPedido(idOrden) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás recuperar esta acción!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, cancelar',
            cancelButtonText: 'No, mantener'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('controller/ordenControlador.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({
                        accion: 'cancelarPedido',
                        idOrden: idOrden
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        actualizarStock(data.productos, 'cancelado');
                        mostrarAlertaExito('Pedido cancelado correctamente.');
                        location.reload();
                    } else {
                        mostrarAlertaError('Hubo un problema al cancelar la orden.');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    }

    function aceptarOrden(idOrden) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¿Deseas aceptar esta orden?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, aceptar',
            cancelButtonText: 'No, volver'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('controller/ordenControlador.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({
                        accion: 'aceptarOrden',
                        idOrden: idOrden
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        actualizarStock(data.productos, 'aceptado');
                        Swal.fire(
                            'Aceptado!',
                            'La orden ha sido aceptada.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire(
                            'Error!',
                            'Hubo un problema al aceptar la orden.',
                            'error'
                        );
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    }

    function actualizarStock(productos, accion) {
        productos.forEach(producto => {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "controller/productoControlador.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send(new URLSearchParams({
                idProducto: producto.idProducto,
                cantidad: producto.cantidad,
                accion: accion
            }));
        });
    }

    function mostrarAlertaExito(mensaje) {
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: mensaje,
            confirmButtonText: 'Aceptar'
        });
    }

    function mostrarAlertaError(mensaje) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: mensaje,
            confirmButtonText: 'Aceptar'
        });
    }

    inicializarPaginacion('pendientes');
    inicializarPaginacion('enProceso');
    inicializarPaginacion('entregados');
    inicializarPaginacion('cancelados');
</script>
