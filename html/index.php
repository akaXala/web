<?php session_start(); 
       include '../php/conexion.php'; // Include the database connection
// Redirect users without an active session
if (!isset($_SESSION['correo'])) {
    header("Location: login.html"); // Adjust the path as needed.
    exit;
    // Get the userId through the email
}
$email = $_SESSION['correo'];
// Perform a database query to retrieve the userId based on the email
// Replace 'your_database_table' with the actual table name in your database
$query = "SELECT id, permiso FROM usuarios WHERE correo = '$email'";
$result = mysqli_query($conn, $query);

if ($result) {
    // Check if a row is returned
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $userId = $row['id'];
        $permiso = $row['permiso'];

        // Check if the 'permiso' value is 1
        if ($permiso == 1) {
            header("Location: admin.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Web</title>
    <!-- JQuery -->
    <script src="../js/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- CSS -->
    <link href="../css/index.css?ver=2.0" rel="stylesheet">
    <!-- JS -->
    <script src="../js/index.js"></script>
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
                <form class="container-fluid justify-content-start d-flex align-items-center">
                    <p class="mb-0">Bienvenido: <?php echo $_SESSION['correo']; ?></p>
                    <a href="../php/logout.php" class="btn btn-danger ms-2">Cerrar sesión</a>
                    <a href="mostratCarrito.php" class="btn btn-danger ms-2">Mi Carrito</a>
                </form>
            </div>
        </div>
    </nav>
    <main>
        <div class="search-container" style="margin-bottom: 50px; margin-top: 10px;">
            <input type="text" id="productSearch" placeholder="Busca productos..." class="form-control">
        </div>
        <div id="products-container">
        </div>
        <a href="./JSON.html"><button type="button" class="btn btn-success">Revisa el JSON completo</button></a>
    </main>
    <footer>
        <p class="pie">Pendejos S.A de C.V</p>
    </footer>
</body>

</html>