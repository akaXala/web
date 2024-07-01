<?php
session_start();
include '../php/conexion.php';

// Verificar si el usuario tiene una sesión activa y es un administrador
if (!isset($_SESSION['correo'])) {
    header("Location: ../html/login.html");
    exit;
}

$email = $_SESSION['correo'];
$query = "SELECT permiso FROM usuarios WHERE correo = '$email'";
$result = mysqli_query($conn, $query);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $permiso = $row['permiso'];
    if ($permiso != 1) {
        header("Location: ../html/index.php");
        exit;
    }
} else {
    header("Location: ../html/login.html");
    exit;
}

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $primerAp = mysqli_real_escape_string($conn, $_POST['primerAp']);
    $segundoAp = mysqli_real_escape_string($conn, $_POST['segundoAp']);
    $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
    $correo = mysqli_real_escape_string($conn, $_POST['correo']) . '@admin.com';
    $contrasena = $_POST['contrasena'];

    // Cifrar la contraseña
    $cipherMethod = 'AES-256-CBC';
    $key = 'cifrado'; // Generate a secure key
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipherMethod)); // Generate an initialization vector

    $cipherPass = openssl_encrypt($contrasena, $cipherMethod, $key, 0, $iv);
    if ($cipherPass === false) {
        die('Encryption failed: ' . openssl_error_string());
    }

    // Base64 encode the encrypted password and IV to ensure safe storage in the database
    $base64CipherPass = base64_encode($cipherPass);
    $base64Iv = base64_encode($iv);

    // Verificar si el correo ya está registrado
    $checkQuery = "SELECT id FROM usuarios WHERE correo = '$correo'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        $_SESSION['message'] = "This email is already registered.";
    } else {
        // Insertar el nuevo administrador en la base de datos
        $insertQuery = "INSERT INTO usuarios (nombre, primerAp, segundoAp, telefono, correo, contrasena, CipherPass, creditos, permiso)
                        VALUES ('$nombre', '$primerAp', '$segundoAp', '$telefono', '$correo', '$base64CipherPass', '$base64Iv', '10000', 1)";
        if (mysqli_query($conn, $insertQuery)) {
            $_SESSION['message'] = "Administrator registered successfully.";
        } else {
            $_SESSION['message'] = "Error registering administrator: " . mysqli_error($conn);
        }
    }

    // Redirigir de vuelta a la página de administradores
    header("Location: ../html/moreOptions.php");
    exit;
} else {
    $_SESSION['message'] = "Invalid request.";
    header("Location: ../html/moreOptions.php");
    exit;
}
?>
