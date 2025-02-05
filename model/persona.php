<?php
require_once 'conexion.php';

class Persona {
    private $idPersona;
    private $nombrePersona;
    private $apellidoPersona;
    private $edadPersona;
    private $nombreBarrio;
    private $tipoSexo_idTipoSexo;
    private $valorDocumento;
    private $tipoDocumento;
    private $contactos;
    private $domicilioDetalle;

    public function __construct($idPersona=null ,$nombrePersona = null, $apellidoPersona = null, $edadPersona = null, $nombreBarrio = null, $tipoSexo_idTipoSexo=null, $valorDocumento=null, $tipoDocumento=null, $contactos=[], $domicilioDetalle=[]) {
        $this->idPersona = $idPersona;
        $this->nombrePersona = $nombrePersona;
        $this->apellidoPersona = $apellidoPersona;
        $this->edadPersona = $edadPersona;
        $this->nombreBarrio = $nombreBarrio;
        $this->tipoSexo_idTipoSexo = $tipoSexo_idTipoSexo;
        $this->valorDocumento = $valorDocumento;
        $this->tipoDocumento = $tipoDocumento;
        $this->contactos = $contactos;
        $this->domicilioDetalle = $domicilioDetalle;
    }

    public function listarPersona($idUsuario) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "SELECT
                    p.idPersona, 
                    p.nombrePersona, 
                    p.apellidoPersona, 
                    p.edadPersona, 
                    p.tipoSexo_idTipoSexo,
                    pd.valor AS valorDocumento,
                    pd.tipoDocumento_idTipoDocumento,
                    pc.valor AS valorContacto,
                    pc.tipoContacto_idTipoContacto,
                    d.idDomicilio,
                    d.barrio,
                    d.numeroCasa,
                    d.piso,
                    d.descripcion
                  FROM persona p 
                  JOIN usuario u ON p.usuario_idUsuario = u.idUsuario 
                  LEFT JOIN personaDocumento pd ON p.idPersona = pd.persona_idPersona
                  LEFT JOIN personaContacto pc ON p.idPersona = pc.persona_idPersona
                  LEFT JOIN domicilio d ON p.idPersona = d.persona_idPersona
                  WHERE u.idUsuario = ?";
    
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if (!$result) {
            error_log("Error en listarPersona para usuario {$idUsuario}: " . $conn->error);
            throw new Exception("Error en la consulta SQL.");
        }
    
        $personas = [];
        while ($row = $result->fetch_assoc()) {
            $idPersona = $row['idPersona'];
    
            if (!isset($personas[$idPersona])) {
                $personas[$idPersona] = [
                    'idPersona' => $row['idPersona'],
                    'nombrePersona' => $row['nombrePersona'],
                    'apellidoPersona' => $row['apellidoPersona'],
                    'edadPersona' => $row['edadPersona'],
                    'tipoSexo_idTipoSexo' => $row['tipoSexo_idTipoSexo'],
                    'valorDocumento' => $row['valorDocumento'],
                    'idTipoDocumento' => $row['tipoDocumento_idTipoDocumento'],
                    'contactos' => [],
                    'direcciones' => []
                ];
            }
    
            $contactExists = array_filter($personas[$idPersona]['contactos'], function ($contacto) use ($row) {
                return $contacto['valor'] === $row['valorContacto'];
            });
            if (empty($contactExists) && !empty($row['valorContacto'])) {
                $personas[$idPersona]['contactos'][] = [
                    'valor' => $row['valorContacto'],
                    'tipoContacto_idTipoContacto' => $row['tipoContacto_idTipoContacto']
                ];
            }
    
            $addressExists = array_filter($personas[$idPersona]['direcciones'], function ($direccion) use ($row) {
                return $direccion['idDomicilio'] === $row['idDomicilio'];
            });
            if (empty($addressExists) && !empty($row['idDomicilio'])) {
                $personas[$idPersona]['direcciones'][] = [
                    'idDomicilio' => $row['idDomicilio'],
                    'barrio' => $row['barrio'],
                    'numeroCasa' => $row['numeroCasa'],
                    'piso' => $row['piso'],
                    'descripcion' => $row['descripcion']
                ];
            }
        }
    
        $stmt->close();
        $conexion->desconectar();
    
        return array_values($personas);
    }
    
    
    
    
    
    public function actualizarDireccion($idPersona, $barrio, $numeroCasa, $piso, $descripcion) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "UPDATE domicilio 
                  SET barrio = '$barrio', numeroCasa = '$numeroCasa', piso='$piso', descripcion '$descripcion'
                  WHERE persona_idPersona = '$this->idPersona'";

        if ($conn->query($query) === TRUE) {
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
    
    public function actualizarPersona() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "UPDATE persona 
                  SET nombrePersona = '$this->nombrePersona', 
                      apellidoPersona = '$this->apellidoPersona', 
                      edadPersona = '$this->edadPersona', 
                      tipoSexo_idTipoSexo = '$this->tipoSexo_idTipoSexo' 
                  WHERE idPersona = '$this->idPersona'";
        
        if ($conn->query($query) === TRUE) {
            
            $queryCheck = "SELECT COUNT(*) as count FROM personaDocumento WHERE persona_idPersona = '$this->idPersona'";
            $resultCheck = $conn->query($queryCheck);
            $rowCheck = $resultCheck->fetch_assoc();
    
            if ($rowCheck['count'] > 0) {
                $queryDocumento = "UPDATE personaDocumento 
                                   SET valor = '$this->valorDocumento' 
                                   WHERE persona_idPersona = '$this->idPersona' AND tipoDocumento_idTipoDocumento='$this->tipoDocumento'";
            } else {
                $queryDocumento = "INSERT INTO personaDocumento (valor, persona_idPersona, tipoDocumento_idTipoDocumento) 
                                   VALUES ('$this->valorDocumento', '$this->idPersona', '$this->tipoDocumento')";
            }
    
            $conn->query($queryDocumento);
    
            $queryDeleteContactos = "DELETE FROM personaContacto WHERE persona_idPersona = '$this->idPersona'";
            $conn->query($queryDeleteContactos);
    
            foreach ($this->contactos as $contacto) {
                $queryInsertContacto = "INSERT INTO personaContacto (valor, persona_idPersona, tipoContacto_idTipoContacto) 
                                        VALUES ('{$contacto['valor']}', '$this->idPersona', '{$contacto['tipoContacto_idTipoContacto']}')";
                $conn->query($queryInsertContacto);
            }
            
            $conexion->desconectar();
            return true;
    
        } else {
            $conexion->desconectar();
            return false;
        }
    }
    
    public function actualizarContacto($idContacto, $tipoContacto_idTipoContacto, $valor) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();

        $query = "UPDATE personaContacto 
                  SET valor = '$valor', tipoContacto_idTipoContacto = '$tipoContacto_idTipoContacto' 
                  WHERE idContacto = '$idContacto' AND persona_idPersona = '$this->idPersona'";

        if ($conn->query($query) === TRUE) {
            $conexion->desconectar();
            return true;
        } else {
            $conexion->desconectar();
            return false;
        }
    }

    public function agregarContacto($idPersona, $tipoContacto_idTipoContacto, $valor) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "INSERT INTO personaContacto (valor, persona_idPersona, tipoContacto_idTipoContacto) 
                  VALUES (?, ?, ?)";
    
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            $conexion->desconectar();
            return false;
        }
    
        $stmt->bind_param("sii", $valor, $idPersona, $tipoContacto_idTipoContacto);
    
        if ($stmt->execute()) {
            $stmt->close();
            $conexion->desconectar();
            return true;
        } else {
            $stmt->close();
            $conexion->desconectar();
            return false;
        }
    }    
    
    public function eliminarContacto($idPersonaContacto) {
        if (empty($idPersonaContacto)) {
            echo "ID de contacto vacío, no se puede eliminar.";
            return false;
        }
    
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = $conn->prepare("DELETE FROM personaContacto WHERE idPersonaContacto = ?");
        $query->bind_param("i", $idPersonaContacto);
        $resultado = $query->execute();
    
        if ($resultado) {
            echo "Contacto con ID $idPersonaContacto eliminado.";
        } else {
            echo "Error al eliminar el contacto con ID $idPersonaContacto.";
        }
    
        $conexion->desconectar();
        return $resultado;
    }
    
    public function guardarDireccion($idPersona, $direccion) {
        $conexion = $this->obtenerConexion();
    
        $query = "SELECT idDomicilio FROM domicilios WHERE idPersona = ? AND barrio = ? AND numeroCasa = ?";
        $stmt = $conexion->prepare($query);
        $stmt->execute([$idPersona, $direccion['barrio'], $direccion['numeroCasa']]);
        $direccionExistente = $stmt->fetch();
    
        if ($direccionExistente) {
            $query = "UPDATE domicilios SET piso = ?, descripcion = ? WHERE idDomicilio = ?";
            $stmt = $conexion->prepare($query);
            $stmt->execute([
                $direccion['piso'],
                $direccion['descripcion'],
                $direccionExistente['idDomicilio']
            ]);
        } else {
            $query = "INSERT INTO domicilios (idPersona, barrio, numeroCasa, piso, descripcion) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conexion->prepare($query);
            $stmt->execute([
                $idPersona,
                $direccion['barrio'],
                $direccion['numeroCasa'],
                $direccion['piso'],
                $direccion['descripcion']
            ]);
        }
    }
    

    /**
     * Get the value of nombreBarrio
     */ 
    public function getNombreBarrio()
    {
        return $this->nombreBarrio;
    }

    /**
     * Set the value of nombreBarrio
     *
     * @return  self
     */ 
    public function setNombreBarrio($nombreBarrio)
    {
        $this->nombreBarrio = $nombreBarrio;

        return $this;
    }

    /**
     * Get the value of edadPersona
     */ 
    public function getEdadPersona()
    {
        return $this->edadPersona;
    }

    /**
     * Set the value of edadPersona
     *
     * @return  self
     */ 
    public function setEdadPersona($edadPersona)
    {
        $this->edadPersona = $edadPersona;

        return $this;
    }

    /**
     * Get the value of apellidoPersona
     */ 
    public function getApellidoPersona()
    {
        return $this->apellidoPersona;
    }

    /**
     * Set the value of apellidoPersona
     *
     * @return  self
     */ 
    public function setApellidoPersona($apellidoPersona)
    {
        $this->apellidoPersona = $apellidoPersona;

        return $this;
    }

    /**
     * Get the value of nombrePersona
     */ 
    public function getNombrePersona()
    {
        return $this->nombrePersona;
    }

    /**
     * Set the value of nombrePersona
     *
     * @return  self
     */ 
    public function setNombrePersona($nombrePersona)
    {
        $this->nombrePersona = $nombrePersona;

        return $this;
    }

    /**
     * Get the value of idPersona
     */ 
    public function getIdPersona()
    {
        return $this->idPersona;
    }

    /**
     * Set the value of idPersona
     *
     * @return  self
     */ 
    public function setIdPersona($idPersona)
    {
        $this->idPersona = $idPersona;

        return $this;
    }

    /**
     * Get the value of tipoSexo_idTipoSexo
     */ 
    public function getTipoSexo_idTipoSexo()
    {
        return $this->tipoSexo_idTipoSexo;
    }

    /**
     * Set the value of tipoSexo_idTipoSexo
     *
     * @return  self
     */ 
    public function setTipoSexo_idTipoSexo($tipoSexo_idTipoSexo)
    {
        $this->tipoSexo_idTipoSexo = $tipoSexo_idTipoSexo;

        return $this;
    }

    /**
     * Get the value of valorDocumento
     */ 
    public function getValorDocumento()
    {
        return $this->valorDocumento;
    }

    /**
     * Set the value of valorDocumento
     *
     * @return  self
     */ 
    public function setValorDocumento($valorDocumento)
    {
        $this->valorDocumento = $valorDocumento;

        return $this;
    }

    /**
     * Get the value of tipoDocumento
     */ 
    public function getTipoDocumento()
    {
        return $this->tipoDocumento;
    }

    /**
     * Set the value of tipoDocumento
     *
     * @return  self
     */ 
    public function setTipoDocumento($tipoDocumento)
    {
        $this->tipoDocumento = $tipoDocumento;

        return $this;
    }

    /**
     * Get the value of contactos
     */ 
    public function getContactos()
    {
        return $this->contactos;
    }

    /**
     * Set the value of contactos
     *
     * @return  self
     */ 
    public function setContactos($contactos)
    {
        $this->contactos = $contactos;

        return $this;
    }

    /**
     * Get the value of domicilioDetalle
     */ 
    public function getDomicilioDetalle()
    {
        return $this->domicilioDetalle;
    }

    /**
     * Set the value of domicilioDetalle
     *
     * @return  self
     */ 
    public function setDomicilioDetalle($domicilioDetalle)
    {
        $this->domicilioDetalle = $domicilioDetalle;

        return $this;
    }
}
?>