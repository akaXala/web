<?php
include ('conexion.php'); // Este archivo debe contener la conexión a la base de datos

header('Content-Type: application/json');

// Asegurarse de que no hay ninguna salida antes de esta línea
ob_start();

// Consulta a la tabla numerocompras
$sql = "SELECT idProducto, compras FROM numerocompras ORDER BY compras DESC LIMIT 10";
$result = $conn->query($sql);

$productos = [];

if ($result) {
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
    echo json_encode($productos);
} else {
    echo json_encode(["error" => "Error fetching data"]);
}

// Limpia cualquier salida inesperada
ob_end_flush();
?>
