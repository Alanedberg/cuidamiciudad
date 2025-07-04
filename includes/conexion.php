<?php
// Configuración de conexión a la base de datos
$host = 'mysqlXXX.hostinger.com';
$db = 'u245718453_cuidamiciudad';
$user = 'u245718453_cuidamiciudad';
$pass = 'Micuidad123';
$charset = 'utf8mb4';

try {
    $conexion = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Error en la conexión: ' . $e->getMessage());
}
?>
