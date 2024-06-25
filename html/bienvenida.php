<?php
    include('../php/conexion.php');

    // Obtener el ID del usuario desde la URL
    if (isset($_GET['userID'])) {
        $userId = intval($_GET['userID']);
        if ($userId > 0) {
            // Ajustar la consulta para usar el ID recibido
            $query = "SELECT nombre FROM usuarios WHERE id = $userId";
            $result = mysqli_query($conn, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $nombre = $row['nombre'];
            } else {
                $nombre = "Invitado"; // Valor predeterminado en caso de error o no encontrar el usuario
            }
        } else {
            $nombre = "Invitado"; // ID no válido
        }
    } else {
        $nombre = "Invitado"; // Valor predeterminado si no se recibe el ID
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenida</title>
    <link href="../css/bienvenida.css" rel="stylesheet">
    <script src="../js/bienvenida.js"></script>
</head>
<body>
    <div class="custom-container">
        <h1 class="custom-h1">Bienvenido</h1>
        <h2 class="custom-h2"><?php echo htmlspecialchars($nombre); ?></h2>
        <h3 class="custom-h3">Gracias por registrarte en nuestra página</h3>
        <h3 class="custom-h3">Ahora ya puedes iniciar sesión</h3>
    </div>
</body>
</html>
