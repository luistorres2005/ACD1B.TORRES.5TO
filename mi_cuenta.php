<?php
session_start();
require 'conexion.php';

// Validar que haya sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

$id_usuario = $_SESSION['usuario_id'];
$mensaje_perfil = '';
$mensaje_pass = '';

// 1. Lógica para actualizar Datos Básicos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_actualizar_datos'])) {
    $nuevo_nombre = trim($_POST['nombre']);
    $nuevo_correo = trim($_POST['correo']);

    if (!empty($nuevo_nombre) && !empty($nuevo_correo)) {
        $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, correo = ? WHERE id = ?");
        if ($stmt->execute([$nuevo_nombre, $nuevo_correo, $id_usuario])) {
            $_SESSION['nombre'] = $nuevo_nombre; // Actualizamos la sesión
            $mensaje_perfil = "<div class='alert alert-success'>Datos actualizados correctamente.</div>";
        } else {
            $mensaje_perfil = "<div class='alert alert-danger'>Error al actualizar datos.</div>";
        }
    }
}

// 2. Lógica para cambiar Contraseña
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_cambiar_pass'])) {
    $pass_actual = $_POST['pass_actual'];
    $pass_nueva = $_POST['pass_nueva'];
    $pass_confirma = $_POST['pass_confirma'];

    if ($pass_nueva === $pass_confirma) {
        // Obtener la contraseña actual encriptada de la BD
        $stmt = $pdo->prepare("SELECT password FROM usuarios WHERE id = ?");
        $stmt->execute([$id_usuario]);
        $user_db = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar si la contraseña actual ingresada es correcta
        if (password_verify($pass_actual, $user_db['password'])) {
            $nuevo_hash = password_hash($pass_nueva, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
            $update->execute([$nuevo_hash, $id_usuario]);
            $mensaje_pass = "<div class='alert alert-success'>Contraseña cambiada con éxito.</div>";
        } else {
            $mensaje_pass = "<div class='alert alert-danger'>La contraseña actual no es correcta.</div>";
        }
    } else {
        $mensaje_pass = "<div class='alert alert-warning'>Las contraseñas nuevas no coinciden.</div>";
    }
}

// 3. Obtener los datos actuales para mostrarlos en el formulario
$stmt = $pdo->prepare("SELECT cedula, nombre, correo FROM usuarios WHERE id = ?");
$stmt->execute([$id_usuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Cuenta - FG optinet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="inicio.php">FG optinet</a>
        <div class="d-flex">
            <a href="inicio.php" class="btn btn-outline-light me-2">Volver al Inicio</a>
            <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h2 class="mb-4">Gestión de mi cuenta</h2>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Mis Datos Básicos</h5>
                </div>
                <div class="card-body">
                    <?= $mensaje_perfil ?>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Cédula (No modificable)</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($usuario['cedula']) ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nombre Completo</label>
                            <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" name="correo" class="form-control" value="<?= htmlspecialchars($usuario['correo']) ?>" required>
                        </div>
                        <button type="submit" name="btn_actualizar_datos" class="btn btn-primary">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Cambiar Contraseña</h5>
                </div>
                <div class="card-body">
                    <?= $mensaje_pass ?>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Contraseña Actual</label>
                            <input type="password" name="pass_actual" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nueva Contraseña</label>
                            <input type="password" name="pass_nueva" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Repetir Nueva Contraseña</label>
                            <input type="password" name="pass_confirma" class="form-control" required>
                        </div>
                        <button type="submit" name="btn_cambiar_pass" class="btn btn-secondary">Actualizar Contraseña</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>