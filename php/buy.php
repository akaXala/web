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
                $queryProducts = "SELECT c.idProducto, p.precio, p.stock, p.descuento FROM carritos c INNER JOIN productos p ON c.idProducto = p.id WHERE c.idUsuario = '$userId'";
                $resultProducts = mysqli_query($conn, $queryProducts);

                $productIds = [];
                $productPrices = [];
                $productStocks = [];
                $productDiscounts = [];

                while ($rowProduct = mysqli_fetch_assoc($resultProducts)) {
                    $productIds[] = $rowProduct['idProducto'];
                    $productPrices[] = $rowProduct['precio'];
                    $productStocks[] = $rowProduct['stock'];
                    $productDiscounts[] = $rowProduct['descuento'];
                }

                $discountedPrices = [];
                $totalPrice = 0;
                foreach ($productPrices as $index => $price) {
                    $discount = $productDiscounts[$index];
                    $discountedPrice = $price - (($price * $discount) / 100);
                    $discountedPrices[] = $discountedPrice;
                    $totalPrice += $discountedPrice;
                }

                $conn->autocommit(FALSE); // Start transaction

                $insertOrderQuery = "INSERT INTO orden (fecha, usuario_id) VALUES (NOW(), '$userId')";
                if ($conn->query($insertOrderQuery) === TRUE) {
                    $orderId = $conn->insert_id;

                    $allQueriesSuccessful = true;
                    foreach ($productIds as $index => $productId) {
                        if ($productStocks[$index] > 0) {
                            $updateStockQuery = "UPDATE productos SET stock = stock - 1 WHERE id = '$productId'";
                            $insertProductQuery = "INSERT INTO orden_id_prod (id, id_prod) VALUES ($orderId, $productId)";

                            if (!$conn->query($updateStockQuery) || !$conn->query($insertProductQuery)) {
                                $allQueriesSuccessful = false;
                                break;
                            }
                        } else {
                            $allQueriesSuccessful = false;
                            break;
                        }
                    }

                    if ($allQueriesSuccessful) {
                        $updateCreditosQuery = "UPDATE usuarios SET creditos = creditos - $totalPrice WHERE id = '$userId'";
                        if ($conn->query($updateCreditosQuery)) {
                            $clearCartQuery = "DELETE FROM carritos WHERE idUsuario = '$userId'";
                            if ($conn->query($clearCartQuery)) {
                                $conn->commit(); // Commit transaction

                                echo '<div class="alert alert-success" role="alert">Compra realizada con éxito.</div>';
                                echo '<div style="text-align: center; padding: 20px;">';
                                echo '<a href="../html/index.php" class="btn btn-primary" style="padding: 10px 20px; font-size: 16px;">Regresar al inicio</a>';
                                echo "<a href='../php/generarPDF.php?orderId=$orderId&userId=$userId' class='btn btn-success ms-2' target='_blank' style='padding: 10px 20px; font-size: 16px;'>Ver Factura</a>";
                                echo '</div>';
                            } else {
                                $conn->rollback(); // Rollback transaction
                                echo "Error al limpiar el carrito.";
                            }
                        } else {
                            $conn->rollback(); // Rollback transaction
                            echo "Error actualizando los créditos del usuario.";
                        }
                    } else {
                        $conn->rollback(); // Rollback transaction
                        echo "Error en el proceso de compra.";
                    }
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
        <!-- Footer content -->
        </div>
    </footer>
</body>
</html>
