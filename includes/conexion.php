<?php
// Configuración de conexión a la base de datos
$host = 'localhost';
$db = 'cuidamiciudad';
$user = 'cuidamiciudad';
$pass = 'micuidad123';
$charset = 'utf8mb4';

try {
    $conexion = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Error en la conexión: ' . $e->getMessage());
}
?>
