<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jsonString = file_get_contents('../html/products.json');
    $data = json_decode($jsonString, true);

    if ($data) {
        // Eliminar todos los productos existentes
        $deleteStmt = $conn->prepare("DELETE FROM productos");
        $deleteStmt->execute();

        foreach ($data['products'] as $product) {
            $stmt = $conn->prepare("INSERT INTO productos (id, titulo, descripcion, id_categoria, precio, descuento, raiting, stock, miniatura) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssdddis", 
                $product['id'], 
                $product['title'], 
                $product['description'], 
                $product['category'], // This is now treated as a string
                $product['price'], 
                $product['discountPercentage'], 
                $product['rating'], 
                $product['stock'], 
                $product['thumbnail']
            );
            $stmt->execute();
        }
        echo "Datos insertados correctamente.";
    } else {
        echo "Error al decodificar JSON.";
    }
} else {
    echo "Método de solicitud no permitido.";
}

$conn->close();
?>
