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
                <form class="container-fluid justify-content-start"></form>
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
                // Modified query to join with the products table and select idProducto, price, and stock
                $queryProducts = "SELECT c.idProducto, p.precio, p.stock, p.descuento FROM carritos c INNER JOIN productos p ON c.idProducto = p.id WHERE c.idUsuario = '$userId'";
                $resultProducts = mysqli_query($conn, $queryProducts);

                $productIds = [];
                $productPrices = [];
                $productStocks = [];

                while ($rowProduct = mysqli_fetch_assoc($resultProducts)) {
                    $productIds[] = $rowProduct['idProducto']; // Store each product ID in the array
                    $productPrices[] = $rowProduct['precio']; // Store each product price in the array
                    $productStocks[] = $rowProduct['stock']; // Store each product stock in the array
                    $productDiscounts[] = $rowProduct['descuento']; // Store each product discount in the array
                }
                // Calculate discounted prices and total price
                $discountedPrices = [];
                $totalPrice = 0;
                foreach ($productPrices as $index => $price) {
                    $discount = $productDiscounts[$index];
                    $discountedPrice = $price - (($price * $discount) / 100);
                    $discountedPrices[] = $discountedPrice;
                    $totalPrice += $discountedPrice;
                }

                // Reduce stock by 1 for each product
                foreach ($productIds as $productId) {
                    $checkStockQuery = "SELECT stock FROM productos WHERE id = '$productId' AND stock > 0";
                    $resultCheckStock = mysqli_query($conn, $checkStockQuery);
                    if (mysqli_num_rows($resultCheckStock) > 0) {
                        $updateStockQuery = "UPDATE productos SET stock = stock - 1 WHERE id = '$productId'";
                        $resultUpdateStock = mysqli_query($conn, $updateStockQuery);
                        if ($resultUpdateStock) {

                            // Subtract the total price from the user's "creditos"
                            $updateCreditosQuery = "UPDATE usuarios SET creditos = creditos - $totalPrice WHERE id = '$userId'";
                            $resultUpdateCreditos = mysqli_query($conn, $updateCreditosQuery);
                            if (!$resultUpdateCreditos) {
                                echo "Error updating user's creditos.";
                            }

                            // Call the "orden.php" script and pass the arrays as parameters
                            $url = 'http://localhost/web/php/orden.php';
                            $data = array(
                                'productIds' => $productIds,
                                'productPrices' => $productPrices,
                                'productStocks' => $productStocks,
                                'totalPrice' => $totalPrice,
                                'userId' => $userId
                            );

                            $options = array(
                                'http' => array(
                                    'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                                    'method' => 'POST',
                                    'content' => http_build_query($data)
                                )
                            );

                            $context = stream_context_create($options);
                            $result = file_get_contents($url, false, $context);

                            if ($result === false) {
                                echo "Error calling the 'orden.php' script.";
                            } else {
                                echo $result;
                            }
                        } else {
                        if (!$resultUpdateStock) {
                            echo "Error updating stock for product ID: $productId<br>";
                        }
                    
                }
            }
        }

                foreach ($productIds as $productId) {
                    // Check if the product ID is already in the "numeroCompras" table
                    $checkProductQuery = "SELECT * FROM numeroCompras WHERE idProducto = '$productId'";
                    $resultCheckProduct = mysqli_query($conn, $checkProductQuery);

                    if (mysqli_num_rows($resultCheckProduct) > 0) {
                        // If the product ID is already in the table, increment the "compras" value by 1
                        $updateProductQuery = "UPDATE numeroCompras SET compras = compras + 1 WHERE idProducto = '$productId'";
                        $resultUpdateProduct = mysqli_query($conn, $updateProductQuery);
                        if (!$resultUpdateProduct) {
                            echo "Error updating product's number of purchases for product ID: $productId<br>";
                        }
                    } else {
                        // If the product ID is not in the table, insert a new row with the product ID and set "compras" to 1
                        $insertProductQuery = "INSERT INTO numeroCompras (idProducto, compras) VALUES ('$productId', 1)";
                        $resultInsertProduct = mysqli_query($conn, $insertProductQuery);
                        if (!$resultInsertProduct) {
                            echo "Error inserting product into the 'numeroCompras' table for product ID: $productId<br>";
                        }
                    }
                }

                // Subtract the total price from the user's "creditos"
                $updateCreditosQuery = "UPDATE usuarios SET creditos = creditos - $totalPrice WHERE id = '$userId'";
                $resultUpdateCreditos = mysqli_query($conn, $updateCreditosQuery);
                if (!$resultUpdateCreditos) {
                    echo "Error updating user's creditos.";
                }

                // Insert the order into the `orden` table and get the order ID
                $insertOrderQuery = "INSERT INTO orden (fecha, usuario_id) VALUES (NOW(), '$userId')";
                if ($conn->query($insertOrderQuery) === TRUE) {
                    $orderId = $conn->insert_id;

                    // Insert product IDs into the `orden_id_prod` table
                    foreach ($productIds as $productId) {
                        $insertProductQuery = "INSERT INTO orden_id_prod (id, id_prod) VALUES ($orderId, $productId)";
                        if ($conn->query($insertProductQuery) !== TRUE) {
                            echo "Error al insertar el producto $productId: " . $conn->error;
                        }
                    }

                    // Clear the cart after the purchase
                    $clearCartQuery = "DELETE FROM carritos WHERE idUsuario = '$userId'";
                    $resultClearCart = mysqli_query($conn, $clearCartQuery);
                    if (!$resultClearCart) {
                        echo "Error al limpiar el carrito.<br>";
                    }

                    // Show confirmation message with buttons
                    echo '<div class="alert alert-success" role="alert">';
                    echo 'Compra realizada con éxito.';
                    echo '</div>';
                    echo '<div style="text-align: center; padding: 20px;">';
                    echo '<a href="../html/index.php" class="btn btn-primary" style="padding: 10px 20px; font-size: 16px;">Regresar al inicio</a>';
                    echo "<a href='../php/generarPDF.php?orderId=$orderId&userId=$userId' class='btn btn-success ms-2' style='padding: 10px 20px; font-size: 16px;'>Ver Factura</a>";
                    echo '</div>';
                } else {
                    echo "Error al insertar la orden: " . $conn->error;
                }
            } else {
                echo "<p>Error al obtener el usuario.</p>";
            }
        } else {
            echo "<p>Usuario no identificado. <a href='../html/login.html'>Iniciar sesión</a></p>";
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