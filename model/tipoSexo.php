<?php
require_once 'conexion.php';

class TipoSexo {
    private $nombreTipoSexo;

    public function __construct($nombreTipoSexo = null) {
        $this->nombreTipoSexo = $nombreTipoSexo;
    }

    public function crearTipoSexo() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "INSERT INTO tipoSexo (nombreTipoSexo) VALUES ('$this->nombreTipoSexo')";
        $result = $conn->query($query);
        $conexion->desconectar();
        return $result;
    }

    public function eliminarTipoSexo($idTipoSexo) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "DELETE FROM tipoSexo WHERE idTipoSexo='$idTipoSexo'";
        $result = $conn->query($query);
        $conexion->desconectar();
        return $result;
    }

    public function listarTipoSexo() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT * FROM tipoSexo";
        $result = $conn->query($query);

        $tiposSexo = [];
        while ($row = $result->fetch_assoc()) {
            $tiposSexo[] = $row;
        }

        $conexion->desconectar();
        return $tiposSexo;
    }

    /**
     * Get the value of nombreTipoSexo
     */ 
    public function getNombreTipoSexo()
    {
        return $this->nombreTipoSexo;
    }

    /**
     * Set the value of nombreTipoSexo
     *
     * @return  self
     */ 
    public function setNombreTipoSexo($nombreTipoSexo)
    {
        $this->nombreTipoSexo = $nombreTipoSexo;

        return $this;
    }
}
?>
