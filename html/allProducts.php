<?php
session_start(); 
// Redirigir usuarios sin una sesión activa
if (!isset($_SESSION['correo'])) {
    header("Location: login.html"); // Ajusta la ruta según sea necesario
    exit;
}

require_once "../php/conexion.php";
$correo = $_SESSION['correo']; // Asegúrate de tener esta variable en la sesión

// Consulta para obtener el ID del usuario y el nombre desde el correo
$stmt = $conn->prepare("SELECT id, nombre FROM usuarios WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    $usuario_id = $user['id'];
    $nombre_usuario = $user['nombre'];
} else {
    // Si no se encuentra el usuario, redirigir o manejar el error
    echo "Usuario no encontrado.";
    exit;
}

$stmt->close();

// Consulta para contar los artículos en el carrito
$stmt = $conn->prepare("SELECT COUNT(*) AS num_articulos FROM carritos WHERE idUsuario = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$num_articulos = $row['num_articulos']; // Cantidad de artículos en el carrito
$stmt->close();
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- JS -->
    <script src="../js/index.js"></script>
    <script src="../js/components.js"></script>
    <script src="../js/allProducts.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary navbar-light py-2 fixed-top custom-toggler">
        <div class="container">
            <!-- LOGO y Texto -->
            <a class="navbar-brand flex-grow-0 me-1" href="./index.php#">
                <img src="../imgs/logo1.png" alt="Logo" width="40" height="40"
                    class="d-inline-block align-middle">XalaStore
            </a>
            <!-- Barra de busqueda -->
            <form class="d-flex flex-grow-1 w-25" role="search" method="get">
                <input id="productSearch" class="form-control" type="search" placeholder="Search">
                <button class="btn position-relative pe-1" type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
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
                        <li class="nav-item"><a class="nav-link" href="./index.php#offers">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="./index.php#categories">Category</a></li>
                        <li class="nav-item"><a class="nav-link" href="./index.php#popular">Popular</a></li>
                    </ul>
                </div>
            </div>
            <!-- Carrito de compras y favoritos -->
            <div class="nav-btns flex-grow-0 pe-3">
                <a href="./mostratCarrito.php">
                    <button type="button" class="btn position-relative">
                        <i class="fa fa-shopping-cart"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge bg-primary"><?php echo $num_articulos; ?></span>
                    </button>
                </a>
            </div>
            <div class="dropdown">
                <button class="btn border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-user"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end ">
                    <li><p class="d-flex justify-content-center">Welcome</p></li>
                    <li><p class="d-flex justify-content-center"><?php echo $nombre_usuario; ?></p></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item py-2" href="#">Purchases</a></li>
                    <li><form class="d-flex justify-content-center">
                        <a href="../php/logout.php">
                            <button class="btn btn-outline-success me-2" type="button">Log out</button>
                        </a>
                    </form></li>
                </ul>
            </div>     
            <!-- boton retraible -->
            <button class="navbar-toggler flex-grow-0 px-0 py-0" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>
    <section>
        <div id="products-container">
        </div>
    </section>

    <footer-js></footer-js>
</body>

</html>