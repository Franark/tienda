<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

require_once('../model/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idOrden = isset($_POST['idOrden']) ? (int)$_POST['idOrden'] : 0;

    if (isset($_FILES['reciboPago']) && $_FILES['reciboPago']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['reciboPago']['tmp_name'];
        $fileName = $_FILES['reciboPago']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedExtensions = ['jpg', 'png', 'pdf'];
        if (!in_array($fileExtension, $allowedExtensions)) {
            header("Location: ../?page=detallesOrdenCliente&idOrden={$idOrden}&status=error&message=Tipo de archivo no permitido.");
            exit;
        }

        $destPath = __DIR__ . '/../assets/recibos/recibo_' . $idOrden . '.' . $fileExtension;
        $fileName = basename($destPath);

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            $conexion = new Conexion();
            $conn = $conexion->conectar();

            $query = "UPDATE orden SET reciboPago = ? WHERE idOrden = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $fileName, $idOrden); 

            if ($stmt->execute()) {
                header("Location: ../?page=detallesOrdenCliente&idOrden={$idOrden}&status=success&message=Recibo subido correctamente.");
            } else {
                header("Location: ../?page=detallesOrdenCliente&idOrden={$idOrden}&status=error&message=Error al actualizar la base de datos.");
            }

            $stmt->close();
            $conexion->desconectar();
        } else {
            header("Location: ../?page=detallesOrdenCliente&idOrden={$idOrden}&status=error&message=Error al mover el archivo subido.");
        }
    } else {
        header("Location: ../?page=detallesOrdenCliente&idOrden={$idOrden}&status=error&message=Error al subir el archivo: " . $_FILES['reciboPago']['error']);
    }
}

?>
