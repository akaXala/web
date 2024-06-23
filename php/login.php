<?php
session_start(); // Start the session at the beginning
include('conexion.php');

$correo = $_POST["txtusuario"];
$pass = $_POST["txtpassword"];

// Debug log
error_log("Login attempt: $correo");

$query = "SELECT contrasena, CipherPass FROM usuarios WHERE correo = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $correo);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $encryptedPass = base64_decode($row['contrasena']);
    $iv = base64_decode($row['CipherPass']);
    $decryptionKey = 'cifrado';
    $decryptedPass = openssl_decrypt($encryptedPass, 'AES-256-CBC', $decryptionKey, 0, $iv);

    // Debug log
    error_log("Decrypted password for $correo: $decryptedPass");

    if ($pass === $decryptedPass) {
        // Set session variables
        $_SESSION["loggedin"] = true;
        $_SESSION["correo"] = $correo;

        // Debug log
        error_log("Login successful: $correo");

        // Redirect user to welcome page
        header("location: ../html/index.php");
        exit;
    } else {
        echo "No ingreso, contraseña incorrecta.";
        // Debug log
        error_log("Login failed for $correo: Incorrect password");
    }
} else {
    echo "No ingreso, usuario no existe.";
    // Debug log
    error_log("Login failed: User $correo does not exist");
}

mysqli_close($conn);
?>