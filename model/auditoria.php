<?php
require_once('conexion.php');

class Auditoria{
    private $idAuditoria;
    private $accion;
    private $idTabla;
    private $nombreTabla;
    private $fechaHora;
    private $nombreUsuario;

    public function registrarAuditoria($accion,$idTabla, $nombreTabla ) {
        $this->accion=$accion;
        $this->idTabla=$idTabla;
        $this->nombreTabla=$nombreTabla;
        $this->nombreUsuario=$_SESSION['nombreUsuario'];
        $this->fechaHora = date('Y-m-d H:i:s');    
    }

    public function guardar(){
        $con = new Conexion();
        $conn = $con->conectar();
        $query="INSERT INTO auditoria (fechaHora, accion, idTabla, nombreTabla, nombreUsuario) VALUES ('$this->fechaHora','$this->accion','$this->idTabla','$this->nombreTabla','$nombreUsuario')";
        echo $query;
        return $conexion->insertar($query);
    }
}
?>