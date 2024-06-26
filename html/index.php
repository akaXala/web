<?php session_start(); 
       include '../php/conexion.php'; // Include the database connection
// Redirect users without an active session
if (!isset($_SESSION['correo'])) {
    header("Location: login.html"); // Ajusta la ruta según sea necesario
    exit;
    // Get the userId through the email
}
$email = $_SESSION['correo'];
// Perform a database query to retrieve the userId based on the email
// Replace 'your_database_table' with the actual table name in your database
$query = "SELECT id, permiso, creditos FROM usuarios WHERE correo = '$email'";
$result = mysqli_query($conn, $query);

if ($result) {
    // Check if a row is returned
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $creditos = $row['creditos'];
        $userId = $row['id'];
        $permiso = $row['permiso'];

        // Check if the 'permiso' value is 1
        if ($permiso == 1) {
            header("Location: admin.php");
            exit;
        }
    }
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
    <!-- ICONO -->
    <link rel="icon" href="../imgs/icono.ico" type="image/x-icon">
    <script src="../js/components.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary navbar-light py-2 fixed-top custom-toggler">
        <div class="container">
            <!-- LOGO y Texto -->
            <a class="navbar-brand flex-grow-0 me-1" href="#">
                <img src="../imgs/logo1.png" alt="Logo" width="40" height="40"
                    class="d-inline-block align-middle">XalaStore
            </a>
            <!-- Barra de busqueda -->
            <form class="d-flex flex-grow-1 w-25" role="search" action="./allProducts.php" method="get">
                <input id="productSearch" class="form-control" name="productSearch" type="search" placeholder="Search">
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
                    <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="./index.php#popular">Popular</a></li>
                        <li class="nav-item"><a class="nav-link" href="./index.php#Beneficios">Benefits</a></li>
                        <li class="nav-item"><a class="nav-link" href="./index.php#categories">Category</a></li>
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
                    <li><span class="d-flex justify-content-center">Creditos: <?php echo $creditos; ?></span></li>
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

    <section id="offers" style="background-color: #d5eaff;">
        <div id="carouselExampleDark" class="carousel carousel-dark slide">
            <!-- Botones de abajo que indica la slide en que me encuentro-->
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1"
                    aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2"
                    aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="3"
                    aria-label="Slide 4"></button>
            </div>
            <!-- Elementos dentro del carrusel-->
            <div class="carousel-inner">
                <!-- Item 1 -->
                <div class="carousel-item active c-item" data-bs-interval="10000">
                    <img src="../imgs/Carrusel-1.png" class="d-block w-100 c-img" alt="...">
                    <div class="carousel-caption row justify-content-center align-items-center h-100 ">
                        <div
                            class="product-details col-md-6 d-flex flex-column justify-content-center align-items-center text-md-start text-center">
                            <div class="discount">19% Off</div>
                            <div class>
                                <h2>Chanel Coco Noir Eau De</h2>
                            </div>
                            <div class="description">
                                oco Noir by Chanel is an elegant and mysterious fragrance, featuring notes of
                                grapefruit, rose, and sandalwood. Perfect for evening occasions.
                            </div>
                            <div class="py-3">
                                <a class="btn btn-primary" href="./dpIniciado.php?id=7">Mas información</a>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex justify-content-center align-items-center">
                            <img src="https://cdn.dummyjson.com/products/images/fragrances/Chanel%20Coco%20Noir%20Eau%20De/1.png"
                                class="img-fluid rounded  w-100" alt="Producto" style="height: 100; width: 100;">
                        </div>
                    </div>
                </div>
                <!-- Item 2 -->
                <div class="carousel-item c-item" data-bs-interval="2000">
                    <img src="../imgs/Carrusel-2.png" class="d-block w-100 c-img" alt="...">
                    <div class="carousel-caption row justify-content-center align-items-center h-100 ">
                        <div
                            class="product-details col-md-6 d-flex flex-column justify-content-center align-items-center text-md-start text-center">
                            <div class="discount">18% Off</div>
                            <div class>
                                <h2>Annibale Colombo Sofa</h2>
                            </div>
                            <div class="description">
                                The Annibale Colombo Sofa is a sophisticated and comfortable seating option, featuring
                                exquisite design and premium upholstery for your living room.
                            </div>
                            <div class="py-3">
                                <a class="btn btn-primary" href="./dpIniciado.php?id=12">Mas información</a>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex justify-content-center align-items-center">
                            <img src="https://cdn.dummyjson.com/products/images/furniture/Annibale%20Colombo%20Sofa/1.png"
                                class="img-fluid rounded  w-100" alt="Producto" style="height: 100; width: 100;">
                        </div>
                    </div>
                </div>
                <!-- Item 3 -->
                <div class="carousel-item c-item">
                    <img src="../imgs/Carrusel-3.png" class="d-block w-100 c-img" alt="...">
                    <div class="carousel-caption row justify-content-center align-items-center h-100 ">
                        <div
                            class="product-details col-md-6 d-flex flex-column justify-content-center align-items-center text-md-start text-center">
                            <div class="discount">18% Off</div>
                            <div class>
                                <h2>Gucci Bloom Eau de</h2>
                            </div>
                            <div class="description">
                                Gucci Bloom by Gucci is a floral and captivating fragrance, with notes of tuberose,
                                jasmine, and Rangoon creeper. It's a modern and romantic scent..
                            </div>
                            <div class="py-3">
                                <a class="btn btn-primary" href="./dpIniciado.php?id=10">Mas información</a>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex justify-content-center align-items-center">
                            <img src="https://cdn.dummyjson.com/products/images/fragrances/Gucci%20Bloom%20Eau%20de/1.png"
                                class="img-fluid rounded  w-100" alt="Producto" style="height: 100; width: 100;">
                        </div>
                    </div>
                </div>
                <!-- Item 4 -->
                <div class="carousel-item c-item">
                    <img src="../imgs/Carrusel-4.png" class="d-block w-100 c-img" alt="...">
                    <div class="carousel-caption row justify-content-center align-items-center h-100 ">
                        <div
                            class="product-details col-md-6 d-flex flex-column justify-content-center align-items-center text-md-start text-center">
                            <div class="discount">19% Off</div>
                            <div class>
                                <h2>Cooking Oil</h2>
                            </div>
                            <div class="description">
                                Versatile cooking oil suitable for frying, sautéing, and various culinary applications.
                            </div>
                            <div class="py-3">
                                <a class="btn btn-primary" href="./dpIniciado.php?id=20">Mas información</a>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex justify-content-center align-items-center">
                            <img src="https://cdn.dummyjson.com/products/images/groceries/Cooking%20Oil/1.png"
                                class="img-fluid rounded  w-100" alt="Producto" style="height: 100; width: 100;">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Controles del carrito previous y next -->
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>
    <!-- Seccion popular -->
    <section id="popular" style="background-color: white; padding-top:8rem; padding-bottom:0rem;">
        <div class="container">
            <div class="row d-flex  justify-content-center align-items-center py-1 px-4 ">
                <h2>Most Popular</h2>
            </div>
            <div id="products-container" class="row  d-flex  justify-content-center align-items-center">
            </div>
        </div>
    </section>
    <!-- Seccion Beneficios -->
    <div id="Beneficios" style=" background-color: white; padding: 4rem;"></div>
    <section style="background-color: #d5eaff; padding-top: 3rem;padding-bottom: 3rem;">
        <div class="container">
            <div class="row  d-flex row justify-content-center align-items-center">
                <div class="card d-flex mx-5 my-3"
                    style="width: 18rem;background-color: #0b5ed7; border-radius: 40px;color: white;">
                    <div class="card-body">
                        <i class="fa-solid fa-truck fa-8x d-flex justify-content-center py-1"></i>
                        <h2 class="card-title d-flex justify-content-center">Free Delivery</h2>
                        <p class="card-text d-flex justify-content-center py-2">Shop online and enjoy free delivery from
                            XalaStore. No extra costs, just
                            convenience. Get your favorite items delivered to your doorstep for free. Shop now!</p>
                    </div>
                </div>
                <div class="card d-flex mx-5 my-3"
                    style="width: 18rem;background-color: #0b5ed7; border-radius: 40px;color: white;">
                    <div class="card-body">
                        <i class="fa-solid fa-money-check-dollar fa-8x d-flex justify-content-center py-1"></i>
                        <h2 class="card-title d-flex justify-content-center">Safe Shopping</h2>
                        <p class="card-text d-flex justify-content-center py-2">Our advanced encryption and trusted
                            payment methods protect your personal information. Enjoy worry-free shopping with our secure
                            checkout process. Shop safely today!</p>
                    </div>
                </div>
                <div class="card d-flex mx-5 my-3"
                    style="width: 18rem;background-color: #0b5ed7; border-radius: 40px;color: white;">
                    <div class="card-body">
                        <i class="fa-solid fa-boxes-packing fa-8x d-flex justify-content-center py-1"></i>
                        <h2 class="card-title d-flex justify-content-center">Returns</h2>
                        <p class="card-text d-flex justify-content-center py-2">Not satisfied with your purchase? No
                            problem! Easily return items within 30 days. Our customer service team is here to help.
                            Enjoy stress-free returns at XalaStore.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Seccion de Categorias-->
    <section id="categories" style="background-color: white; padding-top:8rem; padding-bottom:8rem;">
        <div id="carouselCategories" class="container carousel slide">
            <div class="row row-cols-2">
                <!-- Titulo -->
                <div class="col d-flex  justify-content-start align-items-center py-1 px-4 ">
                    <h2>Browse by category</h2>
                </div>
                <!-- Botones left and right -->
                <div class="col d-flex  justify-content-end align-items-center py-1">
                    <div class="pe-2">
                        <button type="button" data-bs-target="#carouselCategories" data-bs-slide="prev"
                            class="btn btn-primary">
                            <i class="fa-solid fa-arrow-left"></i>
                            <span class="visually-hidden">Previous</span>
                        </button>
                    </div>
                    <button type="button" data-bs-target="#carouselCategories" data-bs-slide="next"
                        class="btn btn-primary">
                        <i class="fa-solid fa-arrow-right"></i>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
                <!-- Items del carrusel -->
                <div class="carousel-inner">
                    <!-- Item 1-->
                    <div class="carousel-item active" data-bs-interval="10000">
                        <div class="row flex-grow-1 py-1">
                            <div class="col d-flex justify-content-center align-items-center">
                                <div class="col d-flex justify-content-center">
                                    <a id="beauty" class="btn" href="./allProducts.php?category=beauty">
                                        <div class="card border-0" style="width: 15rem; height: 15rem;">
                                            <img src="../imgs/beauty.png" class="card-img " alt="beauty">
                                            <div
                                                class=" card-img-overlay d-flex align-items-end justify-content-center py-0">
                                                <h5 class="card-title fw-bold">Beauty</h5>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col d-flex justify-content-center">
                                    <a id="fragrances" class="btn" href="./allProducts.php?category=fragrances">
                                        <div class="card border-0" style="width: 15rem; height: 15rem;">
                                            <img src="../imgs/fragrances.png" class="card-img " alt="fragrances">
                                            <div
                                                class=" card-img-overlay d-flex align-items-end justify-content-center py-0">
                                                <h5 class="card-title fw-bold">Fragrances</h5>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col d-flex justify-content-center align-items-center">
                                <div class="col d-flex justify-content-center">
                                    <a id="furniture" class="btn" href="./allProducts.php?category=furniture">
                                        <div class="card border-0" style="width: 15rem; height: 15rem;">
                                            <img src="../imgs/furniture.png" class="card-img " alt="furniture">
                                            <div
                                                class=" card-img-overlay d-flex align-items-end justify-content-center py-0">
                                                <h5 class="card-title fw-bold">Furniture</h5>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col d-flex justify-content-center">
                                    <a id="groceries" class="btn" href="./allProducts.php?category=groceries">
                                        <div class="card border-0" style="width: 15rem; height: 15rem;">
                                            <img src="../imgs/groceries.png" class="card-img " alt="groceries">
                                            <div
                                                class=" card-img-overlay d-flex align-items-end justify-content-center py-0">
                                                <h5 class="card-title fw-bold">Groceries</h5>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Item 2-->
                    <div class="carousel-item" data-bs-interval="10000">
                        <div class="row flex-grow-1 py-1">
                            <div class="col d-flex justify-content-center align-items-center">
                                <div class="col d-flex justify-content-center">
                                    <a id="home-decoration" class="btn"
                                        href="./allProducts.php?category=home-decoration">
                                        <div class="card border-0" style="width: 15rem; height: 15rem;">
                                            <img src="../imgs/home-decoration.png" class="card-img "
                                                alt="home-decoration">
                                            <div
                                                class=" card-img-overlay d-flex align-items-end justify-content-center py-0">
                                                <h5 class="card-title fw-bold">Home decoration</h5>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col d-flex justify-content-center">
                                    <a id="kitchen-accessories" class="btn"
                                        href="./allProducts.php?category=kitchen-accessories">
                                        <div class="card border-0" style="width: 15rem; height: 15rem;">
                                            <img src="../imgs/kitchen-accessories.png" class="card-img "
                                                alt="kitchen-accessories">
                                            <div
                                                class=" card-img-overlay d-flex align-items-end justify-content-center py-0">
                                                <h5 class="card-title fw-bold">Kitchen accessories</h5>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col d-flex justify-content-center align-items-center">
                                <div class="col d-flex justify-content-center">
                                    <a id="laptops" class="btn" href="./allProducts.php?category=laptops">
                                        <div class="card border-0" style="width: 15rem; height: 15rem;">
                                            <img src="../imgs/laptops.png" class="card-img " alt="laptops">
                                            <div
                                                class=" card-img-overlay d-flex align-items-end justify-content-center py-0">
                                                <h5 class="card-title fw-bold">Laptops</h5>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col d-flex justify-content-center">
                                    <a id="mens-shirts" class="btn" href="./allProducts.php?category=mens-shirts">
                                        <div class="card border-0" style="width: 15rem; height: 15rem;">
                                            <img src="../imgs/mens-shirts.png" class="card-img " alt="mens-shirts">
                                            <div
                                                class=" card-img-overlay d-flex align-items-end justify-content-center py-0">
                                                <h5 class="card-title fw-bold">Men's shirts</h5>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Item 3 -->
                    <div class="carousel-item" data-bs-interval="10000">
                        <div class="row flex-grow-1 py-1">
                            <div class="col d-flex justify-content-center align-items-center">
                                <div class="col d-flex justify-content-center">
                                    <a id="mens-shoes" class="btn" href="./allProducts.php?category=mens-shoes">
                                        <div class="card border-0" style="width: 15rem; height: 15rem;">
                                            <img src="../imgs/mens-shoes.png" class="card-img " alt="mens-shoes">
                                            <div
                                                class=" card-img-overlay d-flex align-items-end justify-content-center py-0">
                                                <h5 class="card-title fw-bold">Men's shoes</h5>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col d-flex justify-content-center">
                                    <a id="mobile-accessories" class="btn"
                                        href="./allProducts.php?category=mobile-accessories">
                                        <div class="card border-0" style="width: 15rem; height: 15rem;">
                                            <img src="../imgs\mobile-accessories​.png" class="card-img "
                                                alt="mobile-accessories">
                                            <div
                                                class=" card-img-overlay d-flex align-items-end justify-content-center py-0">
                                                <h5 class="card-title fw-bold">Mobile accessories</h5>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col d-flex justify-content-center align-items-center">
                                <div class="col d-flex justify-content-center">
                                    <a id="mens-watches" class="btn" href="./allProducts.php?category=mens-watches">
                                        <div class="card border-0" style="width: 15rem; height: 15rem;">
                                            <img src="../imgs/mens-watches.png" class="card-img " alt="mens-watches">
                                            <div
                                                class=" card-img-overlay d-flex align-items-end justify-content-center py-0">
                                                <h5 class="card-title fw-bold">Men's watches</h5>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Controles del carrito previous y next -->
        </div>
    </section>
    <footer-js></footer-js>
</body>

</html>