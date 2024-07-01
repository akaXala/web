<?php
require_once "../php/conexion.php";

if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    $stmt = $conn->prepare("SELECT stock FROM productos WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($product) {
        echo json_encode(["stock" => $product['stock']]);
    } else {
        echo json_encode(["error" => "Producto no encontrado"]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["error" => "ID de producto no proporcionado"]);
}
?>
