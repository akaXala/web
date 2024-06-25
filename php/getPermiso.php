<?php
session_start(); // Inicia o reanuda una sesión

// Incluye el script de conexión a la base de datos
require_once 'conexion.php';

// Verifica si el email del usuario está en la sesión
if (isset($_SESSION['correq'])) {
    $userEmail = $_SESSION['correo']; // Obtiene el email del usuario de la sesión

    // Prepara la consulta SQL para obtener el ID del usuario basado en el email
    $queryForId = "SELECT id FROM usuarios WHERE correo = ?"; // Asume que tu tabla 'usuarios' tiene una columna 'email'

    // Prepara la sentencia para obtener el ID
    if ($stmtForId = mysqli_prepare($conn, $queryForId)) {
        // Vincula los parámetros para los marcadores
        mysqli_stmt_bind_param($stmtForId, "s", $userEmail);

        // Ejecuta la consulta
        mysqli_stmt_execute($stmtForId);

        // Vincula las variables de resultado
        mysqli_stmt_bind_result($stmtForId, $userId);

        // Obtiene los valores
        if (mysqli_stmt_fetch($stmtForId)) {
            // Cierra la sentencia
            mysqli_stmt_close($stmtForId);

            // Ahora que tenemos el ID del usuario, podemos obtener el permiso
            $queryForPermiso = "SELECT permiso FROM usuarios WHERE id = ?";
            if ($stmtForPermiso = mysqli_prepare($conn, $queryForPermiso)) {
                mysqli_stmt_bind_param($stmtForPermiso, "i", $userId);
                mysqli_stmt_execute($stmtForPermiso);
                mysqli_stmt_bind_result($stmtForPermiso, $permiso);

                if (mysqli_stmt_fetch($stmtForPermiso)) {
                    echo json_encode(['permiso' => $permiso]);
                } else {
                    echo json_encode(['error' => 'Permiso no encontrado.']);
                }
                mysqli_stmt_close($stmtForPermiso);
            } else {
                echo json_encode(['error' => 'Error al preparar la consulta de permiso.']);
            }
        } else {
            echo json_encode(['error' => 'Email no encontrado.']);
            mysqli_stmt_close($stmtForId);
        }
    } else {
        echo json_encode(['error' => 'Error al preparar la consulta de ID.']);
    }
} else {
    echo json_encode(['error' => 'Usuario no autenticado.']);
}

// Cierra la conexión
mysqli_close($conn);
?>