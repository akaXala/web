<?php
include ('conexion.php');

$nombre = $_POST["nombre"];
$apP = $_POST["paterno"];
$apM = $_POST["materno"];
$tel = $_POST["tel"];
$email = $_POST["email"];
$pass = $_POST["pass"];

$cipherMethod = 'AES-256-CBC';
$key = openssl_random_pseudo_bytes(32); // Generate a secure key
$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipherMethod)); // Generate an initialization vector

$cipherPass = openssl_encrypt($pass, $cipherMethod, $key, 0, $iv);
if ($cipherPass === false) {
    die('Encryption failed: ' . openssl_error_string());
}

// Base64 encode the encrypted password and IV to ensure safe storage in the database
$base64CipherPass = base64_encode($cipherPass);
$base64Iv = base64_encode($iv);

// Update your query to insert the base64-encoded encrypted password and IV
$query = "INSERT INTO usuarios (nombre, primerAp, segundoAp, telefono, correo, contrasena, CipherPass) VALUES ('$nombre', '$apP', '$apM', '$tel', '$email', '$base64CipherPass', '$base64Iv')";

if (mysqli_query($conn, $query)) {
    echo "Registro exitoso";
} else {
    echo "Error: " . $query . "<br>" . mysqli_error($conn);
}

mysqli_close($conn)
    ?>