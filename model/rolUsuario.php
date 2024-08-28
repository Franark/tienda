<?php
require_once("conexion.php");

class RolUsuario {
    public $id;
    public $nombreRol;

    public function __construct($id = '', $nombreRol = '') {
        $this->id = $id;
        $this->nombreRol = $nombreRol;
    }

    public function listarRoles() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT * FROM rolUsuario";
        $result = $conn->query($query);

        $roles = [];
        while ($row = $result->fetch_assoc()) {
            $roles[] = $row;
        }

        $conexion->desconectar();
        return $roles;
    }

    public function crearRol($nombreRol) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "INSERT INTO rolUsuario (nombreRol) VALUES ('$nombreRol')";
        $conn->query($query);
        $conexion->desconectar();
    }

    public function eliminarRol($idRol) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "DELETE FROM rolUsuario WHERE idRol = $idRol";
        $conn->query($query);
        $conexion->desconectar();
    }

    public function editarRol($idRol, $nombreRol) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "UPDATE rolUsuario SET nombreRol = '$nombreRol' WHERE idRol = $idRol";
        $conn->query($query);
        $conexion->desconectar();
    }

    public function buscarRolPorId($idRol) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT * FROM rolUsuario WHERE idRol = $idRol";
        $result = $conn->query($query);

        $row = $result->fetch_assoc();

        $conexion->desconectar();

        if ($row) {
            $this->id = $row['idRol'];
            $this->nombreRol = $row['nombreRol'];
        } else {
            $this->id = null;
            $this->nombreRol = null;
        }

        return $this;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getNombreRol() {
        return $this->nombreRol;
    }

    public function setNombreRol($nombreRol) {
        $this->nombreRol = $nombreRol;
        return $this;
    }
}
?>
