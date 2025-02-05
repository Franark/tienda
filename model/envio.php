<?php
require_once('conexion.php');
require_once('orden.php');

class Envio {
    private $idEnvio;
    private $fechaEnvio;
    private $estadoEnvio;
    private $orden_idOrden;

    public function __construct($idEnvio = null, $fechaEnvio = null, $estadoEnvio = null, $orden_idOrden = null) {
        $this->idEnvio = $idEnvio;
        $this->fechaEnvio = $fechaEnvio;
        $this->estadoEnvio = $estadoEnvio;
        $this->orden_idOrden = $orden_idOrden;
    }

    public function crearEnvio() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "INSERT INTO envio (fechaEnvio, estadoEnvio, orden_idOrden) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
    
        if (!$stmt) {
            error_log("Error en prepare: " . $conn->error);
            $conexion->desconectar();
            return false;
        }
    
        $stmt->bind_param("ssi", $this->fechaEnvio, $this->estadoEnvio, $this->orden_idOrden);
    
        if (!$stmt->execute()) {
            error_log("Error en execute: " . $stmt->error);
            $stmt->close();
            $conexion->desconectar();
            return false;
        }
    
        $stmt->close();
        $conexion->desconectar();
        return true;
    }    

    public function cambiarEstadoEnvio($nuevoEstado, $repartidorId = null) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "UPDATE envio SET estadoEnvio = ?, repartidor_idUsuario = ? WHERE idEnvio = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sii", $nuevoEstado, $repartidorId, $this->idEnvio);
    
        error_log("Consulta SQL: " . $query . " - Valores: $nuevoEstado, $repartidorId, $this->idEnvio");
    
        if ($stmt->execute()) {
            error_log("Actualización exitosa.");
            $stmt->close();
            $conexion->desconectar();
            return true;
        } else {
            error_log("Error en la consulta: " . $stmt->error);
            $stmt->close();
            $conexion->desconectar();
            return false;
        }
    }
    
    
    
    
    
    private function actualizarEstadoOrden($nuevoEstadoOrden) {
        $idOrden = $this->obtenerIdOrden();
    
        if ($idOrden) {
            $orden = new Orden();
            $orden->setOrdenIdOrden($idOrden);
            $orden->cambiarEstado($nuevoEstadoOrden);
        } else {
            echo "No se encontró una orden asociada al envío.";
        }
    }
    
    private function obtenerIdOrden() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "SELECT orden_idOrden FROM envio WHERE idEnvio = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $this->idEnvio);
        $stmt->execute();
        $stmt->bind_result($idOrden);
        $stmt->fetch();
        $stmt->close();
        $conexion->desconectar();
    
        return $idOrden;
    }    

    
    public function listarEnviosEnProceso($idRepartidor, $limite, $offset) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "SELECT e.idEnvio, e.fechaEnvio, e.estadoEnvio, o.idOrden, u.nickname
                  FROM envio e
                  JOIN orden o ON e.orden_idOrden = o.idOrden
                  JOIN cliente c ON o.cliente_idCliente = c.idCliente
                  JOIN persona pe ON pe.idPersona = c.persona_idPersona
                  JOIN usuario u ON pe.usuario_idUsuario = u.idUsuario
                  WHERE e.estadoEnvio = 'En Proceso' AND e.repartidor_idUsuario = ?
                  LIMIT ? OFFSET ?";
    
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iii", $idRepartidor, $limite, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        $envios = $result->fetch_all(MYSQLI_ASSOC);
    
        $stmt->close();
        $conn->close();
    
        return $envios;
    }
    
    public function listarEnviosPendientes($limite, $offset) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "SELECT e.idEnvio, e.fechaEnvio, e.estadoEnvio, o.idOrden, u.nickname
                  FROM envio e
                  JOIN orden o ON e.orden_idOrden = o.idOrden
                  JOIN cliente c ON o.cliente_idCliente = c.idCliente
                  JOIN persona pe ON pe.idPersona = c.persona_idPersona
                  JOIN usuario u ON pe.usuario_idUsuario = u.idUsuario
                  WHERE e.estadoEnvio = 'Pendiente'
                  LIMIT ? OFFSET ?";
    
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $limite, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        $envios = $result->fetch_all(MYSQLI_ASSOC);
    
        $stmt->close();
        $conn->close();
    
        return $envios;
    }
    
    public function listarEnviosEntregados($idRepartidor, $limite, $offset) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "SELECT e.idEnvio, e.fechaEnvio, e.estadoEnvio, o.idOrden, u.nickname
                  FROM envio e
                  JOIN orden o ON e.orden_idOrden = o.idOrden
                  JOIN cliente c ON o.cliente_idCliente = c.idCliente
                  JOIN persona pe ON pe.idPersona = c.persona_idPersona
                  JOIN usuario u ON pe.usuario_idUsuario = u.idUsuario
                  WHERE e.estadoEnvio = 'Entregado' AND e.repartidor_idUsuario = ?
                  LIMIT ? OFFSET ?";
    
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iii", $idRepartidor, $limite, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        $envios = $result->fetch_all(MYSQLI_ASSOC);
    
        $stmt->close();
        $conn->close();
    
        return $envios;
    }
    
    public function listarEnviosCancelados($idRepartidor, $limite, $offset) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "SELECT e.idEnvio, e.fechaEnvio, e.estadoEnvio, o.idOrden, u.nickname
                  FROM envio e
                  JOIN orden o ON e.orden_idOrden = o.idOrden
                  JOIN cliente c ON o.cliente_idCliente = c.idCliente
                  JOIN persona pe ON pe.idPersona = c.persona_idPersona
                  JOIN usuario u ON pe.usuario_idUsuario = u.idUsuario
                  WHERE e.estadoEnvio = 'Cancelado' AND e.repartidor_idUsuario = ?
                  LIMIT ? OFFSET ?";
    
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iii", $idRepartidor, $limite, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        $envios = $result->fetch_all(MYSQLI_ASSOC);
    
        $stmt->close();
        $conn->close();
    
        return $envios;
    }
    
    
    public function obtenerDetalleEnvio($idEnvio) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT e.idEnvio, e.fechaEnvio, e.estadoEnvio, o.idOrden, u.nickname
                  FROM envio e
                  JOIN orden o ON e.orden_idOrden = o.idOrden
                  JOIN cliente c ON o.cliente_idCliente = c.idCliente
                  JOIN persona p ON p.idPersona = c.persona_idPersona
                  JOIN usuario u ON p.usuario_idUsuario = u.idUsuario
                  WHERE e.idEnvio = ?";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $idEnvio);
        $stmt->execute();
        $resultado = $stmt->get_result();
    
        $detalleEnvio = [];
        if ($resultado && $resultado->num_rows > 0) {
            $detalleEnvio = $resultado->fetch_assoc();
            $queryProductos = "SELECT p.nombreProducto, op.cantidad
                               FROM ordenProducto op
                               JOIN producto p ON op.producto_idProducto = p.idProducto
                               WHERE op.orden_idOrden = ?";
            
            $stmtProductos = $conn->prepare($queryProductos);
            $stmtProductos->bind_param("i", $detalleEnvio['idOrden']);
            $stmtProductos->execute();
            $resultadoProductos = $stmtProductos->get_result();
    
            $productos = [];
            if ($resultadoProductos && $resultadoProductos->num_rows > 0) {
                while ($producto = $resultadoProductos->fetch_assoc()) {
                    $productos[] = $producto;
                }
            }
    
            $detalleEnvio['productos'] = $productos;
        }
    
        $stmt->close();
        $conexion->desconectar();
    
        return $detalleEnvio;
    }
    
    public function obtenerIdEnvioPorOrden($idOrden) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "SELECT idEnvio FROM envio WHERE orden_idOrden = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $idOrden);
        $stmt->execute();
        $stmt->bind_result($idEnvio);
        $stmt->fetch();
    
        $stmt->close();
        $conexion->desconectar();
    
        return $idEnvio ? $idEnvio : false;
    }    

    public function obtenerOrdenIdPorEnvio($idEnvio) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "SELECT orden_idOrden FROM envio WHERE idEnvio = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $idEnvio);
        $stmt->execute();
        $stmt->bind_result($idOrden);
        $stmt->fetch();
    
        $stmt->close();
        $conexion->desconectar();
    
        return $idOrden;
    }

    public function contarEnviosPendientes(){
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        
        $query = "SELECT COUNT(*) as total
                  FROM envio
                  WHERE estadoEnvio = 'Pendiente'";
        $resultado = $conn->query($query);
        
        if ($resultado) {
            $fila = $resultado->fetch_assoc();
            $conexion->desconectar();
            return $fila['total'];
        } else {
            error_log("Error: " . $conn->error);
        }
        
        $conexion->desconectar();
        return 0;
    }

    public function contarEnviosEnProceso(){
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        
        $query = "SELECT COUNT(*) as total
                  FROM envio
                  WHERE estadoEnvio = 'En proceso'";
        $resultado = $conn->query($query);
        
        if ($resultado) {
            $fila = $resultado->fetch_assoc();
            $conexion->desconectar();
            return $fila['total'];
        } else {
            error_log("Error: " . $conn->error);
        }
        
        $conexion->desconectar();
        return 0;
    }
    
    public function cancelarEnvio($idEnvio) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "UPDATE envio SET estadoEnvio = 'Cancelado' WHERE idEnvio = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $idEnvio);
    
        if ($stmt->execute()) {
            $stmt->close();
            $conexion->desconectar();
            return true;
        } else {
            error_log("Error al cancelar envío: " . $stmt->error);
        }
    
        $stmt->close();
        $conexion->desconectar();
        return false;
    }    

    /**
     * Get the value of idEnvio
     */
    public function getIdEnvio()
    {
        return $this->idEnvio;
    }

    /**
     * Set the value of idEnvio
     */
    public function setIdEnvio($idEnvio): self
    {
        $this->idEnvio = $idEnvio;

        return $this;
    }

    /**
     * Get the value of fechaEnvio
     */
    public function getFechaEnvio()
    {
        return $this->fechaEnvio;
    }

    /**
     * Set the value of fechaEnvio
     */
    public function setFechaEnvio($fechaEnvio): self
    {
        $this->fechaEnvio = $fechaEnvio;

        return $this;
    }

    /**
     * Get the value of estadoEnvio
     */
    public function getEstadoEnvio()
    {
        return $this->estadoEnvio;
    }

    /**
     * Set the value of estadoEnvio
     */
    public function setEstadoEnvio($estadoEnvio): self
    {
        $this->estadoEnvio = $estadoEnvio;

        return $this;
    }

    /**
     * Get the value of orden_idOrden
     */
    public function getOrdenIdOrden()
    {
        return $this->orden_idOrden;
    }

    /**
     * Set the value of orden_idOrden
     */
    public function setOrdenIdOrden($orden_idOrden): self
    {
        $this->orden_idOrden = $orden_idOrden;

        return $this;
    }
}
?>
