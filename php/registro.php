<?php
include ('conexion.php');

$nombre = $_POST["nombre"];
$apP = $_POST["paterno"];
$apM = $_POST["materno"];
$tel = $_POST["tel"];
$email = $_POST["email"];
$pass = $_POST["pass"];

$cipherMethod = 'AES-256-CBC';
$key = 'cifrado'; // Generate a secure key
$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipherMethod)); // Generate an initialization vector

$cipherPass = openssl_encrypt($pass, $cipherMethod, $key, 0, $iv);
if ($cipherPass === false) {
    die('Encryption failed: ' . openssl_error_string());
}

// Base64 encode the encrypted password and IV to ensure safe storage in the database
$base64CipherPass = base64_encode($cipherPass);
$base64Iv = base64_encode($iv);

// Verificar si el correo o el número de teléfono ya existen en la base de datos
$checkQuery = "SELECT * FROM usuarios WHERE correo = '$email' OR telefono = '$tel'";
$result = mysqli_query($conn, $checkQuery);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    if ($row['correo'] == $email) {
        echo json_encode(array("status" => "error", "field" => "correo", "message" => "El correo ya está registrado."));
    } elseif ($row['telefono'] == $tel) {
        echo json_encode(array("status" => "error", "field" => "telefono", "message" => "El número de teléfono ya está registrado."));
    }
} else {
    // Si no existen, procede con la inserción
    $query = "INSERT INTO usuarios (nombre, primerAp, segundoAp, telefono, correo, contrasena, CipherPass) VALUES ('$nombre', '$apP', '$apM', '$tel', '$email', '$base64CipherPass', '$base64Iv')";
    if (mysqli_query($conn, $query)) {
        $userID = mysqli_insert_id($conn);
        echo json_encode(array("status" => "success", "userID" => $userID, "message" => "Registro exitoso."));
    } else {
        echo json_encode(array("status" => "error", "message" => "Error: " . mysqli_error($conn)));
    }
}

mysqli_close($conn);
?>
