<?php
require_once('../model/conexion.php');
require_once('../model/orden.php');

session_start();
$usuarioId = $_SESSION['idUsuario'];

$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$limite = 10;
$offset = ($pagina - 1) * $limite;

$orden = new Orden();
$ordenes = $orden->listarOrdenesPorUsuario($usuarioId, $limite, $offset);

foreach ($ordenes as $orden) {
  echo "<tr>
          <td><a href='?page=detallesOrdenCliente&idOrden={$orden['idOrden']}'>{$orden['idOrden']}</a></td>
          <td>{$orden['fechaOrden']}</td>
          <td>{$orden['montoTotal']}</td>
          <td>{$orden['estado']}</td>
        </tr>";
}
?>
