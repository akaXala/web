<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra Confirmada</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../css/index.css?ver=2.0" rel="stylesheet">
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
                <img src="../imgs/logo1.jpg" alt="Logo" width="40" height="40" class="d-inline-block align-text-top">
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
        // Start of the PHP code
        session_start(); // Start the session
        include '../php/conexion.php'; // Include the database connection
        
        $productIds = []; // Initialize an empty array to store product IDs
        $productPrices = []; // Initialize an empty array to store product prices
        $productStocks = []; // Initialize an empty array to store product stocks
        
        if (isset($_SESSION['correo'])) {
            $email = $_SESSION['correo'];
            $queryUserId = "SELECT id FROM usuarios WHERE correo = '$email'";
            $resultUserId = mysqli_query($conn, $queryUserId);
            if ($rowUserId = mysqli_fetch_assoc($resultUserId)) {
                $userId = $rowUserId['id'];
                // Modified query to join with the products table and select idProducto, price, and stock
                $queryProducts = "SELECT c.idProducto, p.precio, p.stock FROM carritos c INNER JOIN productos p ON c.idProducto = p.id WHERE c.idUsuario = '$userId'";
                $resultProducts = mysqli_query($conn, $queryProducts);
                while ($rowProduct = mysqli_fetch_assoc($resultProducts)) {
                    $productIds[] = $rowProduct['idProducto']; // Store each product ID in the array
                    $productPrices[] = $rowProduct['precio']; // Store each product price in the array
                    $productStocks[] = $rowProduct['stock']; // Store each product stock in the array
                }
                // Reduce stock by 1 for each product
                foreach ($productIds as $productId) {
                    $updateStockQuery = "UPDATE productos SET stock = stock - 1 WHERE id = '$productId'";
                    $resultUpdateStock = mysqli_query($conn, $updateStockQuery);
                    if (!$resultUpdateStock) {
                        echo "Error updating stock for product ID: $productId<br>";
                    }
                }
                echo '<div class="alert alert-success" role="alert">';
                echo 'Compra realizada con éxito.';
                echo '</div>';
                // Clear the cart after the purchase
                $clearCartQuery = "DELETE FROM carritos WHERE idUsuario = '$userId'";
                $resultClearCart = mysqli_query($conn, $clearCartQuery);
                if (!$resultClearCart) {
                    echo "Error al limpiar el carrito.<br>";
                }
            } else {
                echo "<p>Error al obtener el usuario.</p>";
            }
        } else {
            echo "<p>Usuario no identificado. <a href='./login.html'>Iniciar sesión</a></p>";
        }
        // Print product IDs, prices, and stocks for debugging
        foreach ($productIds as $index => $productId) {
            echo "Product ID: " . $productId . ", Price: " . $productPrices[$index] . ", Stock: " . $productStocks[$index] . "<br>";
        }
        ?>
    </main>
    <footer class="footer mt-auto py-3 bg-light">
        <!-- Similar footer as in mostrarCarrito.php -->
    </footer>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>