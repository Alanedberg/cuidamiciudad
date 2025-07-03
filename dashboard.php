<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

require_once 'includes/conexion.php';
$usuario = $_SESSION['usuario'];

// Paginación
$por_pagina = 5;
$pagina_actual = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$inicio = ($pagina_actual - 1) * $por_pagina;

$total_stmt = $conexion->query("SELECT COUNT(*) FROM reportes");
$total_reportes = $total_stmt->fetchColumn();
$total_paginas = ceil($total_reportes / $por_pagina);

$stmt = $conexion->prepare("SELECT r.*, u.nombre FROM reportes r JOIN usuarios u ON r.usuario_id = u.id ORDER BY r.fecha DESC LIMIT :inicio, :limite");
$stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
$stmt->bindValue(':limite', $por_pagina, PDO::PARAM_INT);
$stmt->execute();
$reportes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - CuidaMiCiudad</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .badge.estado {
      font-size: 0.75rem;
      padding: 0.4em 0.6em;
    }
    .icono-tipo {
      font-size: 1.2rem;
      margin-right: 5px;
    }
    .card-header h6 {
      font-weight: 600;
    }
  </style>
</head>
<body class="bg-light">

<div class="container py-4">
  <!-- Encabezado -->
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
    <div>
      <h4 class="mb-1"><i class="bi bi-person-circle me-2"></i>Hola, <?= htmlspecialchars($usuario['nombre']) ?></h4>
      <span class="text-muted small">Rol: 
        <span class="badge bg-secondary"><i class="bi bi-person-badge-fill me-1"></i><?= strtoupper($usuario['rol']) ?></span>
      </span>
    </div>
    <div class="mt-3 mt-md-0">
      <a href="logout.php" class="btn btn-sm btn-outline-danger"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
    </div>
  </div>

  <!-- Acciones -->
  <div class="d-flex flex-wrap gap-2 mb-4">
    <a href="nuevo_reporte.php" class="btn btn-primary"><i class="bi bi-plus-circle-fill me-1"></i> Nuevo Reporte</a>
    <a href="historial.php" class="btn btn-outline-secondary"><i class="bi bi-clock-history me-1"></i> Mis Reportes</a>
    <?php if ($usuario['rol'] === 'admin'): ?>
      <a href="admin/panel.php" class="btn btn-warning text-white"><i class="bi bi-tools me-1"></i> Panel Administrativo</a>
    <?php endif; ?>
  </div>

  <!-- Tabla -->
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h6 class="mb-0"><i class="bi bi-card-checklist me-2"></i>Reportes recientes</h6>
      <small class="text-white-50">Página <?= $pagina_actual ?> de <?= $total_paginas ?></small>
    </div>
    <div class="card-body table-responsive p-0">
      <table class="table table-striped table-bordered mb-0 text-center align-middle">
        <thead class="table-light">
          <tr>
            <th><i class="bi bi-flag-fill"></i> Tipo</th>
            <th><i class="bi bi-chat-dots-fill"></i> Descripción</th>
            <th><i class="bi bi-geo-alt-fill"></i> Dirección</th>
            <th><i class="bi bi-hourglass-split"></i> Estado</th>
            <th><i class="bi bi-diagram-3-fill"></i> Departamento</th>
            <th><i class="bi bi-calendar-event"></i> Fecha</th>
            <th><i class="bi bi-person-fill"></i> Usuario</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($reportes as $r): ?>
            <tr>
              <td><i class="bi bi-exclamation-circle text-danger icono-tipo"></i><?= ucfirst($r['tipo_problema']) ?></td>
              <td><?= htmlspecialchars($r['descripcion']) ?></td>
              <td><?= htmlspecialchars($r['direccion']) ?></td>
              <td>
                <span class="badge estado bg-<?= 
                  $r['estado'] === 'resuelto' ? 'success' : (
                  $r['estado'] === 'en_proceso' ? 'info' : 'warning') ?>">
                  <?= strtoupper($r['estado']) ?>
                </span>
              </td>
              <td><?= ucfirst($r['departamento']) ?></td>
              <td><?= date('d/m/Y H:i', strtotime($r['fecha'])) ?></td>
              <td><i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($r['nombre']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Paginación -->
    <div class="card-footer text-center bg-light">
      <nav>
        <ul class="pagination justify-content-center mb-0">
          <?php if ($pagina_actual > 1): ?>
            <li class="page-item">
              <a class="page-link" href="?pagina=<?= $pagina_actual - 1 ?>"><i class="bi bi-chevron-left"></i></a>
            </li>
          <?php endif; ?>
          <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
            <li class="page-item <?= $i == $pagina_actual ? 'active' : '' ?>">
              <a class="page-link" href="?pagina=<?= $i ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>
          <?php if ($pagina_actual < $total_paginas): ?>
            <li class="page-item">
              <a class="page-link" href="?pagina=<?= $pagina_actual + 1 ?>"><i class="bi bi-chevron-right"></i></a>
            </li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </div>
</div>

</body>
</html>
