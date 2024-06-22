<?php
include('conexion.php');

    $correo = $_POST["txtusuario"];
    $pass = $_POST["txtpassword"];

    // Consulta directa sin protección contra inyecciones SQL (no recomendado para producción)
    $query = "SELECT * FROM usuarios WHERE correo = '$correo' AND contrasena = '$pass'";
    $result = mysqli_query($conn, $query);
    $nr = mysqli_num_rows($result);

    if ($nr == 1) {
        echo "Bienvenido: " . htmlspecialchars($correo);
    } else {
        echo "No ingreso, usuario no existe.";
    }

    mysqli_close($conn);
?>