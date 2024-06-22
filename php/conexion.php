<?php
    $conn = mysqli_connect("localhost", "root", "", "onlinestore");

    if (!$conn){
        die("No hay conexión: " .mysqli_connect_error());
    }
?>