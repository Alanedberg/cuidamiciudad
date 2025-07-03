<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

require_once '../includes/conexion.php';

$departamento_filtro = $_GET['departamento'] ?? 'todos';

$sql = "SELECT r.*, u.nombre FROM reportes r JOIN usuarios u ON r.usuario_id = u.id";
$params = [];

if ($departamento_filtro !== 'todos') {
    $sql .= " WHERE r.departamento = ?";
    $params[] = $departamento_filtro;
}

$sql .= " ORDER BY r.fecha DESC";
$stmt = $conexion->prepare($sql);
$stmt->execute($params);
$reportes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Administración - CuidaMiCiudad</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">

<div class="container py-4">
  <!-- Encabezado -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0"><i class="bi bi-shield-lock-fill text-primary"></i> Panel de Administración</h3>
    <a href="../dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-box-arrow-left"></i> Volver</a>
  </div>

  <!-- Filtro por departamento -->
  <div class="mb-3 d-flex justify-content-between align-items-center">
    <form method="GET" class="d-flex align-items-center gap-2">
      <label class="form-label mb-0"><i class="bi bi-funnel-fill text-primary"></i> Filtrar por departamento:</label>
      <select name="departamento" class="form-select" onchange="this.form.submit()">
        <option value="todos" <?= $departamento_filtro === 'todos' ? 'selected' : '' ?>>Todos</option>
        <option value="alumbrado" <?= $departamento_filtro === 'alumbrado' ? 'selected' : '' ?>>Alumbrado Público</option>
        <option value="urbanismo" <?= $departamento_filtro === 'urbanismo' ? 'selected' : '' ?>>Urbanismo</option>
        <option value="residuos" <?= $departamento_filtro === 'residuos' ? 'selected' : '' ?>>Residuos Sólidos</option>
      </select>
    </form>
    <small class="text-muted">Total: <?= count($reportes) ?> reportes</small>
  </div>

  <!-- Tabla de reportes -->
  <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle shadow-sm bg-white">
      <thead class="table-light text-center">
        <tr>
          <th><i class="bi bi-hash"></i></th>
          <th><i class="bi bi-exclamation-circle"></i> Tipo</th>
          <th><i class="bi bi-card-text"></i> Descripción</th>
          <th><i class="bi bi-geo-alt"></i> Dirección</th>
          <th><i class="bi bi-activity"></i> Estado</th>
          <th><i class="bi bi-diagram-3"></i> Departamento</th>
          <th><i class="bi bi-person"></i> Usuario</th>
          <th><i class="bi bi-calendar-event"></i> Fecha</th>
          <th><i class="bi bi-image"></i> Imagen</th>
        </tr>
      </thead>
      <tbody class="text-center">
        <?php foreach ($reportes as $r): ?>
        <tr>
          <td><?= $r['id'] ?></td>
          <td><?= ucfirst($r['tipo_problema']) ?></td>
          <td><?= htmlspecialchars($r['descripcion']) ?></td>
          <td><?= htmlspecialchars($r['direccion']) ?></td>
          <td>
            <form action="estado.php" method="POST" class="d-flex justify-content-center">
              <input type="hidden" name="id" value="<?= $r['id'] ?>">
              <select name="estado" class="form-select form-select-sm me-2">
                <option value="pendiente" <?= $r['estado'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                <option value="en_proceso" <?= $r['estado'] == 'en_proceso' ? 'selected' : '' ?>>En proceso</option>
                <option value="resuelto" <?= $r['estado'] == 'resuelto' ? 'selected' : '' ?>>Resuelto</option>
              </select>
              <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-check2-circle"></i></button>
            </form>
          </td>
          <td>
            <form action="asignar.php" method="POST" class="d-flex justify-content-center">
              <input type="hidden" name="id" value="<?= $r['id'] ?>">
              <select name="departamento" class="form-select form-select-sm me-2">
                <option value="ninguno" <?= $r['departamento'] == 'ninguno' ? 'selected' : '' ?>>—</option>
                <option value="alumbrado" <?= $r['departamento'] == 'alumbrado' ? 'selected' : '' ?>>Alumbrado Público</option>
                <option value="urbanismo" <?= $r['departamento'] == 'urbanismo' ? 'selected' : '' ?>>Urbanismo</option>
                <option value="residuos" <?= $r['departamento'] == 'residuos' ? 'selected' : '' ?>>Residuos Sólidos</option>
              </select>
              <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-send-check"></i></button>
            </form>
          </td>
          <td><?= htmlspecialchars($r['nombre']) ?></td>
          <td><?= date('d/m/Y H:i', strtotime($r['fecha'])) ?></td>
          <td>
            <?php if ($r['imagen']): ?>
              <a href="../uploads/<?= $r['imagen'] ?>" target="_blank" class="btn btn-sm btn-outline-dark">
                <i class="bi bi-eye-fill"></i> Ver
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
</div>

</body>
</html>
