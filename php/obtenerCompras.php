<?php
include ('conexion.php'); // Este archivo debe contener la conexión a la base de datos

// Consulta a la tabla numerocompras
$sql = "SELECT idProducto, compras FROM numerocompras";
$result = $conn->query($sql);

$productos = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $idProducto = $row['idProducto'];
        $compras = $row['compras'];

        // Consulta a la tabla productos
        $sqlProducto = "SELECT titulo FROM productos WHERE id = $idProducto";
        $resultProducto = $conn->query($sqlProducto);

        if ($resultProducto->num_rows > 0) {
            while($rowProducto = $resultProducto->fetch_assoc()) {
                $titulo = $rowProducto['titulo'];
                $productos[] = [
                    'titulo' => $titulo,
                    'compras' => $compras
                ];
            }
        }
    }
}

$conn->close();

// Devolver los datos en formato JSON
header('Content-Type: application/json');
echo json_encode($productos);
?>
