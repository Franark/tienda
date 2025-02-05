<?php
ini_set('display_errors', 1);
require_once('controller/plantillaControlador.php');

$plantilla=new plantillaControlador();
$plantilla->traerPlantilla();
?>