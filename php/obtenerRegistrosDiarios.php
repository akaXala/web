<?php
include ('conexion.php'); // Este archivo debe contener la conexión a la base de datos

header('Content-Type: application/json');

// Asegurarse de que no hay ninguna salida antes de esta línea
ob_start();

$sql = "SELECT fecha, conteo FROM `registro_diario` ORDER BY fecha DESC LIMIT 10";
$result = $conn->query($sql);

$registros = [];

if ($result) {
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $registros[] = [
                'fecha' => $row['fecha'],
                'conteo' => $row['conteo']
            ];
        }
    }
    $conn->close();
    echo json_encode(array_reverse($registros)); // Envía los datos en orden de fecha ascendente
} else {
    echo json_encode(["error" => "Error fetching data"]);
}

// Limpia cualquier salida inesperada
ob_end_flush();
?>
