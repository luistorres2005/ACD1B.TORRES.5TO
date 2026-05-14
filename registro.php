<?php
session_start();
// Recomienda llamar al archivo de conexión con require_once
require_once("conexion.php");

$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. SANITIZACIÓN Y VALIDACIÓN DE ENTRADAS
    $cedula = htmlspecialchars(trim($_POST['cedula']));
    $nombre = htmlspecialchars(trim($_POST['nombre']));
    // filter_var limpia el correo de caracteres no válidos
    $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
    $clave_plana = $_POST['password']; // Contraseña recibida del formulario

    if (!empty($cedula) && !empty($nombre) && !empty($correo) && !empty($clave_plana)) {
        
        // Verificar si el correo o la cédula ya existen en el sistema
        $verificar = $conexion->prepare("SELECT id FROM usuarios WHERE correo = ? OR cedula = ?");
        $verificar->bind_param("ss", $correo, $cedula);
        $verificar->execute();
        $verificar->store_result();
        
        if ($verificar->num_rows > 0) {
            // Escapar el mensaje de error por precaución
            $mensaje = "<div class='alert alert-danger'>" . htmlspecialchars("El correo o la cédula ya están registrados en el sistema.", ENT_QUOTES, 'UTF-8') . "</div>";
        } else {
            // 2. HASHEO DE LA CONTRASEÑA (CRUCIAL)
            $clave_hashed = password_hash($clave_plana, PASSWORD_DEFAULT);
            
            // Inserción del nuevo usuario con la clave hasheada
            $stmt = $conexion->prepare("INSERT INTO usuarios (cedula, nombre, correo, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $cedula, $nombre, $correo, $clave_hashed);
            
            if ($stmt->execute()) {
                $mensaje = "<div class='alert alert-success'>" . htmlspecialchars("Registro exitoso.", ENT_QUOTES, 'UTF-8') . " <a href='index.php'>Inicia sesión aquí</a></div>";
            } else {
                $mensaje = "<div class='alert alert-danger'>" . htmlspecialchars("Error al registrar el usuario.", ENT_QUOTES, 'UTF-8') . "</div>";
            }
            $stmt->close();
        }
        $verificar->close();
    } else {
        $mensaje = "<div class='alert alert-warning'>Llena todos los campos obligatorios.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Empresa ISP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white text-center py-3">
                    <h5 class="mb-0">Registro de Nuevo Cliente</h5>
                </div>
                <div class="card-body p-4">
                    <?= $mensaje ?>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Cédula</label>
                            <input type="text" name="cedula" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nombre Completo</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" name="correo" class="form-control" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Crear Cuenta</button>
                    </form>
                    <div class="mt-3 text-center">
                        <a href="index.php" class="text-decoration-none">¿Ya tienes cuenta? Ingresa aquí</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>