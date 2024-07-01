<?php 
session_start(); 
include '../php/conexion.php'; // Include the database connection

// Redirect users without an active session
if (!isset($_SESSION['correo'])) {
    header("Location: ./login.html"); // Adjust the path as needed.
    exit;
}

$email = $_SESSION['correo'];

// Perform a database query to retrieve the userId based on the email
$query = $conn->prepare("SELECT id, permiso, nombre FROM usuarios WHERE correo = ?");
$query->bind_param("s", $email);
$query->execute();
$result = $query->get_result();

if ($result) {
    // Check if a row is returned
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userId = $row['id'];
        $permiso = $row['permiso'];
        $nombre = $row['nombre'];
        // Check if the 'permiso' value is 1
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

// If the user has the right permission, retrieve the product data
if (isset($_GET['productoId'])) {
    $productoId = $_GET['productoId'];
    $query = $conn->prepare("SELECT * FROM productos WHERE id = ?");
    $query->bind_param("i", $productoId);
    $query->execute();
    $result = $query->get_result();

    if ($result && $result->num_rows > 0) {
        $product = $result->fetch_assoc();
        // Display the form with the product data
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Modify Product</title>
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        </head>
        <body>
            <div class="container">
                <h1>Modify Product</h1>
                <form action="../php/updateProducto.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                    <div class="form-group">
                        <label for="titulo">Title</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo $product['titulo']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="precio">Price</label>
                        <input type="text" class="form-control" id="precio" name="precio" value="<?php echo $product['precio']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="descuento">Discount</label>
                        <input type="text" class="form-control" id="descuento" name="descuento" value="<?php echo $product['descuento']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="stock">Stock</label>
                        <input type="text" class="form-control" id="stock" name="stock" value="<?php echo $product['stock']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="miniatura">Thumbnail URL</label>
                        <input type="text" class="form-control" id="miniatura" name="miniatura" value="<?php echo $product['miniatura']; ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo "Product not found.";
    }
} else {
    echo "Invalid request.";
}
?>
