<?php
require_once 'includes/conexion.php';
session_start();

$mostrar_toast = false;
$tipo = 'success';
$mensaje = '';
$icono = 'bi-check-circle-fill';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);

    $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE correo = ?");
    $stmt->execute([$correo]);

    if ($stmt->rowCount() > 0) {
        $tipo = 'danger';
        $mensaje = 'Este correo ya está registrado.';
        $icono = 'bi-exclamation-triangle-fill';
        $mostrar_toast = true;
    } else {
        $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, correo, clave) VALUES (?, ?, ?)");
        if ($stmt->execute([$nombre, $correo, $clave])) {
            $tipo = 'success';
            $mensaje = 'Registro exitoso. Redirigiendo al login...';
            $icono = 'bi-check-circle-fill';
            $mostrar_toast = true;
        } else {
            $tipo = 'danger';
            $mensaje = 'No se pudo registrar el usuario.';
            $icono = 'bi-exclamation-triangle-fill';
            $mostrar_toast = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro - CuidaMiCiudad</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .toast-container {
        position: fixed;
        top: 1rem;
        right: 1rem;
        z-index: 1080;
    }
  </style>
</head>
<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">

<?php if ($mostrar_toast): ?>
  <div class="toast-container">
    <div class="toast align-items-center text-white bg-<?= $tipo ?> border-0 show" role="alert">
      <div class="d-flex">
        <div class="toast-body">
          <i class="bi <?= $icono ?> me-2"></i> <?= $mensaje ?>
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Cerrar"></button>
      </div>
    </div>
  </div>
  <?php if ($tipo === 'success'): ?>
    <script>
      setTimeout(() => {
        window.location.href = 'login.php';
      }, 2000);
    </script>
  <?php endif; ?>
<?php endif; ?>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow-sm border-0">
        <div class="card-body p-4">
          <h4 class="text-center mb-3"><i class="bi bi-person-plus-fill me-2"></i>Registro en CuidaMiCiudad</h4>
          <p class="text-center text-muted mb-4">Crea una cuenta para reportar problemas comunitarios</p>

          <form method="POST">
            <div class="mb-3">
              <label class="form-label">Nombre</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                <input type="text" name="nombre" class="form-control" required>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Correo</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                <input type="email" name="correo" class="form-control" required>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Contraseña</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" name="clave" class="form-control" required>
              </div>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-person-plus-fill me-1"></i> Registrarse
              </button>
            </div>
          </form>

          <div class="mt-3 text-center">
            <a href="login.php" class="text-decoration-none">
              <i class="bi bi-box-arrow-in-left"></i> ¿Ya tienes cuenta? Inicia sesión
            </a>
          </div>
        </div>
      </div>
      <p class="text-center mt-4 text-muted small">© <?= date('Y') ?> CuidaMiCiudad</p>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
