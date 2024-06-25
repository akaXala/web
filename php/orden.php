<?php
require('./conexion.php');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve POST data
    $productIds = isset($_POST['productIds']) ? $_POST['productIds'] : [];
    $productPrices = isset($_POST['productPrices']) ? $_POST['productPrices'] : [];
    $productStocks = isset($_POST['productStocks']) ? $_POST['productStocks'] : [];
    $totalPrice = isset($_POST['totalPrice']) ? $_POST['totalPrice'] : 0;
    $userId = isset($_POST['userId']) ? intval($_POST['userId']) : 0;

    // Insert a new order into the `orden` table
    $insertOrderQuery = "INSERT INTO orden (fecha, usuario_id) VALUES (NOW(), $userId)";
    if ($conn->query($insertOrderQuery) === TRUE) {
        $orderId = $conn->insert_id; // Get the ID of the inserted order

        // Insert product IDs into the `orden_id_prod` table
        foreach ($productIds as $productId) {
            $productId = intval($productId); // Ensure the ID is an integer
            $insertProductQuery = "INSERT INTO orden_id_prod (id, id_prod) VALUES ($orderId, $productId)";
            if ($conn->query($insertProductQuery) !== TRUE) {
                echo "Error al insertar el producto $productId: " . $conn->error;
            }
        }

        // Print the order data for debugging
        echo "<h2>Orden de compra generada:</h2>";
        echo "<p>ID de la Orden: $orderId</p>";
        echo "<p>Productos:</p><ul>";
        foreach ($productIds as $index => $productId) {
            echo "<li>ID Producto: $productId, Precio: {$productPrices[$index]}, Stock: {$productStocks[$index]}</li>";
        }
        echo "</ul>";
        echo "<p>Precio Total: $totalPrice</p>";

        // Add a button to generate the PDF
        echo "<form action='generarPDF.php' method='get'>";
        echo "<input type='hidden' name='orderId' value='$orderId'>";
        echo "<input type='hidden' name='userId' value='$userId'>";
        echo "<button type='submit'>Generar PDF</button>";
        echo "</form>";
    } else {
        echo "Error al insertar la orden: " . $conn->error;
    }
} else {
    // Handle case where the request is not a POST request
    echo "This script only handles POST requests.";
}
?>
