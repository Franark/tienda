<?php
require_once('conexion.php');
require_once('paginacion.php');

class Producto extends Paginacion{
    public $idProducto;
    public $nombreProducto;
    public $codigoBarras;
    public $precio;
    public $stock;
    public $fechaVencimiento;
    public $imagen;
    public $marca_idMarca;
    public $categoriaProducto_idCategoriaProducto;

    public function __construct($idProducto = null, $nombreProducto = null, $codigoBarras = null, $precio = null, $stock = null, $fechaVencimiento = null, $imagen = null, $marca_idMarca = null, $categoriaProducto_idCategoriaProducto = null) {
        $this->idProducto = $idProducto;
        $this->nombreProducto = $nombreProducto;
        $this->codigoBarras = $codigoBarras;
        $this->precio = $precio;
        $this->stock = $stock;
        $this->fechaVencimiento = $fechaVencimiento;
        $this->imagen = $imagen;
        $this->marca_idMarca = $marca_idMarca;
        $this->categoriaProducto_idCategoriaProducto = $categoriaProducto_idCategoriaProducto;
    }

    public function listarProductos() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();

        $this->current_page = max(1, $this->current_page);

        $offset = ($this->current_page - 1) * $this->page_size;
        
        $query = "SELECT p.idProducto, p.nombreProducto, p.codigoBarras, p.precio, p.stock, p.fechaVencimiento, p.imagen, m.nombreMarca, c.nombreCategoria 
                FROM producto p 
                JOIN marca m ON p.marca_idMarca = m.idMarca 
                JOIN categoriaProducto c ON p.categoriaProducto_idCategoriaProducto = c.idCategoriaProducto
                LIMIT $offset, $this->page_size";
        
        $result = $conn->query($query);

        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }

        $conexion->desconectar();
        return $productos;
    }

    public function cantidadProductos(){
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT COUNT(*) AS total FROM producto";
        $result = $conn->query($query);
        $row = $result->fetch_assoc();
        $conexion->desconectar();
        return $row['total'];
    }

    public function crearProducto() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "INSERT INTO producto (nombreProducto, codigoBarras, precio, stock, fechaVencimiento, imagen, marca_idMarca, categoriaProducto_idCategoriaProducto) 
                  VALUES ('$this->nombreProducto', '$this->codigoBarras', '$this->precio', '$this->stock', '$this->fechaVencimiento', '$this->imagen', '$this->marca_idMarca', '$this->categoriaProducto_idCategoriaProducto')";
    
        if ($conn->query($query) === TRUE) {
            $conexion->desconectar();
            return true;
        } else {
            echo "Error: " . $conn->error;
            $conexion->desconectar();
            return false;
        }
    }
    

    public function actualizarProducto() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "UPDATE producto SET nombreProducto='$this->nombreProducto', codigoBarras='$this->codigoBarras', precio='$this->precio', stock='$this->stock', fechaVencimiento='$this->fechaVencimiento', imagen='$this->imagen', marca_idMarca='$this->marca_idMarca', categoriaProducto_idCategoriaProducto='$this->categoriaProducto_idCategoriaProducto' 
                  WHERE idProducto='$this->idProducto'";
        return $conexion->insertar($query);
    }

    public function eliminarProducto($idProducto) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "DELETE FROM producto WHERE idProducto='$idProducto'";
        
        return $conexion->insertar($query);
    }
    
    public function obtenerProductoPorId($id) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT * FROM producto WHERE idProducto = '$id'";
        $result = $conn->query($query);

        $producto = null;
        if ($result->num_rows > 0) {
            $producto = $result->fetch_assoc();
        }

        $conexion->desconectar();
        return $producto;
    }

    public function buscarProductos($nombreProducto = '', $codigoBarras = '', $marca_idMarca = '', $categoriaProducto_idCategoriaProducto = '', $current_page = 1, $page_size = 10) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $offset = ($current_page - 1) * $page_size;
    
        $query = "SELECT p.idProducto, p.nombreProducto, p.codigoBarras, p.precio, p.stock, p.fechaVencimiento, p.imagen, m.nombreMarca, c.nombreCategoria 
                  FROM producto p 
                  JOIN marca m ON p.marca_idMarca = m.idMarca 
                  JOIN categoriaProducto c ON p.categoriaProducto_idCategoriaProducto = c.idCategoriaProducto 
                  WHERE 1=1";
    
        if ($nombreProducto !== '') {
            $query .= " AND p.nombreProducto LIKE '%$nombreProducto%'";
        }
        if ($codigoBarras !== '') {
            $query .= " AND p.codigoBarras LIKE '%$codigoBarras%'";
        }
        if ($marca_idMarca !== '') {
            $query .= " AND p.marca_idMarca = $marca_idMarca";
        }
        if ($categoriaProducto_idCategoriaProducto !== '') {
            $query .= " AND p.categoriaProducto_idCategoriaProducto = $categoriaProducto_idCategoriaProducto";
        }
        
    
        $query .= " LIMIT $offset, $page_size";
    
        $result = $conn->query($query);
        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }
    
        $conexion->desconectar();
        return $productos;
    }
    
    public function contarProductos($nombreProducto = '', $codigoBarras = '', $marca_idMarca = '', $categoriaProducto_idCategoriaProducto = '') {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "SELECT COUNT(*) as total 
                  FROM producto p 
                  JOIN marca m ON p.marca_idMarca = m.idMarca 
                  JOIN categoriaProducto c ON p.categoriaProducto_idCategoriaProducto = c.idCategoriaProducto 
                  WHERE 1=1";
    
        if ($nombreProducto !== '') {
            $query .= " AND p.nombreProducto LIKE '%$nombreProducto%'";
        }
        if ($codigoBarras !== '') {
            $query .= " AND p.codigoBarras LIKE '%$codigoBarras%'";
        }
        if ($marca_idMarca !== '') {
            $query .= " AND p.marca_idMarca = $marca_idMarca";
        }
        if ($categoriaProducto_idCategoriaProducto !== '') {
            $query .= " AND p.categoriaProducto_idCategoriaProducto = $categoriaProducto_idCategoriaProducto";
        }        
    
        $result = $conn->query($query);
        $row = $result->fetch_assoc();
        $conexion->desconectar();
        return $row['total'];
    }

    public function actualizarPreciosParaTodos($porcentaje){
        $conexion= new Conexion();
        $conn=$conexion->conectar();
        $query="UPDATE producto SET precio=precio*($porcentaje+1)";
        return $conexion->insertar($query);
    }

    public function actualizarPreciosPorMarca($marca_id, $porcentaje) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "UPDATE producto SET precio=precio*($porcentaje+1) WHERE marca_idMarca=$marca_id";
        return $conexion->insertar($query);
    }

    public function actualizarPreciosPorCategoria($categoria_id, $porcentaje) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "UPDATE producto SET precio=precio*($porcentaje+1) WHERE categoriaProducto_idCategoriaProducto=$categoria_id";
        return $conexion->insertar($query);
    }

    public function actualizarStock($idProducto, $cantidad) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT stock FROM producto WHERE idProducto = '$idProducto'";
        $result = $conn->query($query);
        $producto = $result->fetch_assoc();
        $stockActual = $producto['stock'];
        $nuevoStock = $stockActual + $cantidad;
        $query = "UPDATE producto SET stock = $nuevoStock WHERE idProducto = '$idProducto'";
        $conn->query($query);
    
        $conexion->desconectar();
    }

    public function existeCodigoBarras($codigoBarras) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT COUNT(*) as total FROM producto WHERE codigoBarras = '$codigoBarras'";
        $result = $conn->query($query);
        $row = $result->fetch_assoc();

        return $row['total'] > 0;
    }

    public function existeNombreProducto($nombreProducto) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT COUNT(*) as total FROM producto WHERE nombreProducto = '$nombreProducto'";
        $result = $conn->query($query);
        $row = $result->fetch_assoc();

        return $row['total'] > 0;
    }

    public function obtenerProductosSinStock() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT * FROM producto WHERE stock <= 0";
        $result = $conn->query($query);
    
        $productosSinStock = [];
        while ($row = $result->fetch_assoc()) {
            $productosSinStock[] = $row;
        }
    
        $conexion->desconectar();
        return $productosSinStock;
    }

    public function productosPorVencer(){
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT nombreProducto, DATEDIFF(fechaVencimiento, NOW()) AS fechaVencimiento
                  FROM producto
                  WHERE fechaVencimiento BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 5 DAY)
                  ORDER BY fechaVencimiento";
        $result = $conn->query($query);
        $productosPorVencer = [];
        while ($row = $result->fetch_assoc()) {
            $productosPorVencer[] = $row;
        }
        $conexion->desconectar();
        return $productosPorVencer;
    }

    public function productosVendidosPorMes(){
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query= "SELECT 
                p.nombreProducto, 
                SUM(op.cantidad) AS total_vendido, 
                MONTH(o.fechaOrden) AS mes, 
                YEAR(o.fechaOrden) AS año
                FROM 
                    ordenProducto op
                JOIN 
                    producto p ON op.producto_idProducto = p.idProducto
                JOIN 
                    orden o ON op.orden_idOrden = o.idOrden
                GROUP BY 
                    p.nombreProducto, mes, año
                ORDER BY 
                    año DESC, mes DESC, total_vendido DESC";
        $result = $conn->query($query);
        $productosVendidosPorMes = [];
        while ($row = $result->fetch_assoc()) {
            $productosVendidosPorMes[] = $row;
        }
        $conexion->desconectar();
        return $productosVendidosPorMes;
    }

    public function stockProuductos(){
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query= "SELECT 
                nombreProducto, 
                stock
                FROM 
                    producto p
                ORDER BY 
                    stock DESC";
        $result = $conn->query($query);
        $stockProuductos = [];
        while ($row = $result->fetch_assoc()) {
            $stockProuductos[] = $row;
        }
        $conexion->desconectar();
        return $stockProuductos;
    }

    /**
     * Get the value of idProducto
     */ 
    public function getIdProducto()
    {
        return $this->idProducto;
    }

    /**
     * Set the value of idProducto
     *
     * @return  self
     */ 
    public function setIdProducto($idProducto)
    {
        $this->idProducto = $idProducto;

        return $this;
    }

    /**
     * Get the value of nombreProducto
     */ 
    public function getNombreProducto()
    {
        return $this->nombreProducto;
    }

    /**
     * Set the value of nombreProducto
     *
     * @return  self
     */ 
    public function setNombreProducto($nombreProducto)
    {
        $this->nombreProducto = $nombreProducto;

        return $this;
    }

    /**
     * Get the value of codigoBarra
     */ 
    public function getCodigoBarra()
    {
        return $this->codigoBarras;
    }

    /**
     * Set the value of codigoBarra
     *
     * @return  self
     */ 
    public function setCodigoBarra($codigoBarras)
    {
        $this->codigoBarras = $codigoBarras;

        return $this;
    }

    /**
     * Get the value of precio
     */ 
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set the value of precio
     *
     * @return  self
     */ 
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get the value of stock
     */ 
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Set the value of stock
     *
     * @return  self
     */ 
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get the value of fechaVencimiento
     */ 
    public function getFechaVencimiento()
    {
        return $this->fechaVencimiento;
    }

    /**
     * Set the value of fechaVencimiento
     *
     * @return  self
     */ 
    public function setFechaVencimiento($fechaVencimiento)
    {
        $this->fechaVencimiento = $fechaVencimiento;

        return $this;
    }

    /**
     * Get the value of imagen
     */ 
    public function getImagen()
    {
        return $this->imagen;
    }

    /**
     * Set the value of imagen
     *
     * @return  self
     */ 
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * Get the value of marca_idMarca
     */ 
    public function getMarca_idMarca()
    {
        return $this->marca_idMarca;
    }

    /**
     * Set the value of marca_idMarca
     *
     * @return  self
     */ 
    public function setMarca_idMarca($marca_idMarca)
    {
        $this->marca_idMarca = $marca_idMarca;

        return $this;
    }

    /**
     * Get the value of categoriaProducto_idCategoriaProducto
     */ 
    public function getCategoriaProducto_idCategoriaProducto()
    {
        return $this->categoriaProducto_idCategoriaProducto;
    }

    /**
     * Set the value of categoriaProducto_idCategoriaProducto
     *
     * @return  self
     */ 
    public function setCategoriaProducto_idCategoriaProducto($categoriaProducto_idCategoriaProducto)
    {
        $this->categoriaProducto_idCategoriaProducto = $categoriaProducto_idCategoriaProducto;

        return $this;
    }
}
?>
