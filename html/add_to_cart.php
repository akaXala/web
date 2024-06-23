<?php
header('Content-Type: application/json'); // Set Content-Type to application/json

// Read the JSON content from the request body
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); // Convert it into an associative array

// Check if the productId is received from detallesProducto
if(isset($input['productId'])) {
    $productId = $input['productId'];
    // Use the $productId variable in your code
    // For example, add it to the cart or perform any other operations
    
    // Example response as an array
    $response = [
        "message" => "Product with ID $productId added to the cart.",
        "productId" => $productId
    ];

    // Encode and return the response as JSON
    echo json_encode($response);
} else {
    // Handle the case when productId is not received
    // Return an error message as JSON
    echo json_encode(["error" => "No productId received from detallesProducto."]);
}
?>