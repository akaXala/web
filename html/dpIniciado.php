<?php session_start(); 
// Redirect users without an active session
if (!isset($_SESSION['correo'])) {
    header("Location: login.html"); // Adjust the path as needed.
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="../css/detallesProducto.css" rel="stylesheet">
    <!-- ICONO -->
    <link rel="icon" href="../imgs/icono.ico" type="image/x-icon">
</head>
<body>
    <header>
        <!-- Aquí puedes poner un header si es necesario -->
    </header>
    <main class="container mt-5">
        <div id="product-details"></div>
    </main>
    <script src="../js/detallesProducto.js"></script>
</body>
</html>