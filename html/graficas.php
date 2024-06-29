<?php 
session_start(); 
include '../php/conexion.php'; // Include the database connection

// Redirect users without an active session
if (!isset($_SESSION['correo'])) {
    header("Location: login.html"); // Adjust the path as needed.
    exit;   
}

$email = $_SESSION['correo'];
// Perform a database query to retrieve the userId based on the email
$query = "SELECT id, permiso FROM usuarios WHERE correo = '$email'";
$result = mysqli_query($conn, $query);

if ($result) {
    // Check if a row is returned
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $userId = $row['id'];
        $permiso = $row['permiso'];
        // Check if the 'permiso' value is 1 and the userId is not 1
        if ($permiso != 1) {
            header("Location: index.php"); // Redirect to index.html if conditions are not met
            exit;
        }
    } else {
        // If no row is returned, redirect to login.html
        header("Location: login.html");
        exit;
    }
} else {
    // If the query fails, redirect to login.html
    header("Location: login.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- ICONO -->
    <link rel="icon" href="../imgs/icono.ico" type="image/x-icon">
    <title>Dashboard</title>
    <style>
        .graph-container {
            width: 48%;
            margin: auto;
            text-align: center;
        }
        .graph-container button {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <header>
        <marquee behavior="scroll" direction="left" class="marquee">
            <span class="marquee-text" style="margin-right: 800px;">Bienvenido a la tienda en línea</span>
            <span class="marquee-text">Encuentra las mejores ofertas</span>
        </marquee>
    </header>
    <nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="../imgs/logo1.png" alt="Logo" width="40" height="40" class="d-inline-block align-text-top">
                La tienda - Admin
            </a>
            <div class="ms-auto">
                <form class="container-fluid justify-content-start d-flex align-items-center">
                    <a href="../php/logout.php" class="btn btn-danger ms-2">Cerrar sesión</a>
                </form>
            </div>
        </div>
    </nav>
    <div style="display: flex; justify-content: space-between; padding: 20px;">
        <div class="graph-container">
            <iframe src="graficas_logins.php" style="width: 100%; height: 400px; border: none;"></iframe>
            <button class="btn btn-primary mt-2" style="background-color: rgba(75, 192, 192, 1);" onclick="window.open('graficas_logins.php', '_blank')">Ver gráfica de compras en grande</button>
        </div>
        <div class="graph-container">
            <iframe src="graficas_compras.php" style="width: 100%; height: 400px; border: none;"></iframe>
            <button class="btn btn-primary mt-2" style="background-color: rgba(255, 99, 132, 1);" onclick="window.open('graficas_compras.php', '_blank')">Ver gráfica de logins en grande</button>
        </div>
    </div>
</body>
</html>
