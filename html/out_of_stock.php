<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producto Agotado</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../css/index.css?ver=2.0" rel="stylesheet">
</head>
<body>
    <header>
        <marquee behavior="scroll" direction="left" class="marquee">
            <span class="marquee-text" style="margin-right: 800px;">Producto Agotado</span>
            <span class="marquee-text">Lamentamos los inconvenientes</span>
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
        <div class="alert alert-warning" role="alert">
            El producto que intentas comprar está agotado. Por favor, vuelve más tarde o explora otros productos.
        </div>
        <div style="text-align: center; padding: 20px;">
            <a href="../html/index.php" class="btn btn-primary" style="padding: 10px 20px; font-size: 16px;">Regresar al inicio</a>
        </div>
    </main>
    <footer class="footer mt-auto py-3 bg-light">
        <div class="container">
            <span class="text-muted">Pendejos S.A de C.V</span>
        </div>
    </footer>
</body>
</html>