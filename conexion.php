<?php
// Variables de configuración de la base de datos
$servidor = "localhost";
$usuario = "root";
$contrasena = "";
$basedatos = "ACD1B_TORRES";

// Crear conexión con MySQLi
$conexion = new mysqli($servidor, $usuario, $contrasena, $basedatos);

// Verificar si existe error de conexión
if ($conexion->connect_error) {
    die("Error de conexión a la base de datos.");
}
?>