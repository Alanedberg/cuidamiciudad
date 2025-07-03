<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

require_once '../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $departamento = $_POST['departamento'];

    $stmt = $conexion->prepare("UPDATE reportes SET departamento = ? WHERE id = ?");
    $stmt->execute([$departamento, $id]);

    header('Location: panel.php');
    exit;
}
?>
