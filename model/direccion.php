<?php
require_once 'conexion.php';

class Direccion {
    public $idDomicilio;
    public $barrio;
    public $numeroCasa; 
    public $piso;
    public $descripcion;

    public function __construct($idDomicilio = null, $barrio = null, $numeroCasa = null, $piso = null, $descripcion = null) {
        $this->idDomicilio = $idDomicilio;
        $this->barrio = $barrio;
        $this->numeroCasa = $numeroCasa; 
        $this->piso = $piso;
        $this->descripcion = $descripcion;
    }



    public function actualizarDireccion($idDomicilio, $barrio, $numeroCasa, $piso, $descripcion) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "UPDATE domicilio SET barrio = ?, numeroCasa = ?, piso = ?, descripcion = ? WHERE idDomicilio = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("siisi", $barrio, $numeroCasa, $piso, $descripcion, $idDomicilio);
    
        if ($stmt->execute()) {
            $conexion->desconectar();
            return true;
        } else {
            $conexion->desconectar();
            return false;
        }
    }
    

    public function agregarDireccion($idPersona, $barrio, $numeroCasa, $piso, $descripcion) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "INSERT INTO domicilio (barrio, numeroCasa, piso, descripcion, persona_idPersona) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
    
        $piso = is_numeric($piso) ? $piso : null;
        $stmt->bind_param("siisi", $barrio, $numeroCasa, $piso, $descripcion, $idPersona);
    
        $result = $stmt->execute();
        $stmt->close();
        $conexion->desconectar();
    
        return $result;
    }    

    public function eliminarDomicilio($idDomicilio) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "DELETE FROM domicilio WHERE idDomicilio = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $idDomicilio);
    
        $resultado = $stmt->execute();
        $stmt->close();
        $conexion->desconectar();
    
        return $resultado;
    }
    
    public function obtenerDireccionesPorUsuario($idUsuario) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "SELECT d.idDomicilio, d.barrio, d.numeroCasa, d.piso, d.descripcion
                  FROM persona p
                  JOIN usuario u ON p.usuario_idUsuario = u.idUsuario
                  LEFT JOIN domicilio d ON p.idPersona = d.persona_idPersona
                  WHERE u.idUsuario = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $direcciones = [];
        while ($row = $result->fetch_assoc()) {
            $direcciones[] = new Direccion(
                $row['idDomicilio'],
                $row['barrio'],
                $row['numeroCasa'],
                $row['piso'],
                $row['descripcion']
            );
        }
    
        $stmt->close();
        $conexion->desconectar();
    
        return $direcciones;
    }
    
    public function verificarDireccionPorId($idDomicilio) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT COUNT(*) as total FROM domicilio WHERE idDomicilio = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $idDomicilio);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $conexion->desconectar();
    
        return $result['total'] > 0;
    }
    

    public function verificarDireccionUsuario($idDireccion, $idUsuario) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT COUNT(*) AS cantidad 
                  FROM persona p 
                  JOIN usuario u ON p.usuario_idUsuario = u.idUsuario 
                  LEFT JOIN domicilio d ON p.idPersona = d.persona_idPersona 
                  WHERE d.idDomicilio = ? AND u.idUsuario = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $idDireccion, $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        $conexion->desconectar();
    
        return $row['cantidad'] > 0;
    }
    

    public function existeDomicilio($idDomicilio) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "SELECT COUNT(*) FROM domicilio WHERE idDomicilio = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $idDomicilio);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
    
        $stmt->close();
        $conexion->desconectar();
    
        return $count > 0;
    }    
    

    /**
     * Get the value of descripcion
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set the value of descripcion
     */
    public function setDescripcion($descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get the value of piso
     */
    public function getPiso()
    {
        return $this->piso;
    }

    /**
     * Set the value of piso
     */
    public function setPiso($piso): self
    {
        $this->piso = $piso;

        return $this;
    }

    /**
     * Get the value of numeroCasa
     */
    public function getNumeroCasa()
    {
        return $this->numeroCasa;
    }

    /**
     * Set the value of numeroCasa
     */
    public function setNumeroCasa($numeroCasa): self
    {
        $this->numeroCasa = $numeroCasa;

        return $this;
    }

    /**
     * Get the value of barrio
     */
    public function getBarrio()
    {
        return $this->barrio;
    }

    /**
     * Set the value of barrio
     */
    public function setBarrio($barrio): self
    {
        $this->barrio = $barrio;

        return $this;
    }

    /**
     * Get the value of idDireccion
     */
    public function getIdDireccion()
    {
        return $this->idDireccion;
    }

    /**
     * Set the value of idDireccion
     */
    public function setIdDireccion($idDireccion): self
    {
        $this->idDireccion = $idDireccion;

        return $this;
    }
}