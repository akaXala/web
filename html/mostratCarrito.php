<?php
session_start(); // Start the session
include '../php/conexion.php'; // Include the database connection

if(isset($_SESSION['correo'])) {
    $email = $_SESSION['correo'];
    $queryUserId = "SELECT id FROM usuarios WHERE correo = '$email'";
    $resultUserId = mysqli_query($conn, $queryUserId);
    if($rowUserId = mysqli_fetch_assoc($resultUserId)) {
        $userId = $rowUserId['id'];
        $queryCart = "SELECT p.nombre, p.precio, c.idProducto FROM productos p INNER JOIN carritos c ON p.id = c.idProducto WHERE c.idUsuario = '$userId'";
        $resultCart = mysqli_query($conn, $queryCart);
        if(mysqli_num_rows($resultCart) > 0) {
            echo '<table class="table">';
            echo '<thead><tr><th>Producto</th><th>Precio</th><th>Acciones</th></tr></thead>';
            echo '<tbody>';
            while($rowCart = mysqli_fetch_assoc($resultCart)) {
                echo "<tr><td>{$rowCart['nombre']}</td><td>{$rowCart['precio']}</td><td><a href='#' class='btn btn-danger'>Eliminar</a></td></tr>";
            }
            echo '</tbody></table>';
        } else {
            echo "<p>No hay productos en el carrito.</p>";
        }
    } else {
        echo "<p>Error al obtener el usuario.</p>";
    }
} else {
    echo "<p>Usuario no identificado. <a href='./login.html'>Iniciar sesión</a></p>";
}
?>