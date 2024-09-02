<?php
require_once('conexion.php');
require_once('paginacion.php');

class Producto extends Paginacion{
    public $idProducto;
    public $nombreProducto;
    public $codigoBarra;
    public $precio;
    public $stock;
    public $fechaVencimiento;
    public $imagen;
    public $marca_idMarca;
    public $categoriaProducto_idCategoriaProducto;

    public function __construct($idProducto = null, $nombreProducto = null, $codigoBarra = null, $precio = null, $stock = null, $fechaVencimiento = null, $imagen = null, $marca_idMarca = null, $categoriaProducto_idCategoriaProducto = null) {
        $this->idProducto = $idProducto;
        $this->nombreProducto = $nombreProducto;
        $this->codigoBarra = $codigoBarra;
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
                  VALUES ('$this->nombreProducto', '$this->codigoBarra', '$this->precio', '$this->stock', '$this->fechaVencimiento', '$this->imagen', '$this->marca_idMarca', '$this->categoriaProducto_idCategoriaProducto')";
    
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
        $query = "UPDATE producto SET nombreProducto='$this->nombreProducto', codigoBarras='$this->codigoBarra', precio='$this->precio', stock='$this->stock', fechaVencimiento='$this->fechaVencimiento', imagen='$this->imagen', marca_idMarca='$this->marca_idMarca', categoriaProducto_idCategoriaProducto='$this->categoriaProducto_idCategoriaProducto' 
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

    public function buscarProductos($nombreProducto = '', $codigoBarra = '', $marca_idMarca = '', $categoriaProducto_idCategoriaProducto = '', $current_page = 1, $page_size = 10) {
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
        if ($codigoBarra !== '') {
            $query .= " AND p.codigoBarras LIKE '%$codigoBarra%'";
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
    
    public function contarProductos($nombreProducto = '', $codigoBarra = '', $marca_idMarca = '', $categoriaProducto_idCategoriaProducto = '') {
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
        if ($codigoBarra !== '') {
            $query .= " AND p.codigoBarras LIKE '%$codigoBarra%'";
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
        return $this->codigoBarra;
    }

    /**
     * Set the value of codigoBarra
     *
     * @return  self
     */ 
    public function setCodigoBarra($codigoBarra)
    {
        $this->codigoBarra = $codigoBarra;

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
