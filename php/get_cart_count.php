<?php
session_start(); 
// Redirigir usuarios sin una sesión activa
if (!isset($_SESSION['correo'])) {
    header("Location: login.html"); // Ajusta la ruta según sea necesario
    exit;
}

require_once "../php/conexion.php";
$correo = $_SESSION['correo']; // Asegúrate de tener esta variable en la sesión

// Consulta para obtener el ID del usuario y el nombre desde el correo
$stmt = $conn->prepare("SELECT id, nombre FROM usuarios WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    $usuario_id = $user['id'];
    $nombre_usuario = $user['nombre'];
} else {
    // Si no se encuentra el usuario, redirigir o manejar el error
    echo json_encode(["status" => "error", "message" => "Usuario no encontrado."]);
    exit;
}

$stmt->close();

// Consulta para contar los artículos en el carrito
$stmt = $conn->prepare("SELECT COUNT(*) AS num_articulos FROM carritos WHERE idUsuario = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$num_articulos = $row['num_articulos']; // Cantidad de artículos en el carrito
$stmt->close();

echo json_encode(["status" => "success", "count" => $num_articulos]);
?>
