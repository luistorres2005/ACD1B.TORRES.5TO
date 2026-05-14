<?php
$host = 'localhost';
$dbname = 'isp_portal_db';
$username = 'root'; // Cambia si tienes otro usuario
$password = '';     // Cambia si tienes contraseña

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>