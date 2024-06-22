<?php
    // Create connection
    $conn = mysqli_connect("localhost", "root", "", "login");

    if (!$conn){
        die("No hay conexión: " .mysqli_connect_error());
    }

    $nombre = $_POST["txtusuario"];
    $pass = $_POST["txtpassword"];

    $query = mysqli_query($conn, "SELECT * FROM Usuario where noBoleta = '".$nombre."' and password = '".$pass."'");
    $nr = mysqli_num_rows($query);

    if ($nr == 1){
        echo "Bienvenido: " .$nombre;
    } else if ($nr == 0){
        echo "No ingreso, usuario no existe.";
    }
?>