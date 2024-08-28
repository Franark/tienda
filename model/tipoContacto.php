<?php
require_once 'conexion.php';

class TipoContacto {
    public function listarTipoContacto() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT * FROM tipoContacto";
        $result = $conn->query($query);
        
        $tiposContacto = [];
        while ($row = $result->fetch_assoc()) {
            $tiposContacto[] = $row;
        }
        
        $conexion->desconectar();
        return $tiposContacto;
    }

    public function crearTipoContacto($nombreTipoContacto) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "INSERT INTO tipoContacto (nombreTipoContacto) VALUES ('$nombreTipoContacto')";
        $result = $conn->query($query);
        $conexion->desconectar();
        return $result;
    }

    public function actualizarTipoContacto($idTipoContacto, $nombreTipoContacto) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "UPDATE tipoContacto SET nombreTipoContacto = '$nombreTipoContacto' WHERE idTipoContacto = $idTipoContacto";
        $result = $conn->query($query);
        $conexion->desconectar();
        return $result;
    }

    public function eliminarTipoContacto($idTipoContacto) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "DELETE FROM tipoContacto WHERE idTipoContacto = $idTipoContacto";
        $result = $conn->query($query);
        $conexion->desconectar();
        return $result;
    }

    public function obtenerTipoContactoPorId($idTipoContacto) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT * FROM tipoContacto WHERE idTipoContacto = $idTipoContacto";
        $result = $conn->query($query);
        $tipoContacto = $result->fetch_assoc();
        $conexion->desconectar();
        return $tipoContacto;
    }
}
?>
