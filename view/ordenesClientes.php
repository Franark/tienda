<header>
    <h1>Mis Órdenes</h1>
</header>
<main>
    <h2>Órdenes Realizadas</h2>
    <table id="misOrdenes">
        <thead>
            <tr>
                <th>Número del pedido</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody id="misOrdenesBody"></tbody>
    </table>
    <div id="paginationMisOrdenes" style="display: none;"></div>
</main>

<script>
    const urlParams = new URLSearchParams(window.location.search);
    const mensaje = urlParams.get('mensaje');
    if (mensaje) {
        Swal.fire({
            icon: 'success',
            title: 'Compra realizada con éxito',
            text: decodeURIComponent(mensaje),
            confirmButtonText: 'Aceptar'
        });
    }

    function cargarMisOrdenes(pagina) {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "controller/cargarMisOrdenes.php?pagina=" + pagina, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById('misOrdenesBody').innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    }

    function generarPaginacion(totalPaginas, paginaActual) {
        var paginationContainer = document.getElementById('paginationMisOrdenes');
        paginationContainer.innerHTML = '';

        for (let i = 1; i <= totalPaginas; i++) {
            let button = document.createElement('button');
            button.innerText = i;
            if (i === paginaActual) button.disabled = true;
            button.onclick = function() {
                cargarMisOrdenes(i);
                generarPaginacion(totalPaginas, i);
            };
            paginationContainer.appendChild(button);
        }
    }

    function inicializarPaginacion() {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "controller/obtenerTotalMisOrdenes.php", true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var totalOrdenes = parseInt(xhr.responseText);
                var ordenesPorPagina = 10;
                var totalPaginas = Math.ceil(totalOrdenes / ordenesPorPagina);

                cargarMisOrdenes(1);
                generarPaginacion(totalPaginas, 1);
            }
        };
        xhr.send();
    }

    inicializarPaginacion();
</script>
