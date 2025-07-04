<?php
// Configuración de conexión a la base de datos
$host = 'localhost';
$db = 'u245718453_cuidamiciudad';
$user = 'u245718453_cuidamiciudad';
$pass = 'Miciudad123'; // asegúrate que esta contraseña sea correcta
$charset = 'utf8mb4';

try {
    $conexion = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexion->exec("SET time_zone = '-04:00'");
} catch (PDOException $e) {
    die('Error en la conexión: ' . $e->getMessage());
}
?>
