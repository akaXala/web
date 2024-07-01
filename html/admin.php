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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- CSS -->
    <link href="../css/index.css?ver=2.0" rel="stylesheet">
    <!-- ICONO -->
    <link rel="icon" href="../imgs/icono.ico" type="image/x-icon">
    <style>
        .graph-container {
            width: 48%;
        }
        .graph-row {
            display: flex;
            justify-content: space-between;
            padding: 20px;
        }
    </style>
</head>
<body class="sin-id">
    <header>
        <marquee behavior="scroll" direction="left" class="marquee">
            <span class="marquee-text" style="margin-right: 800px;">Bienvenido al Panel de Administración</span>
        </marquee>
    </header>
    <nav class="navbar navbar-expand-lg bg-body-tertiary navbar-light py-2 fixed-top custom-toggler">
        <div class="container">
            <!-- LOGO y Texto -->
            <a class="navbar-brand flex-grow-0 me-1" href="#">
                <img src="../imgs/logo1.png" alt="Logo" width="40" height="40" class="d-inline-block align-middle">XalaStore - Admin
            </a>
            <!-- sub menu retraible -->
            <div class="offcanvas offcanvas-end flex-grow-0" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
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
            <button class="navbar-toggler flex-grow-0 px-0 py-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>
    <main class="text-center mt-5">
        <h1 class="display-2">Welcome Admin</h1>
        <p class="display-4"><?php echo htmlspecialchars($nombre); ?></p><br><br>
        <h1 class="display-5">Statistics Charts</h1>
        <div class="graph-row">
            <div class="graph-container">
                <h1 class="formTitle">Registrations last 3 days</h1>
                <iframe src="GL_thumbnail.php" style="width: 100%; height: 240px; border: none;"></iframe>
                <button class="btn btn-primary mt-2" style="background-color: rgba(255, 99, 132, 1);" onclick="showModal('loginsModal', 'graficas_logins.php')">See more details</button>
            </div>
            <div class="graph-container">
                <h1 class="formTitle">Top 3 most purchased</h1>
                <iframe src="GC_thumbnail.php" style="width: 100%; height: 240px; border: none;"></iframe>
                <button class="btn btn-primary mt-2" style="background-color: rgba(75, 192, 192, 1);" onclick="showModal('comprasModal', 'graficas_compras.php')">See more details</button>
            </div>
        </div>
    </main>

    <!-- Modal para Registros Diarios -->
    <div class="modal fade" id="loginsModal" tabindex="-1" aria-labelledby="loginsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginsModalLabel">Record Chart Last Week</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe id="loginsIframe" src="" style="width: 100%; height: 300px; border: none;"></iframe>
                    <div class="d-flex justify-content-center mt-3">
                        <a class="btn btn-primary mt-2" href="GL_datosCompletos.php" target="_blank">Review complete graph</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Más Comprados -->
    <div class="modal fade" id="comprasModal" tabindex="-1" aria-labelledby="comprasModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="comprasModalLabel">Most Purchased Chart (Top 10)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe id="comprasIframe" src="" style="width: 100%; height: 300px; border: none;"></iframe>
                    <div class="d-flex justify-content-center mt-3">
                        <a class="btn btn-primary mt-2" href="GC_datosCompletos.php" target="_blank">Review complete graph</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p class="pie">Footer</p>
    </footer>

    <script>
        function showModal(modalId, url) {
            document.getElementById(modalId).querySelector('iframe').src = url;
            var myModal = new bootstrap.Modal(document.getElementById(modalId));
            myModal.show();
        }
    </script>
</body>
</html>
