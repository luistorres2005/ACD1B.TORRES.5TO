<?php
session_start();

// Si no ha iniciado sesión, lo regresamos al login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio - FG OptiNet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="inicio.php">FG OptiNet</a>
        <div class="d-flex">
            <a href="mi_cuenta.php" class="btn btn-outline-light me-2">Mi Cuenta</a>
            <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="alert alert-primary shadow-sm">
        <h4 class="mb-0">¡Bienvenido de nuevo, <?= htmlspecialchars($_SESSION['nombre']) ?>!</h4>
        <p class="mb-0">Aquí puedes consultar nuestros servicios y conocer más sobre nosotros.</p>
    </div>

    <h3 class="mt-5 mb-3 border-bottom pb-2">Nuestros Servicios de Internet</h3>
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h4 class="card-title text-primary">Plan Hogar</h4>
                    <h2 class="fw-bold">100 Mbps</h2>
                    <p class="card-text">Ideal para streaming, redes sociales y navegación fluida para toda la familia.</p>
                    <img src="img/internet.png"alt="internet" width="259">                
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card text-center shadow-sm border-primary">
                <div class="card-body">
                    <h4 class="card-title text-primary">Plan Gamer</h4>
                    <h2 class="fw-bold">300 Mbps</h2>
                    <p class="card-text">Ping ultra bajo, juegos online sin lag y descargas rápidas para usuarios exigentes.</p>
                    <img src="img/gamer.webp"alt="internet" width="150">
                </div>
            </div>
        </div>
    </div>

    <h3 class="mt-5 mb-3 border-bottom pb-2">Sobre Nosotros</h3>
    <div class="row mb-5">
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-success">Nuestra Misión</h5>
                    <p class="card-text text-justify">Brindar conectividad de alta calidad y soluciones tecnológicas accesibles para todos los hogares y negocios, garantizando un servicio de internet estable, rápido y con un soporte técnico humano y eficiente.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-info">Nuestra Visión</h5>
                    <p class="card-text text-justify">Ser la empresa proveedora de internet líder en la región, reconocida por nuestra innovación tecnológica, la expansión de nuestra red de fibra óptica y el compromiso inquebrantable con la satisfacción de nuestros clientes.</p>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>