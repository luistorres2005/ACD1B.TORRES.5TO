<?php
session_start();
require 'conexion.php';

$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cedula = trim($_POST['cedula']);
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $password = $_POST['password'];

    if (!empty($cedula) && !empty($nombre) && !empty($correo) && !empty($password)) {
        // Validar si el correo ya existe
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE correo = ?");
        $stmt->execute([$correo]);
        
        if ($stmt->rowCount() > 0) {
            $mensaje = "<div class='alert alert-danger'>El correo ya está registrado en el sistema ISP.</div>";
        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO usuarios (cedula, nombre, correo, password) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            
            if ($stmt->execute([$cedula, $nombre, $correo, $passwordHash])) {
                $mensaje = "<div class='alert alert-success'>Registro exitoso. <a href='index.php'>Iniciar sesión</a></div>";
            } else {
                $mensaje = "<div class='alert alert-danger'>Error al registrar.</div>";
            }
        }
    } else {
        $mensaje = "<div class='alert alert-warning'>Todos los campos son obligatorios.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de nuevo cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Registro de cliente </h4>
                </div>
                <div class="card-body">
                    <?= $mensaje ?>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label>Cédula</label>
                            <input type="text" name="cedula" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Nombre Completo</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Correo Electrónico</label>
                            <input type="email" name="correo" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Contraseña</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Registrarse</button>
                    </form>
                    <div class="mt-3 text-center">
                        <a href="index.php">¿Ya tienes cuenta? Inicia sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>