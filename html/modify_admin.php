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

// Retrieve the admin details to modify
if (isset($_POST['id'])) {
    $adminId = $_POST['id'];
    $queryAdmin = "SELECT id, nombre, primerAp, segundoAp, telefono, correo FROM usuarios WHERE id = $adminId";
    $resultAdmin = mysqli_query($conn, $queryAdmin);

    if ($resultAdmin) {
        $adminData = mysqli_fetch_assoc($resultAdmin);
        $correoPartes = explode('@', $adminData['correo']);
        $correoLocal = $correoPartes[0];
    } else {
        // If the query fails, redirect to admin.php
        header("Location: admin.php");
        exit;
    }
} else {
    // If no admin ID is provided, redirect to admin.php
    header("Location: admin.php");
    exit;
}

// Update admin details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $nombre = $_POST['nombre'];
    $primerAp = $_POST['primerAp'];
    $segundoAp = $_POST['segundoAp'];
    $telefono = $_POST['telefono'];
    $correoLocal = $_POST['correo'];
    $correo = $correoLocal . '@admin.com';

    $updateQuery = "UPDATE usuarios SET nombre = '$nombre', primerAp = '$primerAp', segundoAp = '$segundoAp', telefono = '$telefono', correo = '$correo' WHERE id = $adminId";
    
    if (mysqli_query($conn, $updateQuery)) {
        header("Location: moreOptions.php");
        exit;
    } else {
        $error = "Error updating record: " . mysqli_error($conn);
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
    <title>Modify Administrator</title>
    <style>
        .is-invalid {
            border-color: #dc3545;
        }
        .is-valid {
            border-color: #28a745;
        }
        .error-message {
            color: #dc3545;
            font-size: 0.875em;
        }
    </style>
</head>
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
<body class="sin-id">
    <div class="container mt-5 pt-5">
        <h2 class="text-center">Modify Administrator</h2>
        <?php if (isset($error)) { ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>
        <form id="modify-form" action="modify_admin.php" method="post">
            <input type="hidden" name="id" value="<?php echo $adminData['id']; ?>">
            <input type="hidden" name="update" value="1">
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control is-valid" id="nombre" name="nombre" value="<?php echo $adminData['nombre']; ?>" required>
                    <div id="error-nombre" class="error-message"></div>
                </div>
                <div class="col-md-4">
                    <label for="primerAp" class="form-label">Primer Apellido</label>
                    <input type="text" class="form-control is-valid" id="primerAp" name="primerAp" value="<?php echo $adminData['primerAp']; ?>" required>
                    <div id="error-apellido_paterno" class="error-message"></div>
                </div>
                <div class="col-md-4">
                    <label for="segundoAp" class="form-label">Segundo Apellido</label>
                    <input type="text" class="form-control is-valid" id="segundoAp" name="segundoAp" value="<?php echo $adminData['segundoAp']; ?>" required>
                    <div id="error-apellido_materno" class="error-message"></div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control is-valid" id="telefono" name="telefono" value="<?php echo $adminData['telefono']; ?>" required>
                    <div id="error-telefono" class="error-message"></div>
                </div>
                <div class="col-md-4">
                    <label for="correo" class="form-label">Correo</label>
                    <div class="input-group">
                        <input type="text" class="form-control is-valid" id="correo" name="correo" value="<?php echo $correoLocal; ?>" required>
                        <span class="input-group-text">@admin.com</span>
                    </div>
                    <div id="error-correo" class="error-message"></div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const inputs = document.querySelectorAll('input');
            const modifyForm = document.getElementById('modify-form');

            const expresiones = {
                correo: /^[a-zA-Z0-9_.+-]{1,40}$/,
                nombre: /^[a-zA-ZÀ-ÿ\s]{1,40}$/,
                apellido_paterno: /^[a-zA-ZÀ-ÿ\s]{1,40}$/,
                apellido_materno: /^[a-zA-ZÀ-ÿ\s]{1,40}$/,
                telefono: /^\d{10}$/
            };

            const campos = {
                correo: true,
                nombre: true,
                apellido_paterno: true,
                apellido_materno: true,
                telefono: true
            };

            const mensajesError = {
                correo: "El correo solo debe tener carácteres alfanúmericos.",
                nombre: "El nombre solo puede contener letras y espacios.",
                apellido_paterno: "El apellido paterno solo puede contener letras y espacios.",
                apellido_materno: "El apellido materno solo puede contener letras y espacios.",
                telefono: "El teléfono debe contener 10 dígitos."
            };

            const validarFormulario = (e) => {
                switch(e.target.name){
                    case "correo":
                        validarCampo(expresiones.correo, e.target, "correo");
                        break;
                    case "nombre":
                        validarCampo(expresiones.nombre, e.target, "nombre");
                        break;
                    case "primerAp":
                        validarCampo(expresiones.apellido_paterno, e.target, "apellido_paterno");
                        break;
                    case "segundoAp":
                        validarCampo(expresiones.apellido_materno, e.target, "apellido_materno");
                        break;
                    case "telefono":
                        validarCampo(expresiones.telefono, e.target, "telefono");
                        break;
                }
            };

            const validarCampo = (expresion, input, campo) => {
                const mensajeError = document.querySelector(`#error-${campo}`);
                if (expresion.test(input.value.trim())) {
                    input.classList.remove("is-invalid");
                    input.classList.add("is-valid");
                    campos[campo] = true;
                    mensajeError.innerText = "";
                } else {
                    input.classList.add("is-invalid");
                    input.classList.remove("is-valid");
                    campos[campo] = false;
                    mensajeError.innerText = mensajesError[campo];
                }
            };

            inputs.forEach((input) => {
                input.addEventListener("keyup", validarFormulario);
                input.addEventListener("blur", validarFormulario);
            });

            modifyForm.addEventListener("submit", (e) => {
                e.preventDefault();

                if (
                    campos.correo &&
                    campos.nombre &&
                    campos.apellido_paterno &&
                    campos.apellido_materno &&
                    campos.telefono
                ) {
                    modifyForm.submit();
                } else {
                    alert("Por favor, rellena el formulario correctamente.");
                }
            });
        });
    </script>
</body>
</html>
