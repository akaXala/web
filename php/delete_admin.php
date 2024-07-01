<?php
session_start();
include '../php/conexion.php';

// Verificar si el usuario tiene una sesión activa y es un administrador
if (!isset($_SESSION['correo'])) {
    header("Location: login.html");
    exit;
}

$email = $_SESSION['correo'];
$query = "SELECT permiso FROM usuarios WHERE correo = '$email'";
$result = mysqli_query($conn, $query);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $permiso = $row['permiso'];
    if ($permiso != 1) {
        header("Location: index.php");
        exit;
    }
} else {
    header("Location: login.html");
    exit;
}

// Verificar si se ha enviado el ID del administrador a eliminar
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $adminId = $_POST['id'];

    // Eliminar el administrador de la base de datos
    $deleteQuery = "DELETE FROM usuarios WHERE id = '$adminId' AND permiso = 1";
    if (mysqli_query($conn, $deleteQuery)) {
        $_SESSION['message'] = "Administrator deleted successfully.";
    } else {
        $_SESSION['message'] = "Error deleting administrator: " . mysqli_error($conn);
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
