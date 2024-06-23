<?php
session_start(); // Start the session
header('Content-Type: application/json'); // Set Content-Type to application/json
try {
    include '../php/conexion.php';
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}

// Check if the productId is received from detallesProducto via form data
if(isset($_POST['productId'])) {
    $productId = $_POST['productId'];
    // Get the userId from the session email through a SQL query
    $email = $_SESSION['correo'];
    $query = "SELECT id FROM usuarios WHERE correo = '$email'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $userId = isset($row['id']) ? $row['id'] : 'default_value';
    } else {
        // Handle the case when the SQL query fails
        echo json_encode(["error" => "Failed to retrieve userId from the database."]);
        exit;
    }
    

    $query = "INSERT INTO carritos (idUsuario, idProducto) VALUES ('$userId', '$productId')";
    if (mysqli_query($conn, $query)) {
        $response = [
            "message" => "Product with ID $productId added to the cart.",
            "productId" => $productId
        ];
    } else {
        // Handle the case when the SQL query fails
        echo json_encode(["error" => "Failed to add product to the cart."]);
        exit;
    }
    $response = [
        "message" => "Product with ID $productId added to the cart., userId: $userId, email: $email",
        "productId" => $productId,
        "userId" => $userId
    ];

    // Encode and return the response as JSON
    echo json_encode($response);
} else {
    // Handle the case when productId is not received
    // Return an error message as JSON
    echo json_encode(["error" => "No productId received from detallesProducto."]);
}
?>