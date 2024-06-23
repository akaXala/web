<?php
// Start of the PHP code
session_start(); // Start the session
include '../php/conexion.php'; // Include the database connection

$productIds = []; // Initialize an empty array to store product IDs

if (isset($_SESSION['correo'])) {
    $email = $_SESSION['correo'];
    $queryUserId = "SELECT id FROM usuarios WHERE correo = '$email'";
    $resultUserId = mysqli_query($conn, $queryUserId);
    if ($rowUserId = mysqli_fetch_assoc($resultUserId)) {
        $userId = $rowUserId['id'];
        // Modified query to select only idProducto
        $queryProductIds = "SELECT idProducto FROM carritos WHERE idUsuario = '$userId'";
        $resultProductIds = mysqli_query($conn, $queryProductIds);
        while ($rowProductId = mysqli_fetch_assoc($resultProductIds)) {
            $productIds[] = $rowProductId['idProducto']; // Store each product ID in the array
        }
        
        // Continue with the rest of your code...
    } else {
        echo "<p>Error al obtener el usuario.</p>";
    }
} else {
    echo "<p>Usuario no identificado. <a href='./login.html'>Iniciar sesión</a></p>";
}
// Print product IDs for debugging
foreach ($productIds as $productId) {
    echo "Product ID: " . $productId . "<br>";
}