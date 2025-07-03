<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

require_once '../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $estado = $_POST['estado'];

    $stmt = $conexion->prepare("UPDATE reportes SET estado = ? WHERE id = ?");
    $stmt->execute([$estado, $id]);

    header('Location: panel.php');
    exit;
}
?>
