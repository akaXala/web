<?php
session_start(); // Start the session
header('Content-Type: application/json'); // Set Content-Type to application/json

try {
    include '../php/conexion.php';
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
    exit;
}

// Check if the user is logged in
if (!isset($_SESSION['correo'])) {
    echo json_encode(["status" => "error", "message" => "Not logged in"]);
    exit;
}

// Check if the productId is received from detallesProducto via form data
if (isset($_POST['productId'])) {
    $productId = $_POST['productId'];

    // Get the userId from the session email through a SQL query
    $email = $_SESSION['correo'];
    $query = "SELECT id FROM usuarios WHERE correo = '$email'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $userId = isset($row['id']) ? $row['id'] : null;
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to retrieve userId from the database."]);
        exit;
    }

    if ($userId) {
        $query = "INSERT INTO carritos (idUsuario, idProducto) VALUES ('$userId', '$productId')";
        if (mysqli_query($conn, $query)) {
            echo json_encode([
                "status" => "success",
                "message" => "Product with ID $productId added to the cart.",
                "productId" => $productId,
                "userId" => $userId
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to add product to the cart."]);
        }
        exit; // Ensure the script stops after handling the request
    } else {
        echo json_encode(["status" => "error", "message" => "User not found."]);
        exit;
    }
}