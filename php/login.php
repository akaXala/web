<?php
include('conexion.php');

$correo = $_POST["txtusuario"];
$pass = $_POST["txtpassword"];

// Fetch the encrypted password and IV for the user
$query = "SELECT contrasena, CipherPass FROM usuarios WHERE correo = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $correo);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $encryptedPass = base64_decode($row['contrasena']);
    $iv = base64_decode($row['CipherPass']);

    // Assuming you have the decryption key stored securely
    $decryptionKey = 'cifrado'; // The same key used for encryption

    // Decrypt the password
    $decryptedPass = openssl_decrypt($encryptedPass, 'AES-256-CBC', $decryptionKey, 0, $iv);

    // Compare the decrypted password with the input password
    if ($pass === $decryptedPass) {
        echo "Bienvenido: " . htmlspecialchars($correo);
    } else {
        echo "No ingreso, contraseña incorrecta.";
    }
} else {
    echo "No ingreso, usuario no existe.";
}

mysqli_close($conn);
?>