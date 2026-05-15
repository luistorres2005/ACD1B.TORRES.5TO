<?php
session_start();
require_once("conexion.php");

// Uso de intval() para asegurar que el ID de sesión sea un entero
$id_usuario = isset($_SESSION['usuario_id']) ? intval($_SESSION['usuario_id']) : 0;

if ($id_usuario === 0) {
    header("Location: index.php");
    exit;
}

$mensaje_perfil = '';
$mensaje_pass = '';

// 1. Actualizar Datos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_actualizar_datos'])) {
    // Sanitización de entradas
    $nuevo_nombre = htmlspecialchars(trim($_POST['nombre']));
    $nuevo_correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);

    if (!empty($nuevo_nombre) && !empty($nuevo_correo)) {
        $stmt = $conexion->prepare("UPDATE usuarios SET nombre = ?, correo = ? WHERE id = ?");
        $stmt->bind_param("ssi", $nuevo_nombre, $nuevo_correo, $id_usuario);
        
        if ($stmt->execute()) {
            $_SESSION['nombre'] = $nuevo_nombre; 
            $mensaje_perfil = "<div class='alert alert-success'>" . htmlspecialchars("Datos guardados.", ENT_QUOTES, 'UTF-8') . "</div>";
        } else {
            $mensaje_perfil = "<div class='alert alert-danger'>" . htmlspecialchars("Error al actualizar.", ENT_QUOTES, 'UTF-8') . "</div>";
        }
        $stmt->close();
    }
}

// 2. Cambiar Contraseña
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_cambiar_pass'])) {
    $pass_actual = $_POST['pass_actual'];
    $pass_nueva = $_POST['pass_nueva'];
    $pass_confirma = $_POST['pass_confirma'];

    if ($pass_nueva === $pass_confirma) {
        $stmt = $conexion->prepare("SELECT password FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $user_db = $resultado->fetch_assoc();

        if (password_verify($pass_actual, $user_db['password'])) {
            $nuevo_hash = password_hash($pass_nueva, PASSWORD_DEFAULT);
            $update = $conexion->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
            $update->bind_param("si", $nuevo_hash, $id_usuario);
            $update->execute();
            $mensaje_pass = "<div class='alert alert-success'>" . htmlspecialchars("Contraseña actualizada.", ENT_QUOTES, 'UTF-8') . "</div>";
            $update->close();
        } else {
            $mensaje_pass = "<div class='alert alert-danger'>" . htmlspecialchars("La contraseña actual es incorrecta.", ENT_QUOTES, 'UTF-8') . "</div>";
        }
        $stmt->close();
    } else {
        $mensaje_pass = "<div class='alert alert-warning'>" . htmlspecialchars("Las contraseñas nuevas no coinciden.", ENT_QUOTES, 'UTF-8') . "</div>";
    }
}

// 3. Obtener datos para el formulario
$stmt = $conexion->prepare("SELECT cedula, nombre, correo FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil - FG OptiNet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="inicio.php">Empresa ISP</a>
        <div class="d-flex">
            <a href="inicio.php" class="btn btn-outline-light me-2">Volver al Inicio</a>
            <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h3 class="mb-4">Configuración de mi cuenta</h3>

    <div class="row">
        <!-- Formulario de Datos -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom border-primary border-3">
                    <h5 class="mb-0 text-primary fw-bold">Mis Datos</h5>
                </div>
                <div class="card-body">
                    <?= $mensaje_perfil ?>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label text-muted">Cédula</label>
                            <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($usuario['cedula'], ENT_QUOTES, 'UTF-8') ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nombre Completo</label>
                            <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($usuario['nombre'], ENT_QUOTES, 'UTF-8') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Correo Electrónico</label>
                            <input type="email" name="correo" class="form-control" value="<?= htmlspecialchars($usuario['correo'], ENT_QUOTES, 'UTF-8') ?>" required>
                        </div>
                        <button type="submit" name="btn_actualizar_datos" class="btn btn-primary w-100">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Formulario de Contraseña -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom border-warning border-3">
                    <h5 class="mb-0 text-warning fw-bold">Seguridad</h5>
                </div>
                <div class="card-body">
                    <?= $mensaje_pass ?>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Contraseña Actual</label>
                            <input type="password" name="pass_actual" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nueva Contraseña</label>
                            <input type="password" name="pass_nueva" class="form-control" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Repetir Nueva Contraseña</label>
                            <input type="password" name="pass_confirma" class="form-control" required>
                        </div>
                        <button type="submit" name="btn_cambiar_pass" class="btn btn-warning w-100 fw-bold">Actualizar Contraseña</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>