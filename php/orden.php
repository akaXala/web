<?php
// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve POST data
    $productIds = isset($_POST['productIds']) ? $_POST['productIds'] : [];
    $productPrices = isset($_POST['productPrices']) ? $_POST['productPrices'] : [];
    $productStocks = isset($_POST['productStocks']) ? $_POST['productStocks'] : [];
    $totalPrice = isset($_POST['totalPrice']) ? $_POST['totalPrice'] : 0;

    // Print the data for debugging
    echo "<h2>Orden de compra generada:</h2>";
    echo "<p>Productos: " . implode(", ", $productIds) . "</p>
    <p>Precios: " . implode(", ", $productPrices) . "</p>
    <p>Existencias: " . implode(", ", $productStocks) . "</p>
    <p>Precio total: " . $totalPrice . "</p>";
} else {
    // Handle case where the request is not a POST request
    echo "This script only handles POST requests.";
}
?>