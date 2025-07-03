<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

require_once 'includes/conexion.php';
$usuario_id = $_SESSION['usuario']['id'];

$stmt = $conexion->prepare("SELECT * FROM reportes WHERE usuario_id = ? ORDER BY fecha DESC");
$stmt->execute([$usuario_id]);
$mis_reportes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mi Historial - CuidaMiCiudad</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4><i class="bi bi-clock-history me-2"></i>Historial de reportes</h4>
    <a href="dashboard.php" class="btn btn-outline-secondary">
      <i class="bi bi-arrow-left-circle"></i> Volver
    </a>
  </div>

  <?php if (count($mis_reportes) > 0): ?>
    <div class="table-responsive shadow-sm rounded">
      <table class="table table-bordered table-hover bg-white">
        <thead class="table-light">
          <tr>
            <th><i class="bi bi-exclamation-triangle"></i> Tipo</th>
            <th><i class="bi bi-text-left"></i> Descripción</th>
            <th><i class="bi bi-geo-alt"></i> Dirección</th>
            <th><i class="bi bi-info-circle"></i> Estado</th>
            <th><i class="bi bi-building"></i> Departamento</th>
            <th><i class="bi bi-calendar-event"></i> Fecha</th>
            <th><i class="bi bi-image"></i> Imagen</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($mis_reportes as $r): ?>
            <tr>
              <td><?= ucfirst($r['tipo_problema']) ?></td>
              <td><?= htmlspecialchars($r['descripcion']) ?></td>
              <td><?= htmlspecialchars($r['direccion']) ?></td>
              <td>
                <span class="badge bg-<?= 
                  $r['estado'] === 'resuelto' ? 'success' : 
                  ($r['estado'] === 'en_proceso' ? 'info' : 'warning') ?>">
                  <?= strtoupper($r['estado']) ?>
                </span>
              </td>
              <td><?= ucfirst($r['departamento']) ?></td>
              <td><?= date('d/m/Y H:i', strtotime($r['fecha'])) ?></td>
              <td>
                <?php if ($r['imagen']): ?>
                  <a href="uploads/<?= $r['imagen'] ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-eye"></i> Ver
                  </a>
                <?php else: ?>
                  <span class="text-muted">—</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="alert alert-info text-center">
      <i class="bi bi-emoji-neutral"></i> No has realizado reportes todavía.
    </div>
  <?php endif; ?>
</div>

</body>
</html>
