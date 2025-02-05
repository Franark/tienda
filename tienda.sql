-- MySQL dump 10.13  Distrib 8.0.36, for Linux (x86_64)
--
-- Host: localhost    Database: tienda
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `atributoDomicilio`
--
CREATE DATABASE tienda;

USE tienda;

DROP TABLE IF EXISTS `atributoDomicilio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `atributoDomicilio` (
  `idAtributoDomicilio` int(11) NOT NULL AUTO_INCREMENT,
  `nombreAtributo` varchar(255) NOT NULL,
  PRIMARY KEY (`idAtributoDomicilio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `atributoDomicilio`
--

LOCK TABLES `atributoDomicilio` WRITE;
/*!40000 ALTER TABLE `atributoDomicilio` DISABLE KEYS */;
/*!40000 ALTER TABLE `atributoDomicilio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `barrio`
--

DROP TABLE IF EXISTS `barrio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `barrio` (
  `idBarrio` int(11) NOT NULL AUTO_INCREMENT,
  `nombreBarrio` varchar(255) NOT NULL,
  PRIMARY KEY (`idBarrio`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barrio`
--

LOCK TABLES `barrio` WRITE;
/*!40000 ALTER TABLE `barrio` DISABLE KEYS */;
INSERT INTO `barrio` VALUES (1,'La Floresta');
/*!40000 ALTER TABLE `barrio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carritoCompra`
--

DROP TABLE IF EXISTS `carritoCompra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carritoCompra` (
  `idCarritoCompra` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_idCliente` int(11) DEFAULT NULL,
  `estado` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idCarritoCompra`),
  KEY `cliente_idCliente` (`cliente_idCliente`),
  CONSTRAINT `carritoCompra_ibfk_1` FOREIGN KEY (`cliente_idCliente`) REFERENCES `cliente` (`idCliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carritoCompra`
--

LOCK TABLES `carritoCompra` WRITE;
/*!40000 ALTER TABLE `carritoCompra` DISABLE KEYS */;
/*!40000 ALTER TABLE `carritoCompra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carritoProducto`
--

DROP TABLE IF EXISTS `carritoProducto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carritoProducto` (
  `idCarritoProducto` int(11) NOT NULL AUTO_INCREMENT,
  `carritoCompra_idCarritoCompra` int(11) DEFAULT NULL,
  `producto_idProducto` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precioTotal` float DEFAULT NULL,
  PRIMARY KEY (`idCarritoProducto`),
  KEY `carritoCompra_idCarritoCompra` (`carritoCompra_idCarritoCompra`),
  KEY `producto_idProducto` (`producto_idProducto`),
  CONSTRAINT `carritoProducto_ibfk_1` FOREIGN KEY (`carritoCompra_idCarritoCompra`) REFERENCES `carritoCompra` (`idCarritoCompra`),
  CONSTRAINT `carritoProducto_ibfk_2` FOREIGN KEY (`producto_idProducto`) REFERENCES `producto` (`idProducto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carritoProducto`
--

LOCK TABLES `carritoProducto` WRITE;
/*!40000 ALTER TABLE `carritoProducto` DISABLE KEYS */;
/*!40000 ALTER TABLE `carritoProducto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categoriaProducto`
--

DROP TABLE IF EXISTS `categoriaProducto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categoriaProducto` (
  `idCategoriaProducto` int(11) NOT NULL AUTO_INCREMENT,
  `nombreCategoria` varchar(255) NOT NULL,
  PRIMARY KEY (`idCategoriaProducto`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoriaProducto`
--

LOCK TABLES `categoriaProducto` WRITE;
/*!40000 ALTER TABLE `categoriaProducto` DISABLE KEYS */;
INSERT INTO `categoriaProducto` VALUES (1,'Bebidas'),(2,'Lácteos'),(3,'Panadería'),(4,'Carnes'),(5,'Frutas y Verduras'),(6,'Snacks'),(7,'Limpieza'),(8,'Cuidado Personal');
/*!40000 ALTER TABLE `categoriaProducto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cliente`
--

DROP TABLE IF EXISTS `cliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cliente` (
  `idCliente` int(11) NOT NULL AUTO_INCREMENT,
  `persona_idPersona` int(11) DEFAULT NULL,
  PRIMARY KEY (`idCliente`),
  KEY `persona_idPersona` (`persona_idPersona`),
  CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`persona_idPersona`) REFERENCES `persona` (`idPersona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente`
--

LOCK TABLES `cliente` WRITE;
/*!40000 ALTER TABLE `cliente` DISABLE KEYS */;
/*!40000 ALTER TABLE `cliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `domicilio`
--

DROP TABLE IF EXISTS `domicilio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `domicilio` (
  `idDomicilio` int(11) NOT NULL AUTO_INCREMENT,
  `persona_idPersona` int(11) DEFAULT NULL,
  `barrio_idBarrio` int(11) DEFAULT NULL,
  PRIMARY KEY (`idDomicilio`),
  KEY `persona_idPersona` (`persona_idPersona`),
  KEY `barrio_idBarrio` (`barrio_idBarrio`),
  CONSTRAINT `domicilio_ibfk_1` FOREIGN KEY (`persona_idPersona`) REFERENCES `persona` (`idPersona`),
  CONSTRAINT `domicilio_ibfk_2` FOREIGN KEY (`barrio_idBarrio`) REFERENCES `barrio` (`idBarrio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `domicilio`
--

LOCK TABLES `domicilio` WRITE;
/*!40000 ALTER TABLE `domicilio` DISABLE KEYS */;
/*!40000 ALTER TABLE `domicilio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `domicilioDetalle`
--

DROP TABLE IF EXISTS `domicilioDetalle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `domicilioDetalle` (
  `idDomicilioDetalle` int(11) NOT NULL AUTO_INCREMENT,
  `domicilio_idDomicilio` int(11) DEFAULT NULL,
  `atributoDomicilio_idAtributoDomicilio` int(11) DEFAULT NULL,
  `valor` varchar(45) NOT NULL,
  PRIMARY KEY (`idDomicilioDetalle`),
  KEY `domicilio_idDomicilio` (`domicilio_idDomicilio`),
  KEY `atributoDomicilio_idAtributoDomicilio` (`atributoDomicilio_idAtributoDomicilio`),
  CONSTRAINT `domicilioDetalle_ibfk_1` FOREIGN KEY (`domicilio_idDomicilio`) REFERENCES `domicilio` (`idDomicilio`),
  CONSTRAINT `domicilioDetalle_ibfk_2` FOREIGN KEY (`atributoDomicilio_idAtributoDomicilio`) REFERENCES `atributoDomicilio` (`idAtributoDomicilio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `domicilioDetalle`
--

LOCK TABLES `domicilioDetalle` WRITE;
/*!40000 ALTER TABLE `domicilioDetalle` DISABLE KEYS */;
/*!40000 ALTER TABLE `domicilioDetalle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empleado`
--

DROP TABLE IF EXISTS `empleado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empleado` (
  `idEmpleado` int(11) NOT NULL AUTO_INCREMENT,
  `persona_idPersona` int(11) DEFAULT NULL,
  PRIMARY KEY (`idEmpleado`),
  KEY `persona_idPersona` (`persona_idPersona`),
  CONSTRAINT `empleado_ibfk_1` FOREIGN KEY (`persona_idPersona`) REFERENCES `persona` (`idPersona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empleado`
--

LOCK TABLES `empleado` WRITE;
/*!40000 ALTER TABLE `empleado` DISABLE KEYS */;
/*!40000 ALTER TABLE `empleado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `envio`
--

DROP TABLE IF EXISTS `envio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `envio` (
  `idEnvio` int(11) NOT NULL AUTO_INCREMENT,
  `fechaEnvio` date DEFAULT NULL,
  `estadoEnvio` varchar(45) DEFAULT NULL,
  `orden_idOrden` int(11) DEFAULT NULL,
  PRIMARY KEY (`idEnvio`),
  KEY `orden_idOrden` (`orden_idOrden`),
  CONSTRAINT `envio_ibfk_1` FOREIGN KEY (`orden_idOrden`) REFERENCES `orden` (`idOrden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `envio`
--

LOCK TABLES `envio` WRITE;
/*!40000 ALTER TABLE `envio` DISABLE KEYS */;
/*!40000 ALTER TABLE `envio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `factura`
--

DROP TABLE IF EXISTS `factura`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `factura` (
  `idFactura` int(11) NOT NULL AUTO_INCREMENT,
  `fechaEmision` date DEFAULT NULL,
  `montoTotal` float DEFAULT NULL,
  `orden_idOrden` int(11) DEFAULT NULL,
  PRIMARY KEY (`idFactura`),
  KEY `orden_idOrden` (`orden_idOrden`),
  CONSTRAINT `factura_ibfk_1` FOREIGN KEY (`orden_idOrden`) REFERENCES `orden` (`idOrden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `factura`
--

LOCK TABLES `factura` WRITE;
/*!40000 ALTER TABLE `factura` DISABLE KEYS */;
/*!40000 ALTER TABLE `factura` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `marca`
--

DROP TABLE IF EXISTS `marca`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `marca` (
  `idMarca` int(11) NOT NULL AUTO_INCREMENT,
  `nombreMarca` varchar(255) NOT NULL,
  PRIMARY KEY (`idMarca`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `marca`
--

LOCK TABLES `marca` WRITE;
/*!40000 ALTER TABLE `marca` DISABLE KEYS */;
INSERT INTO `marca` VALUES (1,'Coca-Cola'),(2,'Pepsi'),(3,'Lala'),(4,'Bimbo'),(5,'Sukarne'),(6,'Herdez'),(7,'Colgate'),(8,'Dove'),(9,'Nestlé'),(10,'Kellogg\'s'),(11,'Heinz'),(12,'Danone'),(13,'Ariel'),(14,'Gillette'),(15,'Palmolive'),(16,'Tide'),(17,'Huggies'),(18,'PepsiCo'),(19,'Pampers'),(20,'Colgate-Palmolive'),(21,'PepsiCo'),(22,'Procter & Gamble'),(23,'Unilever'),(24,'Johnson & Johnson'),(25,'Mars'),(26,'Mondelez'),(27,'Danone'),(28,'Reckitt Benckiser'),(29,'Bimbo'),(30,'Nescafé'),(31,'Gamesa'),(32,'La Costeña'),(33,'Suavitel'),(34,'Fabuloso'),(35,'Pinol'),(36,'Jumex'),(37,'Carbonell'),(38,'Lala'),(39,'Smucker\'s'),(40,'Zucaritas'),(41,'Barcel'),(42,'Nestlé'),(43,'Heinz'),(44,'Coca-Cola'),(45,'Lays'),(46,'Sabritas'),(47,'Sprite'),(48,'Fanta');
/*!40000 ALTER TABLE `marca` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orden`
--

DROP TABLE IF EXISTS `orden`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orden` (
  `idOrden` int(11) NOT NULL AUTO_INCREMENT,
  `fechaOrden` date DEFAULT NULL,
  `cliente_idCliente` int(11) DEFAULT NULL,
  `estado` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idOrden`),
  KEY `cliente_idCliente` (`cliente_idCliente`),
  CONSTRAINT `orden_ibfk_1` FOREIGN KEY (`cliente_idCliente`) REFERENCES `cliente` (`idCliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orden`
--

LOCK TABLES `orden` WRITE;
/*!40000 ALTER TABLE `orden` DISABLE KEYS */;
/*!40000 ALTER TABLE `orden` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ordenProducto`
--

DROP TABLE IF EXISTS `ordenProducto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ordenProducto` (
  `idOrdenProducto` int(11) NOT NULL AUTO_INCREMENT,
  `orden_idOrden` int(11) DEFAULT NULL,
  `producto_idProducto` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precioTotal` float DEFAULT NULL,
  PRIMARY KEY (`idOrdenProducto`),
  KEY `orden_idOrden` (`orden_idOrden`),
  KEY `producto_idProducto` (`producto_idProducto`),
  CONSTRAINT `ordenProducto_ibfk_1` FOREIGN KEY (`orden_idOrden`) REFERENCES `orden` (`idOrden`),
  CONSTRAINT `ordenProducto_ibfk_2` FOREIGN KEY (`producto_idProducto`) REFERENCES `producto` (`idProducto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ordenProducto`
--

LOCK TABLES `ordenProducto` WRITE;
/*!40000 ALTER TABLE `ordenProducto` DISABLE KEYS */;
/*!40000 ALTER TABLE `ordenProducto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permiso`
--

DROP TABLE IF EXISTS `permiso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permiso` (
  `idPermiso` int(11) NOT NULL AUTO_INCREMENT,
  `nombrePermiso` varchar(255) NOT NULL,
  PRIMARY KEY (`idPermiso`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permiso`
--

LOCK TABLES `permiso` WRITE;
/*!40000 ALTER TABLE `permiso` DISABLE KEYS */;
INSERT INTO `permiso` VALUES (1,'signup'),(2,'login'),(3,'crearCategoria'),(4,'crearMarca'),(5,'crearProducto'),(6,'crearTipoContacto'),(7,'crearTipoDocumento'),(8,'crearUsuario'),(9,'editarCategoria'),(10,'editarMarca'),(11,'editarProducto'),(12,'editarTipoContacto'),(13,'editarTipoDocumento'),(14,'editarUsuario'),(15,'gestionCategorias'),(16,'gestionMarcas'),(17,'gestionProductos'),(18,'gestionTipoContacto'),(19,'gestionTipoDocumento'),(20,'gestionUsuarios'),(21,'logout'),(22,'cambiarPassword'),(23,'perfil'),(24,'gestionBarrio'),(25,'gestionPaginas'),(26,'home'),(27,'404'),(28,'403');
/*!40000 ALTER TABLE `permiso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `persona`
--

DROP TABLE IF EXISTS `persona`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `persona` (
  `idPersona` int(11) NOT NULL AUTO_INCREMENT,
  `nombrePersona` varchar(255) DEFAULT NULL,
  `apellidoPersona` varchar(255) DEFAULT NULL,
  `edadPersona` int(11) DEFAULT NULL,
  `tipoSexo_idTipoSexo` int(11) DEFAULT NULL,
  `usuario_idUsuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`idPersona`),
  KEY `tipoSexo_idTipoSexo` (`tipoSexo_idTipoSexo`),
  KEY `fk_persona_usuario1_idx` (`usuario_idUsuario`),
  CONSTRAINT `fk_persona_usuario1` FOREIGN KEY (`usuario_idUsuario`) REFERENCES `usuario` (`idUsuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `persona_ibfk_1` FOREIGN KEY (`tipoSexo_idTipoSexo`) REFERENCES `tipoSexo` (`idTipoSexo`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `persona`
--

LOCK TABLES `persona` WRITE;
/*!40000 ALTER TABLE `persona` DISABLE KEYS */;
INSERT INTO `persona` VALUES (2,'Francisco','Ibarra',18,1,5);
/*!40000 ALTER TABLE `persona` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personaContacto`
--

DROP TABLE IF EXISTS `personaContacto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personaContacto` (
  `idPersonaContacto` int(11) NOT NULL AUTO_INCREMENT,
  `valor` varchar(45) NOT NULL,
  `persona_idPersona` int(11) DEFAULT NULL,
  `tipoContacto_idTipoContacto` int(11) DEFAULT NULL,
  PRIMARY KEY (`idPersonaContacto`),
  KEY `persona_idPersona` (`persona_idPersona`),
  KEY `tipoContacto_idTipoContacto` (`tipoContacto_idTipoContacto`),
  CONSTRAINT `personaContacto_ibfk_1` FOREIGN KEY (`persona_idPersona`) REFERENCES `persona` (`idPersona`),
  CONSTRAINT `personaContacto_ibfk_2` FOREIGN KEY (`tipoContacto_idTipoContacto`) REFERENCES `tipoContacto` (`idTipoContacto`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personaContacto`
--

LOCK TABLES `personaContacto` WRITE;
/*!40000 ALTER TABLE `personaContacto` DISABLE KEYS */;
INSERT INTO `personaContacto` VALUES (10,'23452354252',2,1);
/*!40000 ALTER TABLE `personaContacto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personaDocumento`
--

DROP TABLE IF EXISTS `personaDocumento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personaDocumento` (
  `idPersonaDocumento` int(11) NOT NULL AUTO_INCREMENT,
  `valor` varchar(45) NOT NULL,
  `persona_idPersona` int(11) DEFAULT NULL,
  `tipoDocumento_idTipoDocumento` int(11) DEFAULT NULL,
  PRIMARY KEY (`idPersonaDocumento`),
  KEY `persona_idPersona` (`persona_idPersona`),
  KEY `tipoDocumento_idTipoDocumento` (`tipoDocumento_idTipoDocumento`),
  CONSTRAINT `personaDocumento_ibfk_1` FOREIGN KEY (`persona_idPersona`) REFERENCES `persona` (`idPersona`),
  CONSTRAINT `personaDocumento_ibfk_2` FOREIGN KEY (`tipoDocumento_idTipoDocumento`) REFERENCES `tipoDocumento` (`idTipoDocumento`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personaDocumento`
--

LOCK TABLES `personaDocumento` WRITE;
/*!40000 ALTER TABLE `personaDocumento` DISABLE KEYS */;
INSERT INTO `personaDocumento` VALUES (1,'564894655',2,2);
/*!40000 ALTER TABLE `personaDocumento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `producto`
--

DROP TABLE IF EXISTS `producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `producto` (
  `idProducto` int(11) NOT NULL AUTO_INCREMENT,
  `nombreProducto` varchar(255) NOT NULL,
  `codigoBarras` varchar(13) NOT NULL,
  `precio` float NOT NULL,
  `stock` int(11) DEFAULT NULL,
  `fechaVencimiento` date DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `marca_idMarca` int(11) DEFAULT NULL,
  `categoriaProducto_idCategoriaProducto` int(11) DEFAULT NULL,
  PRIMARY KEY (`idProducto`),
  KEY `marca_idMarca` (`marca_idMarca`),
  KEY `categoriaProducto_idCategoriaProducto` (`categoriaProducto_idCategoriaProducto`),
  CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`marca_idMarca`) REFERENCES `marca` (`idMarca`),
  CONSTRAINT `producto_ibfk_2` FOREIGN KEY (`categoriaProducto_idCategoriaProducto`) REFERENCES `categoriaProducto` (`idCategoriaProducto`)
) ENGINE=InnoDB AUTO_INCREMENT=160 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `producto`
--

LOCK TABLES `producto` WRITE;
/*!40000 ALTER TABLE `producto` DISABLE KEYS */;
INSERT INTO `producto` VALUES (1,'Coca-Cola 2L','7501055311111',1.5,100,'2024-12-31','coca_cola_2l.png',1,1),(2,'Pepsi 2L','7501055312222',1.5,80,'2024-12-31','pepsi_2l.png',2,1),(3,'Leche Lala 1L','7501055323333',1,150,'2023-09-15','leche_lala_1l.png',3,2),(4,'Pan Blanco Bimbo','7501055334444',1.2,200,'2023-08-31','pan_blanco_bimbo.png',4,3),(5,'Carne Molida Sukarne 500g','7501055345555',3.5,50,'2023-09-10','carne_molida_sukarne.png',5,4),(6,'Atún Herdez 140g','7501055356666',1.3,120,'2025-02-28','atun_herdez.png',6,5),(7,'Pasta de Dientes Colgate','7501055367777',2,90,'2026-05-31','colgate_pasta.png',7,8),(8,'Jabón Dove 135g','7501055378888',1.8,130,'2025-01-31','jabon_dove.png',8,8),(9,'Chocolate Nestlé 100g','7501055399999',1.2,150,'2024-03-31','chocolate_nestle.png',9,6),(10,'Cereal Corn Flakes 500g','7501055312345',3,100,'2023-12-31','corn_flakes.png',10,6),(11,'Kétchup Heinz 500g','7501055323456',2.5,80,'2024-11-30','ketchup_heinz.png',11,7),(12,'Yogurt Danone 1L','7501055334567',2,90,'2023-09-10','yogurt_danone.png',12,2),(13,'Detergente Ariel 1kg','7501055345678',3.5,60,'2025-01-15','detergente_ariel.png',13,7),(14,'Rasuradora Gillette','7501055356789',5,50,'2026-03-31','rasuradora_gillette.png',14,8),(15,'Shampoo Palmolive 400ml','7501055367890',2.5,70,'2025-06-30','shampoo_palmolive.png',15,8),(16,'Detergente Tide 1kg','7501055378901',3.7,65,'2025-02-28','detergente_tide.png',16,7),(17,'Pañales Huggies 50pz','7501055389012',12,40,'2026-12-31','panales_huggies.png',17,8),(18,'Galletas Gamesa','7501055390123',1,120,'2023-10-15','galletas_gamesa.png',18,6),(113,'Pañales Pampers 60pz','7501055401111',15,30,'2026-12-31','panales_pampers.png',19,8),(114,'Crema Dental Colgate 100ml','7501055402222',2.5,90,'2025-12-31','colgate_100ml.png',20,8),(115,'Refresco Pepsi 355ml','7501055403333',1,150,'2024-08-31','pepsi_355ml.png',3,1),(116,'Jabón Líquido Dove 250ml','7501055404444',3,80,'2025-06-30','jabon_dove.png',8,8),(117,'Shampoo Pantene 400ml','7501055405555',4,60,'2025-12-31','shampoo_pantene.png',21,8),(118,'Desodorante Rexona 150ml','7501055406666',3.5,75,'2025-09-30','desodorante_rexona.png',22,8),(119,'Jabón de Manos Palmolive 300ml','7501055407777',2.5,100,'2025-11-30','jabon_manos_palmolive.png',23,8),(120,'Pasta de Dientes Sensodyne','7501055408888',5,40,'2026-01-31','sensodyne.png',24,8),(121,'Leche Danone Deslactosada 1L','7501055409999',1.2,120,'2023-11-15','leche_deslactosada_danone.png',25,2),(122,'Detergente Líquido Ariel 2L','7501055410000',6.5,50,'2025-04-30','detergente_liquido_ariel.png',13,7),(123,'Cereal Choco Krispis 500g','7501055411111',3.2,110,'2024-01-31','choco_krispis.png',10,6),(124,'Galletas Oreo 154g','7501055412222',1.5,100,'2023-12-31','galletas_oreo.png',26,6),(125,'Café Soluble Nescafé 200g','7501055413333',4,70,'2024-02-28','nescafe.png',9,6),(126,'Aceite Vegetal Capullo 1L','7501055414444',2,80,'2024-10-31','aceite_capullo.png',27,7),(127,'Cereal Froot Loops 500g','7501055415555',3.5,90,'2024-03-31','froot_loops.png',10,6),(128,'Helado de Vainilla 1L','7501055416666',5,60,'2023-08-31','helado_vainilla.png',28,2),(129,'Salsa Cátsup Del Monte 500g','7501055417777',1.8,100,'2024-05-31','salsa_catsup_del_monte.png',29,7),(130,'Cerveza Corona 355ml','7501055418888',1.2,150,'2024-09-30','cerveza_corona.png',30,1),(131,'Galletas María Gamesa 170g','7501055419999',0.9,130,'2024-03-31','galletas_maria_gamesa.png',18,6),(132,'Jabón en Barra Zest 200g','7501055420000',1.5,80,'2025-02-28','jabon_zest.png',31,8),(133,'Chocolate en Polvo Hershey\'s 400g','7501055421111',4.5,60,'2025-12-31','chocolate_hersheys.png',32,6),(134,'Queso Manchego 500g','7501055422222',6,40,'2023-09-15','queso_manchego.png',33,2),(135,'Arroz Verde Valle 1kg','7501055423333',1.3,120,'2024-12-31','arroz_verde_valle.png',34,7),(136,'Papel Higiénico Pétalo 4pz','7501055424444',3,100,'2025-08-31','papel_higienico_petalo.png',35,7),(137,'Toallas Femeninas Always 16pz','7501055425555',4,60,'2026-02-28','toallas_always.png',36,8),(138,'Cerveza Heineken 355ml','7501055426666',1.3,140,'2024-10-31','cerveza_heineken.png',30,1),(139,'Atún Dolores 140g','7501055427777',1.5,100,'2025-07-31','atun_dolores.png',37,5),(140,'Frijoles Refritos La Sierra 560g','7501055428888',2,110,'2025-01-31','frijoles_la_sierra.png',38,5),(141,'Mermelada de Fresa Smucker\'s 400g','7501055429999',3.5,60,'2025-05-31','mermelada_smuckers.png',39,5),(142,'Jugo de Naranja Del Valle 1L','7501055430000',1.5,120,'2023-09-30','jugo_del_valle.png',40,1),(143,'Avena Quaker 800g','7501055431111',2.5,80,'2024-07-31','avena_quaker.png',41,6),(144,'Agua Embotellada Bonafont 1L','7501055432222',1,150,'2025-12-31','agua_bonafont.png',42,1),(145,'Galletas Marías 170g','7501055433333',0.8,130,'2024-11-30','galletas_marias.png',18,6),(146,'Carne para Asar Sukarne 1kg','7501055434444',10,50,'2023-09-10','carne_asar_sukarne.png',5,4),(147,'Cereal All-Bran 500g','7501055435555',3.8,90,'2024-04-30','all_bran.png',10,6),(148,'Queso Oaxaca 500g','7501055436666',5.5,50,'2023-08-31','queso_oaxaca.png',33,2),(149,'Pasta Dental Colgate Total 100ml','7501055437777',2.8,90,'2025-12-31','colgate_total.png',20,8),(150,'Pañales Huggies Etapa 3','7501055438888',13,40,'2026-12-31','panales_huggies_etapa3.png',17,8),(151,'Detergente Ace 1kg','7501055439999',3.2,60,'2025-03-31','detergente_ace.png',43,7),(152,'Jugo de Manzana Jumex 1L','7501055440000',1.5,110,'2023-11-15','jugo_jumex.png',44,1),(153,'Crema Corporal Nivea 250ml','7501055441111',4,70,'2025-10-31','crema_nivea.png',45,8),(154,'Aceite de Oliva Carbonell 500ml','7501055442222',6,60,'2024-12-31','aceite_carbonell.png',46,7),(155,'Cereal Zucaritas 500g','7501055443333',3,100,'2024-02-28','zucaritas.png',10,6),(156,'Agua Embotellada Epura 1L','7501055444444',0.9,140,'2025-11-30','agua_epura.png',47,1),(157,'Cereal Fitness 500g','7501055445555',4.2,80,'2024-06-30','cereal_fitness.png',9,6),(158,'Salsa Valentina 370ml','7501055446666',1.2,120,'2025-02-28','salsa_valentina.png',29,7),(159,'Mantequilla Lala 90g','7501055447777',1.5,70,'2023-10-31','mantequilla_lala.png',48,2);
/*!40000 ALTER TABLE `producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rolPermisos`
--

DROP TABLE IF EXISTS `rolPermisos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rolPermisos` (
  `idRolPermisos` int(11) NOT NULL AUTO_INCREMENT,
  `rolUsuario_idRolUsuario` int(11) DEFAULT NULL,
  `permiso_idPermiso` int(11) DEFAULT NULL,
  PRIMARY KEY (`idRolPermisos`),
  KEY `rolUsuario_idRolUsuario` (`rolUsuario_idRolUsuario`),
  KEY `permiso_idPermiso` (`permiso_idPermiso`),
  CONSTRAINT `rolPermisos_ibfk_1` FOREIGN KEY (`rolUsuario_idRolUsuario`) REFERENCES `rolUsuario` (`idRolUsuario`),
  CONSTRAINT `rolPermisos_ibfk_2` FOREIGN KEY (`permiso_idPermiso`) REFERENCES `permiso` (`idPermiso`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rolPermisos`
--

LOCK TABLES `rolPermisos` WRITE;
/*!40000 ALTER TABLE `rolPermisos` DISABLE KEYS */;
INSERT INTO `rolPermisos` VALUES (6,1,1),(7,1,2),(8,1,3),(9,1,4),(10,1,5),(11,1,6),(12,1,7),(13,1,8),(14,1,9),(15,1,10),(16,1,11),(17,1,12),(18,1,13),(19,1,14),(20,1,15),(21,1,16),(22,1,17),(23,1,18),(24,1,19),(25,1,20),(26,1,21),(27,1,22),(28,1,23),(29,1,24),(30,1,25),(31,1,26),(32,1,27),(33,1,28);
/*!40000 ALTER TABLE `rolPermisos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rolUsuario`
--

DROP TABLE IF EXISTS `rolUsuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rolUsuario` (
  `idRolUsuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombreRol` varchar(255) NOT NULL,
  PRIMARY KEY (`idRolUsuario`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rolUsuario`
--

LOCK TABLES `rolUsuario` WRITE;
/*!40000 ALTER TABLE `rolUsuario` DISABLE KEYS */;
INSERT INTO `rolUsuario` VALUES (1,'Administrador'),(2,'Cliente'),(3,'Empleado');
/*!40000 ALTER TABLE `rolUsuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipoContacto`
--

DROP TABLE IF EXISTS `tipoContacto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipoContacto` (
  `idTipoContacto` int(11) NOT NULL AUTO_INCREMENT,
  `nombreTipoContacto` varchar(255) NOT NULL,
  PRIMARY KEY (`idTipoContacto`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipoContacto`
--

LOCK TABLES `tipoContacto` WRITE;
/*!40000 ALTER TABLE `tipoContacto` DISABLE KEYS */;
INSERT INTO `tipoContacto` VALUES (1,'Telefono'),(3,'Email');
/*!40000 ALTER TABLE `tipoContacto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipoDocumento`
--

DROP TABLE IF EXISTS `tipoDocumento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipoDocumento` (
  `idTipoDocumento` int(11) NOT NULL AUTO_INCREMENT,
  `nombreTipoDocumento` varchar(255) NOT NULL,
  PRIMARY KEY (`idTipoDocumento`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipoDocumento`
--

LOCK TABLES `tipoDocumento` WRITE;
/*!40000 ALTER TABLE `tipoDocumento` DISABLE KEYS */;
INSERT INTO `tipoDocumento` VALUES (2,'DNI');
/*!40000 ALTER TABLE `tipoDocumento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipoSexo`
--

DROP TABLE IF EXISTS `tipoSexo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipoSexo` (
  `idTipoSexo` int(11) NOT NULL AUTO_INCREMENT,
  `nombreTipoSexo` varchar(255) NOT NULL,
  PRIMARY KEY (`idTipoSexo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipoSexo`
--

LOCK TABLES `tipoSexo` WRITE;
/*!40000 ALTER TABLE `tipoSexo` DISABLE KEYS */;
INSERT INTO `tipoSexo` VALUES (1,'Masculino'),(2,'Femenino');
/*!40000 ALTER TABLE `tipoSexo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `idUsuario` int(11) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rolUsuario_idRolUsuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`idUsuario`),
  KEY `rolUsuario_idRolUsuario` (`rolUsuario_idRolUsuario`),
  CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`rolUsuario_idRolUsuario`) REFERENCES `rolUsuario` (`idRolUsuario`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (5,'franark','oscarfranciscooscar1@gmail.com','$2y$10$Bpnm1jJETmQp1y6rIIV/K.kJkZF0Iwh25503FRjfuLs1NMB9UYT7a',1);
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-09-02 14:51:23
