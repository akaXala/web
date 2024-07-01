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

$queryProductos = "SELECT id, titulo, precio, descuento, stock, miniatura FROM productos";
$resultProductos = mysqli_query($conn, $queryProductos);

// Array to store the query results
$productData = [];

if ($resultProductos) {
    while ($product = mysqli_fetch_assoc($resultProductos)) {
        $productData[] = $product;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- CSS -->
    <link href="../css/index.css?ver=2.0" rel="stylesheet">
    <!-- ICONO -->
    <link rel="icon" href="../imgs/icono.ico" type="image/x-icon">
    <title>Administrar Productos</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary navbar-light py-2 fixed-top custom-toggler">
        <div class="container">
            <!-- LOGO y Texto -->
            <a class="navbar-brand flex-grow-0 me-1" href="#">
                <img src="../imgs/logo1.png" alt="Logo" width="40" height="40" class="d-inline-block align-middle">XalaStore - Admin
            </a>
            <!-- sub menu retraible -->
            <div class="offcanvas offcanvas-end flex-grow-0" tabindex="-1" id="offcanvasNavbar"
                aria-labelledby="offcanvasNavbarLabel">
                <!-- Titulo del submenu -->
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <!-- Items retraibles -->
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end">
                        <li class="nav-item"><a class="nav-link" href="admin.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="PDF.php">Bills</a></li>
                        <li class="nav-item"><a class="nav-link" href="productos.php">Manage Products</a></li>
                        <li class="nav-item"><a class="nav-link" href="moreOptions.php">More Options</a></li>
                    </ul>
                    <!-- login -->
                    <form class="flex-grow-0">
                        <a href="../php/logout.php" class="btn btn-danger">Log out</a>
                    </form>
                </div>
            </div>
            <!-- boton retraible -->
            <button class="navbar-toggler flex-grow-0 px-0 py-0" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>
    <div class="container mt-4">
        <h2 class="text-center">Store products</h2>
        <!-- Búsqueda y Ordenación -->
        <div class="row mb-3">
            <div class="col-md-4">
                <input type="text" id="searchInput" class="form-control" placeholder="Search by Title">
            </div>
            <div class="col-md-4">
                <select id="sortOrder" class="form-select">
                    <option value="id_asc">Ascending ID</option>
                    <option value="id_desc">Descending ID</option>
                    <option value="price_asc">Ascending Price</option>
                    <option value="price_desc">Descending Price</option>
                    <option value="stock_asc">Ascending Stock</option>
                    <option value="stock_desc">Descending Stock</option>
                </select>
            </div>
            <div class="col-md-4">
                <button id="applyFilters" class="btn btn-primary w-100">Apply Filters</button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm">
                <caption>Lista de Productos</caption>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Price</th>
                        <th>Discount</th>
                        <th>Stock</th>
                        <th>Thumbnail</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="productTableBody" class="table-group-divider">
                    <!-- Las filas de la tabla se llenarán dinámicamente con JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Pasar los datos de PHP a JavaScript
        const productData = <?php echo json_encode($productData); ?>;
    </script>
    <script src="../js/mostrarProductos.js"></script>
</body>
</html>
