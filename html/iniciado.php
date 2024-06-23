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
    <!-- CSS -->
    <link href="../css/index.css?ver=2.0" rel="stylesheet">
    <!-- JS -->
    <script src="../js/index.js"></script>
</head>
<body>
<?php session_start(); ?>
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
                <form class="container-fluid justify-content-start">
                    <p>Bienvenido: <?php echo isset($_SESSION['correo']) ? $_SESSION['correo'] : 'Invitado'; ?></p>
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