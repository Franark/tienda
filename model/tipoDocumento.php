<?php
require_once 'conexion.php';

class TipoDocumento {
    private $idTipoDocumento;
    private $nombreTipoDocumento;

    public function __construct($nombreTipoDocumento = null) {
        $this->nombreTipoDocumento = $nombreTipoDocumento;
    }

    public function crearTipoDocumento() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "INSERT INTO tipoDocumento (nombreTipoDocumento) VALUES ('$this->nombreTipoDocumento')";
        $result = $conn->query($query);
        $conexion->desconectar();
        return $result;
    }

    public function actualizarTipoDocumento() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "UPDATE tipoDocumento SET nombreTipoDocumento='$this->nombreTipoDocumento' WHERE idTipoDocumento='$this->idTipoDocumento'";
        $result = $conn->query($query);
        $conexion->desconectar();
        return $result;
    }

    public function eliminarTipoDocumento($idTipoDocumento) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "DELETE FROM tipoDocumento WHERE idTipoDocumento='$idTipoDocumento'";
        $result = $conn->query($query);
        $conexion->desconectar();
        return $result;
    }

    public function obtenerTipoDocumentoPorId($idTipoDocumento) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT * FROM tipoDocumento WHERE idTipoDocumento='$idTipoDocumento'";
        $result = $conn->query($query);

        $documento = null;
        if ($result->num_rows > 0) {
            $documento = $result->fetch_assoc();
        }

        $conexion->desconectar();
        return $documento;
    }

    public function listarTiposDocumento() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT * FROM tipoDocumento";
        $result = $conn->query($query);

        $tiposDocumento = [];
        while ($row = $result->fetch_assoc()) {
            $tiposDocumento[] = $row;
        }

        $conexion->desconectar();
        return $tiposDocumento;
    }

    /**
     * Get the value of idTipoDocumento
     */ 
    public function getIdTipoDocumento()
    {
        return $this->idTipoDocumento;
    }

    /**
     * Set the value of idTipoDocumento
     *
     * @return  self
     */ 
    public function setIdTipoDocumento($idTipoDocumento)
    {
        $this->idTipoDocumento = $idTipoDocumento;

        return $this;
    }

    /**
     * Get the value of nombreTipoDocumento
     */ 
    public function getNombreTipoDocumento()
    {
        return $this->nombreTipoDocumento;
    }

    /**
     * Set the value of nombreTipoDocumento
     *
     * @return  self
     */ 
    public function setNombreTipoDocumento($nombreTipoDocumento)
    {
        $this->nombreTipoDocumento = $nombreTipoDocumento;

        return $this;
    }
}
?>
