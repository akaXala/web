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

// Get admins
$queryAdmins = "SELECT id, nombre, primerAp, segundoAp, telefono, correo FROM usuarios WHERE permiso = 1";
$resultAdmins = mysqli_query($conn, $queryAdmins);

// Array to store the query results
$adminData = [];

if ($resultAdmins) {
    while ($admin = mysqli_fetch_assoc($resultAdmins)) {
        $adminData[] = $admin;
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
    <title>Historial de compras</title>
    <style>
        .content-section {
            display: none;
        }
        .active {
            display: block;
        }
        .nav-tabs {
            border-bottom: 1px solid #dee2e6;
        }
        .nav-item {
            margin-bottom: -1px;
        }
        .nav-link {
            border: 1px solid transparent;
            border-top-left-radius: .25rem;
            border-top-right-radius: .25rem;
        }
        .nav-link.active {
            color: #495057;
            background-color: #fff;
            border-color: #dee2e6 #dee2e6 #fff;
        }
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
<body class="sin-id">
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
    <div class="container mt-5 pt-5">
        <h2 class="text-center">More options</h2>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="option1-tab" data-bs-toggle="tab" data-bs-target="#option1" type="button" role="tab" aria-controls="option1" aria-selected="true">Admins</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="option2-tab" data-bs-toggle="tab" data-bs-target="#option2" type="button" role="tab" aria-controls="option2" aria-selected="false">Scalability</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="option3-tab" data-bs-toggle="tab" data-bs-target="#option3" type="button" role="tab" aria-controls="option3" aria-selected="false">Credits</button>
            </li>
        </ul>
        <div class="tab-content mt-4">
            <div class="tab-pane fade show active" id="option1" role="tabpanel" aria-labelledby="option1-tab">
                <h3>Manage administrators</h3><br>
                <h4>Active managers</h4>
                <?php if (!empty($adminData)) { ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Primer Apellido</th>
                                <th>Segundo Apellido</th>
                                <th>Teléfono</th>
                                <th>Correo</th>
                                <th>Modify</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($adminData as $admin) { ?>
                            <tr>
                                <td><?php echo $admin['id']; ?></td>
                                <td><?php echo $admin['nombre']; ?></td>
                                <td><?php echo $admin['primerAp']; ?></td>
                                <td><?php echo $admin['segundoAp']; ?></td>
                                <td><?php echo $admin['telefono']; ?></td>
                                <td><?php echo $admin['correo']; ?></td>
                                <td>
                                    <form action="modify_admin.php" method="post">
                                        <input type="hidden" name="id" value="<?php echo $admin['id']; ?>">
                                        <button type="submit" class="btn btn-warning btn-sm">Modify</button>
                                    </form>
                                </td>
                                <td>
                                    <form action="../php/delete_admin.php" method="post" onsubmit="return confirm('Are you sure you want to delete this administrator?');">
                                        <input type="hidden" name="id" value="<?php echo $admin['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <?php } else { ?>
                <p>No administrators found.</p>
                <?php } ?>
                <h4 class="mt-4">Register a new administrator</h4>
                <form id="register-form" action="../php/registrer_admin.php" method="post">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                            <div id="error-nombre" class="error-message"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="primerAp" class="form-label">Primer Apellido</label>
                            <input type="text" class="form-control" id="primerAp" name="primerAp" required>
                            <div id="error-apellido_paterno" class="error-message"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="segundoAp" class="form-label">Segundo Apellido</label>
                            <input type="text" class="form-control" id="segundoAp" name="segundoAp" required>
                            <div id="error-apellido_materno" class="error-message"></div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" required>
                            <div id="error-telefono" class="error-message"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="correo" class="form-label">Correo</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="correo" name="correo" required>
                                <span class="input-group-text">@admin.com</span>
                            </div>
                            <div id="error-correo" class="error-message"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="contrasena" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                            <div id="error-contrasena" class="error-message"></div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
            </div>
            <div class="tab-pane fade" id="option2" role="tabpanel" aria-labelledby="option2-tab">
                <h3>Page size administration</h3>
                <p>This is the content for option 2.</p>
            </div>
            <div class="tab-pane fade" id="option3" role="tabpanel" aria-labelledby="option3-tab">
                <h3>XalaStore creator credits</h3>
                <p>This is the content for option 3.</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const inputs = document.querySelectorAll('input');
            const registerForm = document.getElementById('register-form');

            const expresiones = {
                correo: /^[a-zA-Z0-9_.+-]{1,40}$/,
                contrasena: /^.{4,12}$/,
                nombre: /^[a-zA-ZÀ-ÿ\s]{1,40}$/,
                apellido_paterno: /^[a-zA-ZÀ-ÿ\s]{1,40}$/,
                apellido_materno: /^[a-zA-ZÀ-ÿ\s]{1,40}$/,
                telefono: /^\d{10}$/
            };

            const campos = {
                correo: false,
                contrasena: false,
                nombre: false,
                apellido_paterno: false,
                apellido_materno: false,
                telefono: false
            };

            const mensajesError = {
                correo: "El correo solo debe tener carácteres alfanúmericos.",
                contrasena: "La contraseña debe tener entre 4 y 12 caracteres.",
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
                    case "contrasena":
                        validarCampo(expresiones.contrasena, e.target, "contrasena");
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

            registerForm.addEventListener("submit", (e) => {
                e.preventDefault();

                if (
                    campos.correo &&
                    campos.contrasena &&
                    campos.nombre &&
                    campos.apellido_paterno &&
                    campos.apellido_materno &&
                    campos.telefono
                ) {
                    registerForm.submit();
                } else {
                    alert("Por favor, rellena el formulario correctamente.");
                }
            });
        });
    </script>
</body>
</html>
