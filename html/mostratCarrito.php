<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS -->
    <link href="../css/index.css?ver=2.0" rel="stylesheet">
    <!-- ICONO -->
    <link rel="icon" href="../imgs/icono.ico" type="image/x-icon">
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
                La tienda
            </a>
            <div class="ms-auto">
                <form class="container-fluid justify-content-start">
                </form>
            </div>
        </div>
    </nav>
    <main class="container mt-5">
        <?php
        session_start(); // Start the session
        include '../php/conexion.php'; // Include the database connection
        
        if (isset($_SESSION['correo'])) {
            $email = $_SESSION['correo'];
            $queryUserId = "SELECT id FROM usuarios WHERE correo = '$email'";
            $resultUserId = mysqli_query($conn, $queryUserId);
            if ($rowUserId = mysqli_fetch_assoc($resultUserId)) {
                $userId = $rowUserId['id'];
                $queryCart = "SELECT p.titulo, p.precio, p.miniatura, c.idProducto FROM productos p INNER JOIN carritos c ON p.id = c.idProducto WHERE c.idUsuario = '$userId'";
                $resultCart = mysqli_query($conn, $queryCart);
                if (mysqli_num_rows($resultCart) > 0) {
                    $totalPrice = 0; // Initialize total price
                    echo '<table class="table">';
                    echo '<thead><tr><th>Producto</th><th>Precio</th><th>Thumbnail</th><th>Acciones</th></tr></thead>';
                    echo '<tbody>';
                    while ($rowCart = mysqli_fetch_assoc($resultCart)) {
                        echo "<tr><td>{$rowCart['titulo']}</td><td>{$rowCart['precio']}</td><td><img src='{$rowCart['miniatura']}' width='50' height='50'></td><td><a href='../php/deleteFromCart.php?idProducto={$rowCart['idProducto']}' class='btn btn-danger'>Eliminar</a></td></tr>";
                        $totalPrice += $rowCart['precio']; // Add item price to total
                    }
                    echo '</tbody></table>';
                    // Display total price
                    echo "<div style='text-align: center; padding: 20px;'><strong>Total: $$totalPrice</strong></div>";
                    echo "<div style='text-align: center; padding: 20px;'><a href='../php/buy.php' class='btn btn-success' style='padding: 10px 20px; font-size: 16px;'>Buy</a></div>";

                } else {
                    echo "<p>No hay productos en el carrito.</p>";
                }
            } else {
                echo "<p>Error al obtener el usuario.</p>";
            }
        } else {
            echo "<p>Usuario no identificado. <a href='./login.html'>Iniciar sesión</a></p>";
        }
?>
    </main>
    <footer class="footer mt-auto py-3 bg-light">
        <div class="container">
            <span class="text-muted">Pendejos S.A de C.V</span>
        </div>
    </footer>
</body>

</html>