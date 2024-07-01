<?php
session_start();
include '../php/conexion.php';

// Check if the user is logged in
if (!isset($_SESSION['correo'])) {
    header("Location: ./login.html");
    exit;
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $precio = $_POST['precio'];
    $descuento = $_POST['descuento'];
    $stock = $_POST['stock'];

    // Update the product in the database without modifying the thumbnail
    $query = $conn->prepare("UPDATE productos SET titulo = ?, precio = ?, descuento = ?, stock = ? WHERE id = ?");
    $query->bind_param("sssii", $titulo, $precio, $descuento, $stock, $id);

    if ($query->execute()) {
        echo "Product updated successfully.";
        header("Location: ../html/productos.php"); // Redirect to a relevant page after updating
    } else {
        echo "Error updating product: " . $query->error;
    }
} else {
    echo "Invalid request method.";
}
?>
