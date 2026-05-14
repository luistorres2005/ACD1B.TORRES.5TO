<?php
session_start();
require 'conexion.php';

// Si ya tiene sesión, lo mandamos directo al inicio
if (isset($_SESSION['usuario_id'])) {
    header("Location: inicio.php");
    exit;
}

$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = trim($_POST['correo']);
    $password = $_POST['password'];

    if (!empty($correo) && !empty($password)) {
        // Consultamos el usuario en la base de datos
        $stmt = $pdo->prepare("SELECT id, nombre, password FROM usuarios WHERE correo = ?");
        $stmt->execute([$correo]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificamos si existe y si la contraseña coincide
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['nombre'] = $user['nombre'];
            header("Location: inicio.php"); // Redirigimos a la nueva página principal
            exit;
        } else {
            $mensaje = "<div class='alert alert-danger'>Correo o contraseña incorrectos.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - FG OptiNet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center mt-5">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0">Portal de Clientes </h4>
                </div>
                <div class="card-body p-4">
                    <?= $mensaje ?>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Correo Electrónico</label>
                            <input type="email" name="correo" class="form-control" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Contraseña</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
                    </form>
                    <div class="mt-3 text-center">
                        <a href="registro.php" class="text-decoration-none">¿No tienes cuenta? Regístrate aquí</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>