<?php
    include('conexion.php');

    $nombre = $_POST["nombre"];
    $apP = $_POST["paterno"];
    $apM = $_POST["materno"];
    $tel = $_POST["tel"];
    $email = $_POST["email"];
    $pass = $_POST["pass"];

    $query = "INSERT INTO usuarios (nombre, primerAp, segundoAp, telefono, correo, contrasena) VALUES ('$nombre', '$apP', '$apM', '$tel', '$email', '$pass')";

    if (mysqli_query($conn, $query)) {
        echo "Registro exitoso";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn)
?>