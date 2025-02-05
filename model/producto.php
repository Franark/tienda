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
    
        $query = "SELECT 
            p.idProducto, 
            p.nombreProducto, 
            p.codigoBarras, 
            p.precio, 
            p.stock AS stockActual, 
            p.fechaVencimiento,
            p.activo,
            m.nombreMarca, 
            c.nombreCategoria,
            MIN(pi.imagen) AS imagenPrincipal
        FROM producto p
        JOIN marca m ON p.marca_idMarca = m.idMarca
        JOIN categoriaProducto c ON p.categoriaProducto_idCategoriaProducto = c.idCategoriaProducto
        LEFT JOIN producto_imagenes pi ON p.idProducto = pi.producto_idProducto
        GROUP BY p.idProducto, p.nombreProducto, p.codigoBarras, p.precio, p.stock, p.fechaVencimiento, p.activo, m.nombreMarca, c.nombreCategoria
        LIMIT $offset, $this->page_size;";


        $result = $conn->query($query);
    
        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $row['imagenes'] = [$row['imagenPrincipal'] ?? 'default-image.jpg'];
            $productos[] = $row;
        }
    
        $conexion->desconectar();
        return $productos;
    }
    
    public function listarProductosClientes() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $this->current_page = max(1, $this->current_page);
        $offset = ($this->current_page - 1) * $this->page_size;
    
        $query = "SELECT 
                p.idProducto, 
                p.nombreProducto, 
                p.codigoBarras, 
                p.precio, 
                p.stock AS stockActual, 
                p.fechaVencimiento, 
                p.activo, 
                m.nombreMarca, 
                c.nombreCategoria,
                MIN(pi.imagen) AS imagenPrincipal
            FROM producto p
            JOIN marca m ON p.marca_idMarca = m.idMarca
            JOIN categoriaProducto c ON p.categoriaProducto_idCategoriaProducto = c.idCategoriaProducto
            LEFT JOIN producto_imagenes pi ON p.idProducto = pi.producto_idProducto
            WHERE p.activo = 1 AND (p.stock > 0 OR p.stock IS NULL)
            GROUP BY p.idProducto
            LIMIT $offset, $this->page_size;";
    
        $result = $conn->query($query);
    
        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $row['imagenes'] = [$row['imagenPrincipal'] ?? 'default-image.jpg'];
            $productos[] = $row;
        }
    
        $conexion->desconectar();
        return $productos;
    }    

    public function cantidadProductos() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "SELECT COUNT(*) as total
                  FROM producto p
                  WHERE p.activo = 1 AND (p.stock > 0 OR p.stock IS NULL)";
    
        $result = $conn->query($query);
        $row = $result->fetch_assoc();
    
        $conexion->desconectar();
        return $row['total'];
    }

    public function crearProducto() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "INSERT INTO producto (nombreProducto, codigoBarras, precio, stock, fechaVencimiento, marca_idMarca, categoriaProducto_idCategoriaProducto) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
    
        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            "ssdissi",
            $this->nombreProducto,
            $this->codigoBarras,
            $this->precio,
            $this->stock,
            $this->fechaVencimiento,
            $this->marca_idMarca,
            $this->categoriaProducto_idCategoriaProducto
        );
    
        if ($stmt->execute()) {
            $this->idProducto = $stmt->insert_id;
            $stmt->close();
            $conexion->desconectar();
            return true;
        } else {
            error_log("Error en crearProducto: " . $stmt->error);
            $stmt->close();
            $conexion->desconectar();
            return false;
        }
    }    

    public function guardarImagenProducto($productoId, $imagen) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "INSERT INTO producto_imagenes (producto_idProducto, imagen) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $productoId, $imagen);
    
        $resultado = $stmt->execute();
    
        if (!$resultado) {
            error_log("Error al guardar imagen: " . $stmt->error);
        }
    
        $stmt->close();
        $conexion->desconectar();
        return $resultado;
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
        
        $query = "UPDATE producto SET activo = 0 WHERE idProducto = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $idProducto);
    
        $resultado = $stmt->execute();
        $conexion->desconectar();
        return $resultado;
    }     
    
    public function obtenerProductoPorId($id) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "SELECT * FROM producto WHERE idProducto = '$id'";
        $result = $conn->query($query);
    
        $producto = null;
        if ($result->num_rows > 0) {
            $producto = $result->fetch_assoc();

            $query_imagenes = "SELECT imagen FROM producto_imagenes WHERE producto_idProducto = '$id' ORDER BY idImagen DESC";
            $result_imagenes = $conn->query($query_imagenes);
            $producto['imagenes'] = [];
    
            while ($imagen = $result_imagenes->fetch_assoc()) {
                $producto['imagenes'][] = $imagen['imagen'];
            }
        }
    
        $conexion->desconectar();
        return $producto;
    }
    
    


    public function obtenerImagenesPorProducto($idProducto) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT idImagen, imagen FROM producto_imagenes WHERE producto_idProducto = '$idProducto'";
        $result = $conn->query($query);
        $imagenes = [];
        while ($row = $result->fetch_assoc()) {
            $imagenes[] = $row;
        }
        $conexion->desconectar();
        return $imagenes;
    }    

    public function eliminarImagenDeBaseDatos($nombreImagen) {
        $conexion = new Conexion();
        $this->conexion = $conexion->conectar();
        $sql = "DELETE FROM producto_imagenes WHERE imagen = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $nombreImagen);
        if ($stmt->execute()) {
            return $stmt->affected_rows > 0;
        }
        return false;
    }

    public function obtenerImagenPorId($idImagen){
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT idImagen, imagen FROM producto_imagenes WHERE idImagen = '$idImagen'";
        $result = $conn->query($query);

        $imagen = null;
        if ($result->num_rows > 0) {
            $imagen = $result->fetch_assoc();
        }

        $conexion->desconectar();
        return $imagen;
    }

    public function buscarProductos($termino, $filtro = '', $orden = '', $direccion = 'ASC') {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $this->current_page = max(1, $this->current_page);
        $offset = ($this->current_page - 1) * $this->page_size;
    
        $termino = $conn->real_escape_string($termino);
        $filtro = $conn->real_escape_string($filtro);
        $orden = $conn->real_escape_string($orden);
        $direccion = strtoupper($direccion) === 'DESC' ? 'DESC' : 'ASC';
    
        $query = "SELECT 
            p.idProducto, 
            p.nombreProducto, 
            p.codigoBarras, 
            p.precio, 
            p.stock AS stockActual, 
            p.fechaVencimiento,
            p.activo,
            m.nombreMarca, 
            c.nombreCategoria,
            MIN(pi.imagen) AS imagenPrincipal
        FROM producto p
        JOIN marca m ON p.marca_idMarca = m.idMarca
        JOIN categoriaProducto c ON p.categoriaProducto_idCategoriaProducto = c.idCategoriaProducto
        LEFT JOIN producto_imagenes pi ON p.idProducto = pi.producto_idProducto
        WHERE p.nombreProducto LIKE '%$termino%'";
    
        if (!empty($filtro)) {
            $query .= " AND $filtro";
        }
    
        if (!empty($orden)) {
            $query .= " ORDER BY $orden $direccion";
        }
    
        $query .= " GROUP BY p.idProducto, p.nombreProducto, p.codigoBarras, p.precio, p.stock, p.fechaVencimiento, p.activo, m.nombreMarca, c.nombreCategoria
                     LIMIT $offset, $this->page_size";
    
        $result = $conn->query($query);
    
        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $row['imagenes'] = [$row['imagenPrincipal'] ?? 'default-image.jpg'];
            $productos[] = $row;
        }
    
        $conexion->desconectar();
        return $productos;
    }
    
    public function obtenerProductos($filtro = '', $orden = 'ASC', $limite = 10, $pagina = 1) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $offset = ($pagina - 1) * $limite;
    
        $query = "
            SELECT p.idProducto, p.nombreProducto, p.codigoBarras, p.precio, p.stock AS stockActual, p.fechaVencimiento, p.imagen,
                   m.nombreMarca, c.nombreCategoria
            FROM producto p
            JOIN marca m ON p.marca_idMarca = m.idMarca
            JOIN categoriaProducto c ON p.categoriaProducto_idCategoriaProducto = c.idCategoriaProducto
            LEFT JOIN producto_imagenes pi ON p.idProducto = pi.producto_idProducto
            WHERE 1=1";
        switch ($filtro) {
            case 'sin_stock':
                $query .= " AND p.stock = 0";
                break;
            case 'por_vencer':
                $query .= " AND p.fechaVencimiento IS NOT NULL ORDER BY p.fechaVencimiento ASC";
                break;
            case 'precio':
                $query .= " ORDER BY p.precio $orden";
                break;
            case 'stock':
                $query .= " ORDER BY p.stock $orden";
                break;
            case 'fecha_vencimiento':
                $query .= " WHERE p.fechaVencimiento IS NOT NULL ORDER BY p.fechaVencimiento $orden";
                break;
            case 'inactivos':
                $query .= " AND p.estado = 'inactivo'";
                break;
        }
    
        $query .= " LIMIT $offset, $limite";
    
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

    public function actualizarPreciosParaTodos($porcentaje) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        
        $query = "UPDATE producto SET precio = precio * (? + 1)";
        $stmt = $conn->prepare($query);
        
        $stmt->bind_param("d", $porcentaje);
        
        $resultado = $stmt->execute();
        if (!$resultado) {
            error_log("Error en la consulta SQL: " . $stmt->error);
        }
        
        $stmt->close();
        return $resultado;
    }
    

    public function actualizarPreciosPorMarca($marca_id, $porcentaje) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        
        $query = "UPDATE producto SET precio = precio * (? + 1) WHERE marca_idMarca = ?";
        $stmt = $conn->prepare($query);
        
        $stmt->bind_param("di", $porcentaje, $marca_id);
        
        $resultado = $stmt->execute();
        if (!$resultado) {
            error_log("Error en la consulta SQL: " . $stmt->error);
        }
        
        $stmt->close();
        return $resultado;
    }
    

    public function actualizarPreciosPorCategoria($categoria_id, $porcentaje) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        
        $query = "UPDATE producto SET precio = precio * (? + 1) WHERE categoriaProducto_idCategoriaProducto = ?";
        $stmt = $conn->prepare($query);
        
        $stmt->bind_param("di", $porcentaje, $categoria_id);
        
        $resultado = $stmt->execute();
        if (!$resultado) {
            error_log("Error en la consulta SQL: " . $stmt->error);
        }
        
        $stmt->close();
        return $resultado;
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

    public function insertarNotificacion($idProducto, $mensaje, $tipoNotificacion) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        
        $queryCheck = "SELECT idNotificacion 
                       FROM notificaciones 
                       WHERE idProducto = ? AND tipoNotificacion = ? AND mensaje = ?";
        $stmtCheck = $conn->prepare($queryCheck);
        $stmtCheck->bind_param("iss", $idProducto, $tipoNotificacion, $mensaje);
        $stmtCheck->execute();
        $stmtCheck->store_result();
        
        if ($stmtCheck->num_rows > 0) {
            $stmtCheck->close();
            $conexion->desconectar();
            return;
        }
        $stmtCheck->close();
        
        $query = "INSERT INTO notificaciones (idProducto, mensaje, tipoNotificacion) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iss", $idProducto, $mensaje, $tipoNotificacion);
        $stmt->execute();
        
        $stmt->close();
        $conexion->desconectar();
    }
    
    
    
    public function eliminarNotificacionesAntiguas() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "DELETE FROM notificaciones WHERE fechaCreacion < DATE_SUB(NOW(), INTERVAL 30 DAY) OR leido = 1";
        $conn->query($query);
        $conexion->desconectar();
    }
    
    
    public function productosPorVencer() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "SELECT 
                    p.idProducto, 
                    p.nombreProducto, 
                    p.codigoBarras, 
                    p.precio, 
                    p.stock AS stockActual, 
                    p.fechaVencimiento, 
                    DATEDIFF(p.fechaVencimiento, CURDATE()) AS diasParaVencimiento,
                    p.activo,
                    m.nombreMarca, 
                    c.nombreCategoria,
                    MIN(pi.imagen) AS imagenPrincipal
                FROM producto p
                JOIN marca m ON p.marca_idMarca = m.idMarca
                JOIN categoriaProducto c ON p.categoriaProducto_idCategoriaProducto = c.idCategoriaProducto
                LEFT JOIN producto_imagenes pi ON p.idProducto = pi.producto_idProducto
                WHERE p.fechaVencimiento IS NOT NULL
                GROUP BY p.idProducto, p.nombreProducto, p.codigoBarras, p.precio, p.stock, p.fechaVencimiento, p.activo, m.nombreMarca, c.nombreCategoria
                HAVING diasParaVencimiento > 0
                ORDER BY fechaVencimiento ASC";
        $result = $conn->query($query);
        
        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $row['imagenes'] = [$row['imagenPrincipal'] ?? 'default-image.jpg'];
            $productos[] = $row;
        }
        $conexion->desconectar();
        return $productos;
    }
    
    public function obtenerNotificaciones() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT mensaje, tipoNotificacion, fechaCreacion, leido 
                  FROM notificaciones 
                  ORDER BY fechaCreacion DESC";
        $result = $conn->query($query);
    
        $notificaciones = [];
        while ($row = $result->fetch_assoc()) {
            $notificaciones[] = $row;
        }
    
        $conexion->desconectar();
        return $notificaciones;
    }

    public function obtenerTodasLasNotificaciones() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT mensaje, tipoNotificacion, fechaCreacion, leido 
                  FROM notificaciones 
                  ORDER BY fechaCreacion DESC";
        $result = $conn->query($query);
    
        $notificaciones = [];
        while ($row = $result->fetch_assoc()) {
            $notificaciones[] = $row;
        }
    
        $conexion->desconectar();
        return $notificaciones;
    }
    

    public function listarNotificacionesProductos($current_page, $page_size) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $offset = ($current_page - 1) * $page_size;
    
        $query = "SELECT mensaje, tipoNotificacion, fechaCreacion, leido
                  FROM notificaciones
                  ORDER BY fechaCreacion DESC
                  LIMIT $offset, $page_size";
        
        $result = $conn->query($query);
    
        $notificaciones = [];
        while ($row = $result->fetch_assoc()) {
            $notificaciones[] = $row;
        }
    
        $conexion->desconectar();
        return $notificaciones;
    }
    
    public function contarNotificaciones() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "SELECT COUNT(*) AS total FROM notificaciones";
        $result = $conn->query($query);
        
        $row = $result->fetch_assoc();
        $total_notificaciones = $row['total'];
    
        $conexion->desconectar();
        return $total_notificaciones;
    }
    

    public function productosVendidosPorMes($fecha_inicio = null, $fecha_fin = null){
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $whereFecha = '';
        if ($fecha_inicio && $fecha_fin) {
            $whereFecha = "AND o.fechaOrden BETWEEN '$fecha_inicio' AND '$fecha_fin'";
        }
    
        $query = "SELECT 
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
                  WHERE 
                    1=1 $whereFecha
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

    public function listarProductosPorPrecio($orden = 'ASC') {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        
        $query = "SELECT 
                    p.idProducto, 
                    p.nombreProducto, 
                    p.codigoBarras, 
                    p.precio, 
                    p.stock AS stockActual, 
                    p.fechaVencimiento, 
                    p.activo,
                    m.nombreMarca, 
                    c.nombreCategoria,
                    MIN(pi.imagen) AS imagenPrincipal
                  FROM producto p
                  JOIN marca m ON p.marca_idMarca = m.idMarca
                  JOIN categoriaProducto c ON p.categoriaProducto_idCategoriaProducto = c.idCategoriaProducto
                  LEFT JOIN producto_imagenes pi ON p.idProducto = pi.producto_idProducto
                  GROUP BY p.idProducto, p.nombreProducto, p.codigoBarras, p.precio, p.stock, p.fechaVencimiento, p.activo, m.nombreMarca, c.nombreCategoria
                  ORDER BY p.precio $orden";
        
        $result = $conn->query($query);
        $productos = [];
        
        while ($row = $result->fetch_assoc()) {
            $row['imagenes'] = [$row['imagenPrincipal'] ?? 'default-image.jpg'];
            $productos[] = $row;
        }
        
        $conexion->desconectar();
        return $productos;
    }
    
    public function obtenerProductosSinStock() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "SELECT 
                    p.idProducto, 
                    p.nombreProducto, 
                    p.codigoBarras, 
                    p.precio, 
                    p.stock AS stockActual, 
                    p.fechaVencimiento, 
                    p.activo,
                    m.nombreMarca, 
                    c.nombreCategoria,
                    MIN(pi.imagen) AS imagenPrincipal
                  FROM producto p
                  JOIN marca m ON p.marca_idMarca = m.idMarca
                  JOIN categoriaProducto c ON p.categoriaProducto_idCategoriaProducto = c.idCategoriaProducto
                  LEFT JOIN producto_imagenes pi ON p.idProducto = pi.producto_idProducto
                  WHERE p.stock = 0
                  GROUP BY p.idProducto, p.nombreProducto, p.codigoBarras, p.precio, p.stock, p.fechaVencimiento, p.activo, m.nombreMarca, c.nombreCategoria";
    
        $result = $conn->query($query);
    
        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $row['imagenes'] = [$row['imagenPrincipal'] ?? 'default-image.jpg'];
            $productos[] = $row;
        }
    
        $conexion->desconectar();
        return $productos;
    }
    
    
    
    public function actualizarPreciosPorFechaVencimiento($porcentaje) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        
        $query = "UPDATE producto 
                  SET precio = precio * ($porcentaje + 1)
                  WHERE fechaVencimiento IS NOT NULL AND DATEDIFF(fechaVencimiento, CURDATE()) < 30"; // Productos que vencen en menos de 30 días
        
        return $conexion->insertar($query);
    }

    public function listarProductosPorFechaVencimiento($order = 'asc') {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "SELECT 
                    p.idProducto, 
                    p.nombreProducto, 
                    p.codigoBarras, 
                    p.precio, 
                    p.stock AS stockActual, 
                    p.fechaVencimiento, 
                    p.activo,
                    m.nombreMarca, 
                    c.nombreCategoria,
                    MIN(pi.imagen) AS imagenPrincipal
                  FROM producto p
                  JOIN marca m ON p.marca_idMarca = m.idMarca
                  JOIN categoriaProducto c ON p.categoriaProducto_idCategoriaProducto = c.idCategoriaProducto
                  LEFT JOIN producto_imagenes pi ON p.idProducto = pi.producto_idProducto
                  WHERE fechaVencimiento IS NOT NULL
                  GROUP BY p.idProducto, p.nombreProducto, p.codigoBarras, p.precio, p.stock, p.fechaVencimiento, p.activo, m.nombreMarca, c.nombreCategoria
                  ORDER BY fechaVencimiento $order"; 
    
        $result = $conn->query($query);
        $productos = [];
    
        while ($row = $result->fetch_assoc()) {
            $row['imagenes'] = [$row['imagenPrincipal'] ?? 'default-image.jpg'];
            $productos[] = $row;
        }
    
        $conexion->desconectar();
        return $productos;
    }
    
    public function listarProductosPorCategoria($categoria_id) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        
        $query = "SELECT p.idProducto, p.nombreProducto, p.codigoBarras, p.precio, p.stock, p.fechaVencimiento, p.imagen, 
                         m.nombreMarca, c.nombreCategoria
                  FROM producto p
                  JOIN marca m ON p.marca_idMarca = m.idMarca
                  JOIN categoriaProducto c ON p.categoriaProducto_idCategoriaProducto = c.idCategoriaProducto
                  WHERE p.categoriaProducto_idCategoriaProducto = $categoria_id";
        
        $result = $conn->query($query);
        $productos = [];
        
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }
        
        $conexion->desconectar();
        return $productos;
    }
    
    public function listarProductosPorStock($order = 'asc') {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "SELECT 
                p.idProducto, 
                p.nombreProducto, 
                p.codigoBarras, 
                p.precio, 
                p.stock AS stockActual, 
                p.fechaVencimiento, 
                p.activo,
                m.nombreMarca, 
                c.nombreCategoria,
                MIN(pi.imagen) AS imagenPrincipal
            FROM producto p
            JOIN marca m ON p.marca_idMarca = m.idMarca
            JOIN categoriaProducto c ON p.categoriaProducto_idCategoriaProducto = c.idCategoriaProducto
            LEFT JOIN producto_imagenes pi ON p.idProducto = pi.producto_idProducto
            GROUP BY p.idProducto, p.nombreProducto, p.codigoBarras, p.precio, p.stock, p.fechaVencimiento, p.activo, m.nombreMarca, c.nombreCategoria
            ORDER BY stockActual $order";
    
        $result = $conn->query($query);
        $productos = [];
    
        while ($row = $result->fetch_assoc()) {
            $row['imagenes'] = [$row['imagenPrincipal'] ?? 'default-image.jpg'];
            $productos[] = $row;
        }
    
        $conexion->desconectar();
        return $productos;
    }
    

    public function listarProductosOrdenados($criterio, $orden) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $this->current_page = max(1, $this->current_page);
        $offset = ($this->current_page - 1) * $this->page_size;
    
        $criteriosValidos = ['precio', 'stock', 'fechaVencimiento'];
        $criterio = in_array($criterio, $criteriosValidos) ? $criterio : 'idProducto';
        $orden = strtolower($orden) === 'desc' ? 'DESC' : 'ASC';
    
        $query = "SELECT 
            p.idProducto, 
            p.nombreProducto, 
            p.codigoBarras, 
            p.precio, 
            p.stock AS stockActual, 
            p.fechaVencimiento, 
            p.imagen, 
            p.activo,
            m.nombreMarca, 
            c.nombreCategoria
        FROM producto p
        JOIN marca m ON p.marca_idMarca = m.idMarca
        JOIN categoriaProducto c ON p.categoriaProducto_idCategoriaProducto = c.idCategoriaProducto
        ORDER BY $criterio $orden
        LIMIT $offset, $this->page_size;";
    
        $result = $conn->query($query);
    
        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }
    
        $conexion->desconectar();
        return $productos;
    }
    

    public function usuariosConMasCompras($fecha_inicio = null, $fecha_fin = null){
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $whereFecha = '';
        if ($fecha_inicio && $fecha_fin) {
            $whereFecha = "AND o.fechaOrden BETWEEN '$fecha_inicio' AND '$fecha_fin'";
        }
    
        $query = "SELECT 
                    c.idCliente, 
                    CONCAT(p.nombrePersona, ' ', p.apellidoPersona) AS nombre_cliente, 
                    COUNT(o.idOrden) AS total_compras
                  FROM 
                    orden o
                  JOIN 
                    cliente c ON o.cliente_idCliente = c.idCliente
                  JOIN 
                    persona p ON c.persona_idPersona = p.idPersona
                  WHERE 
                    1=1 $whereFecha
                  GROUP BY 
                    c.idCliente, nombre_cliente
                  ORDER BY 
                    total_compras DESC";
    
        $result = $conn->query($query);
        $usuariosConMasCompras = [];
        while ($row = $result->fetch_assoc()) {
            $usuariosConMasCompras[] = $row;
        }
    
        $conexion->desconectar();
        return $usuariosConMasCompras;
    }

    public function listarProductosInactivos() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "SELECT 
                    p.idProducto, 
                    p.nombreProducto, 
                    p.codigoBarras, 
                    p.precio, 
                    p.stock AS stockActual, 
                    p.fechaVencimiento, 
                    p.activo,
                    m.nombreMarca, 
                    c.nombreCategoria,
                    MIN(pi.imagen) AS imagenPrincipal
                  FROM producto p
                  JOIN marca m ON p.marca_idMarca = m.idMarca
                  JOIN categoriaProducto c ON p.categoriaProducto_idCategoriaProducto = c.idCategoriaProducto
                  LEFT JOIN producto_imagenes pi ON p.idProducto = pi.producto_idProducto
                  WHERE p.activo = 'inactivo'
                  GROUP BY p.idProducto, p.nombreProducto, p.codigoBarras, p.precio, p.stock, p.fechaVencimiento, p.activo, m.nombreMarca, c.nombreCategoria";
    
        $result = $conn->query($query);
    
        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $row['imagenes'] = [$row['imagenPrincipal'] ?? 'default-image.jpg'];
            $productos[] = $row;
        }
    
        $conexion->desconectar();
        return $productos;
    }    
    
    public function activarProducto($idProducto) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        
        $query = "UPDATE producto SET activo = 1 WHERE idProducto = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $idProducto);
    
        $resultado = $stmt->execute();
        $conexion->desconectar();
        return $resultado;
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
