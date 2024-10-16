<?php
require_once('conexion.php');

class Orden{
    private $idOrden;
    private $fechaOrden;
    private $cliente_idCliente;
    private $estado;

    public function __construct($idOrden=null , $fechaOrden=null, $cliente_idCliente=null, $estado=null){
        $this->idOrden = $idOrden;
        $this->fechaOrden = $fechaOrden;
        $this->cliente_idCliente = $cliente_idCliente;
        $this->estado = $estado;
    }

    public function listarOrdenesPendientes($limite, $offset) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "SELECT 
                    o.idOrden, 
                    o.fechaOrden,
                    o.estado, 
                    u.nickname AS usuario,
                    SUM(op.precioTotal) AS montoTotal
                  FROM orden o
                  JOIN cliente c ON o.cliente_idCliente = c.idCliente
                  JOIN persona pe ON pe.idPersona = c.persona_idPersona
                  JOIN usuario u ON pe.usuario_idUsuario = u.idUsuario
                  LEFT JOIN ordenProducto op ON o.idOrden = op.orden_idOrden
                  LEFT JOIN producto p ON op.producto_idProducto = p.idProducto
                  WHERE o.estado = 'Pendiente'
                  GROUP BY o.idOrden, u.nickname, o.fechaOrden, o.estado
                  LIMIT ? OFFSET ?";
    
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $limite, $offset);
    
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $ordenes = [];
    
            while ($row = $result->fetch_assoc()) {
                $ordenes[] = $row;
            }
    
            return $ordenes;
        } else {
            return [];
        }
    
        $stmt->close();
        $conexion->desconectar();
    }

    public function obtenerOrdenPorId($idOrden) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        
        $query = "SELECT 
                    o.idOrden,
                    o.fechaOrden,
                    o.cliente_idCliente,
                    op.cantidad,
                    op.precioTotal,
                    p.nombreProducto
                  FROM orden o
                  JOIN ordenProducto op ON o.idOrden = op.orden_idOrden
                  JOIN producto p ON op.producto_idProducto = p.idProducto
                  WHERE o.idOrden = ?";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $idOrden);
        
        $detalleOrden = [];
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $detalleOrden['productos'] = [];
            
            while ($row = $result->fetch_assoc()) {
                $detalleOrden['cliente_idCliente'] = $row['cliente_idCliente']; // Agregar esto
                $detalleOrden['productos'][] = [
                    'nombreProducto' => $row['nombreProducto'],
                    'cantidad' => $row['cantidad'],
                    'precioTotal' => $row['precioTotal']
                ];
            }
        } else {
            echo "Error al obtener la orden: " . $stmt->error;
        }
        
        $stmt->close();
        $conexion->desconectar();
        return $detalleOrden;
    }    

    public function crearOrden($productos) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "INSERT INTO orden (fechaOrden, cliente_idCliente, estado) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sis", $this->fechaOrden, $this->cliente_idCliente, $this->estado);
    
        if ($stmt->execute()) {
            $this->idOrden = $stmt->insert_id;
            $stmt->close();
    
            foreach ($productos as $producto) {
                $queryProducto = "INSERT INTO ordenProducto (orden_idOrden, producto_idProducto, cantidad, precioTotal) VALUES (?, ?, ?, ?)";
                $stmtProducto = $conn->prepare($queryProducto);
                $stmtProducto->bind_param("iiid", $this->idOrden, $producto['idProducto'], $producto['cantidad'], $producto['precioTotal']);
                if (!$stmtProducto->execute()) {
                    $stmtProducto->close();
                    return false;
                }
                $stmtProducto->close();
            }
            $conexion->desconectar();
            return true;
        } else {
            return false;
        }
    }
    
    public function crearEnvio($idOrden) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        
        $estadoEnvio = 'Pendiente';
        $fechaEnvio = date('Y-m-d H:i:s');
    
        $query = "INSERT INTO envio (fechaEnvio, estadoEnvio, orden_idOrden) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $fechaEnvio, $estadoEnvio, $idOrden);
    
        if ($stmt->execute()) {
            $stmt->close();
            $conexion->desconectar();
            return true;
        } else {
            echo "Error al crear el envío: " . $stmt->error;
            $stmt->close();
            $conexion->desconectar();
            return false;
        }
    }

    public function listarOrdenesEnProceso($limite, $offset) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT 
                    o.idOrden, 
                    o.fechaOrden,
                    o.estado, 
                    u.nickname AS usuario,
                    SUM(op.precioTotal) AS montoTotal
                  FROM orden o
                  JOIN cliente c ON o.cliente_idCliente = c.idCliente
                  JOIN persona pe ON pe.idPersona = c.persona_idPersona
                  JOIN usuario u ON pe.usuario_idUsuario = u.idUsuario
                  LEFT JOIN ordenProducto op ON o.idOrden = op.orden_idOrden
                  LEFT JOIN producto p ON op.producto_idProducto = p.idProducto
                  WHERE o.estado = 'En Proceso'
                  GROUP BY o.idOrden, u.nickname, o.fechaOrden, o.estado
                  LIMIT ? OFFSET ?";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $limite, $offset);
    
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $ordenes = [];
    
            while ($row = $result->fetch_assoc()) {
                $ordenes[] = $row;
            }
    
            return $ordenes;
        } else {
            return [];
        }
    
        $stmt->close();
        $conexion->desconectar();
    }

    public function listarOrdenesEntregadas($limite, $offset) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT 
                    o.idOrden, 
                    o.fechaOrden,
                    o.estado, 
                    u.nickname AS usuario,
                    SUM(op.precioTotal) AS montoTotal
                  FROM orden o
                  JOIN cliente c ON o.cliente_idCliente = c.idCliente
                  JOIN persona pe ON pe.idPersona = c.persona_idPersona
                  JOIN usuario u ON pe.usuario_idUsuario = u.idUsuario
                  LEFT JOIN ordenProducto op ON o.idOrden = op.orden_idOrden
                  LEFT JOIN producto p ON op.producto_idProducto = p.idProducto
                  WHERE o.estado = 'Entregado'
                  GROUP BY o.idOrden, u.nickname, o.fechaOrden, o.estado
                  LIMIT ? OFFSET ?";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $limite, $offset);
    
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $ordenes = [];
    
            while ($row = $result->fetch_assoc()) {
                $ordenes[] = $row;
            }
    
            return $ordenes;
        } else {
            return [];
        }
    
        $stmt->close();
        $conexion->desconectar();
    }

    public function cambiarEstado($nuevoEstado) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "UPDATE orden SET estado = ? WHERE idOrden = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $nuevoEstado, $this->idOrden);
    
        if ($stmt->execute()) {
            if ($nuevoEstado === 'Pendiente') {
                $this->crearEnvio($this->idOrden);
            }
            $stmt->close();
            $conexion->desconectar();
            return true;
        } else {
            echo "Error al cambiar el estado: " . $stmt->error;
            $stmt->close();
            $conexion->desconectar();
            return false;
        }
    }    

    function generarFactura($idOrden) {
        include 'conexion.php';
    
        try {
            $pdo->beginTransaction();
    
            $query = "SELECT SUM(p.precio * op.cantidad) AS montoTotal
                      FROM orden_producto op
                      JOIN producto p ON op.producto_idProducto = p.idProducto
                      WHERE op.orden_idOrden = :idOrden";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':idOrden', $idOrden, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$result || $result['montoTotal'] === null) {
                throw new Exception("No se pudo calcular el monto total para la orden.");
            }
    
            $montoTotal = $result['montoTotal'];
    
            $queryFactura = "INSERT INTO factura (fechaEmision, montoTotal, orden_idOrden)
                             VALUES (NOW(), :montoTotal, :idOrden)";
            $stmtFactura = $pdo->prepare($queryFactura);
            $stmtFactura->bindParam(':montoTotal', $montoTotal, PDO::PARAM_STR);
            $stmtFactura->bindParam(':idOrden', $idOrden, PDO::PARAM_INT);
            $stmtFactura->execute();
    
            $pdo->commit();
            echo "Factura generada exitosamente.";
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "Error al generar la factura: " . $e->getMessage();
        }
    }

    public function contarOrdenesPendientes() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT COUNT(*) as total FROM orden WHERE estado = 'Pendiente'";
        
        $result = $conn->query($query);
        $row = $result->fetch_assoc();
        
        return $row['total'];
    }
    

    public function contarOrdenesEnProceso() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT COUNT(*) AS total FROM orden WHERE estado = 'En proceso'";
        
        $result = $conn->query($query);
        $row = $result->fetch_assoc();
        $total = $row['total'];
        
        $conexion->desconectar();
        return $total;
    }

    public function contarOrdenesEntregadas() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT COUNT(*) AS total FROM orden WHERE estado = 'Entregado'";
        
        $result = $conn->query($query);
        $row = $result->fetch_assoc();
        $total = $row['total'];
        
        $conexion->desconectar();
        return $total;
    }

    public function cancelarPedido($idOrden) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "UPDATE orden SET estado = 'Cancelado' WHERE idOrden = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $idOrden);
    
        if ($stmt->execute()) {
            $stmt->close();
            $conexion->desconectar();
            return true;
        } else {
            echo "Error al cancelar el pedido: " . $stmt->error;
            $stmt->close();
            $conexion->desconectar();
            return false;
        }
    }
    
    public function actualizarEstadoOrden($idOrden, $nuevoEstado) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "UPDATE orden SET estado = ? WHERE idOrden = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $nuevoEstado, $idOrden);
    
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

    public function listarOrdenesCanceladas($limite, $offset) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "SELECT 
                    o.idOrden, 
                    o.fechaOrden,
                    o.estado, 
                    u.nickname AS usuario,
                    SUM(op.precioTotal) AS montoTotal
                  FROM orden o
                  JOIN cliente c ON o.cliente_idCliente = c.idCliente
                  JOIN persona pe ON pe.idPersona = c.persona_idPersona
                  JOIN usuario u ON pe.usuario_idUsuario = u.idUsuario
                  LEFT JOIN ordenProducto op ON o.idOrden = op.orden_idOrden
                  LEFT JOIN producto p ON op.producto_idProducto = p.idProducto
                  WHERE o.estado = 'Cancelado'
                  GROUP BY o.idOrden, u.nickname, o.fechaOrden, o.estado
                  LIMIT ? OFFSET ?";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $limite, $offset);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $ordenes = $resultado->fetch_all(MYSQLI_ASSOC);
    
        $stmt->close();
        $conexion->desconectar();
    
        return $ordenes;
    }
    
    /**
     * Get the value of idOrden
     */
    public function getIdOrden()
    {
        return $this->idOrden;
    }

    /**
     * Set the value of idOrden
     */
    public function setIdOrden($idOrden): self
    {
        $this->idOrden = $idOrden;

        return $this;
    }

    /**
     * Get the value of fechaOrden
     */
    public function getFechaOrden()
    {
        return $this->fechaOrden;
    }

    /**
     * Set the value of fechaOrden
     */
    public function setFechaOrden($fechaOrden): self
    {
        $this->fechaOrden = $fechaOrden;

        return $this;
    }

    /**
     * Get the value of cliente_idCliente
     */
    public function getClienteIdCliente()
    {
        return $this->cliente_idCliente;
    }

    /**
     * Set the value of cliente_idCliente
     */
    public function setClienteIdCliente($cliente_idCliente): self
    {
        $this->cliente_idCliente = $cliente_idCliente;

        return $this;
    }

    /**
     * Get the value of estado
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set the value of estado
     */
    public function setEstado($estado): self
    {
        $this->estado = $estado;

        return $this;
    }
}
?>