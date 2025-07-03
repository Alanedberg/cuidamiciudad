<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

require_once 'includes/conexion.php';

$reporte_exitoso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['usuario']['id'];
    $tipo = $_POST['tipo_problema'];
    $descripcion = trim($_POST['descripcion']);
    $direccion = trim($_POST['direccion']);

    $imagen_nombre = '';
    if (!empty($_FILES['imagen']['name'])) {
        $imagen_nombre = time() . '_' . basename($_FILES['imagen']['name']);
        move_uploaded_file($_FILES['imagen']['tmp_name'], "uploads/" . $imagen_nombre);
    }

    $stmt = $conexion->prepare("INSERT INTO reportes (usuario_id, tipo_problema, descripcion, direccion, imagen) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$usuario_id, $tipo, $descripcion, $direccion, $imagen_nombre]);

    $reporte_exitoso = true;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Nuevo Reporte - CuidaMiCiudad</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <style>
    .select2-container .select2-selection--single {
      height: 38px;
      padding: 5px 12px;
    }
    .form-icon {
      margin-right: 8px;
    }
  </style>
</head>
<body class="bg-light">

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-clipboard-plus"></i> Nuevo Reporte</h4>
    <a href="dashboard.php" class="btn btn-secondary">← Volver</a>
  </div>

  <form method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm border-0 bg-white">
    <div class="mb-3">
      <label class="form-label"><i class="bi bi-exclamation-circle form-icon"></i>Tipo de problema</label>
      <select name="tipo_problema" class="form-select tipo-select" required>
        <option value="">Selecciona uno</option>
        <option value="basura">🗑️ Basura acumulada</option>
        <option value="lampara">💡 Lámpara apagada</option>
        <option value="bache">🕳️ Bache en la calle</option>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label"><i class="bi bi-chat-left-dots form-icon"></i>Descripción</label>
      <textarea name="descripcion" class="form-control" rows="3" required placeholder="Describe brevemente el problema..."></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label"><i class="bi bi-geo-alt form-icon"></i>Dirección / Sector</label>
      <input type="text" name="direccion" class="form-control" required placeholder="Ej: Calle Duarte, sector El Millón">
    </div>
    <div class="mb-3">
      <label class="form-label"><i class="bi bi-camera form-icon"></i>Foto (opcional)</label>
      <input type="file" name="imagen" class="form-control">
    </div>
    <div class="d-grid">
      <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> Enviar reporte</button>
    </div>
  </form>
</div>

<?php if ($reporte_exitoso): ?>
<script>
  Swal.fire({
    icon: 'success',
    title: '¡Reporte enviado!',
    text: 'Tu reporte fue registrado correctamente.',
    confirmButtonText: 'OK'
  }).then(() => {
    window.location.href = 'dashboard.php';
  });
</script>
<?php endif; ?>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<script>
  $(document).ready(function () {
    $('.tipo-select').select2({
      placeholder: "Selecciona el tipo de problema"
    });
  });
</script>
</body>
</html>
