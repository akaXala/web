<?php
session_start();
include '../php/conexion.php';

if (isset($_SESSION['correo']) && isset($_GET['idProducto'])) {
    $email = $_SESSION['correo'];
    $productId = $_GET['idProducto'];

    // Query to get userId based on email
    $userQuery = "SELECT id FROM usuarios WHERE correo = '$email'";
    $userResult = mysqli_query($conn, $userQuery);
    if ($userRow = mysqli_fetch_assoc($userResult)) {
        $userId = $userRow['id'];

        // Use the obtained userId in the DELETE query
        $query = "DELETE FROM carritos WHERE idUsuario = '$userId' AND idProducto = '$productId'";
        if (mysqli_query($conn, $query)) {
            echo "Producto eliminado con éxito.";
        } else {
            echo "Error al eliminar el producto.";
        }
    } else {
        echo "Usuario no encontrado.";
    }
} else {
    echo "Operación no permitida.";
}
header('Location: ../html/mostratCarrito.php'); // Redirect back to the cart page
exit();
?>