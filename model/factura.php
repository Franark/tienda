<?php
require_once('conexion.php');

class Factura {
    private $idFactura;
    private $fechaEmision;
    private $montoTotal;
    private $orden_idOrden;

    public function __construct($idFactura = null, $fechaEmision = null, $montoTotal = null, $orden_idOrden = null) {
        $this->idFactura = $idFactura;
        $this->fechaEmision = $fechaEmision;
        $this->montoTotal = $montoTotal;
        $this->orden_idOrden = $orden_idOrden;
    }
    public function crearFactura() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "INSERT INTO factura (fechaEmision, montoTotal, orden_idOrden) VALUES ('$this->fechaEmision', '$this->montoTotal', '$this->orden_idOrden')";
    
        if ($conn->query($query) === TRUE) {
            $this->idFactura = $conn->insert_id;
            $conexion->desconectar();
            return true;
        } else {
            $conexion->desconectar();
            return false;
        }
    }
    
    public function obtenerFacturaPorId($idFactura) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();

        $sql = "SELECT * FROM factura WHERE idFactura = '$this->idFactura'";
        $resultado = $conn->query($sql);
        $conexion->desconectar();

        if ($resultado->num_rows > 0) {
            return $resultado->fetch_assoc();
        } else {
            return null;
        }
    }

    /**
     * Get the value of idFactura
     */
    public function getIdFactura()
    {
        return $this->idFactura;
    }

    /**
     * Set the value of idFactura
     */
    public function setIdFactura($idFactura): self
    {
        $this->idFactura = $idFactura;

        return $this;
    }

    /**
     * Get the value of fechaEmision
     */
    public function getFechaEmision()
    {
        return $this->fechaEmision;
    }

    /**
     * Set the value of fechaEmision
     */
    public function setFechaEmision($fechaEmision): self
    {
        $this->fechaEmision = $fechaEmision;

        return $this;
    }

    /**
     * Get the value of montoTotal
     */
    public function getMontoTotal()
    {
        return $this->montoTotal;
    }

    /**
     * Set the value of montoTotal
     */
    public function setMontoTotal($montoTotal): self
    {
        $this->montoTotal = $montoTotal;

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
