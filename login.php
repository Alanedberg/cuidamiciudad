<?php
require_once 'includes/conexion.php';
session_start();

$mostrar_toast = false;
$tipo = 'success';
$mensaje = '';
$icono = 'bi-check-circle-fill';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo']);
    $clave = $_POST['clave'];

    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE correo = ?");
    $stmt->execute([$correo]);

    if ($stmt->rowCount() == 1) {
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        if (password_verify($clave, $usuario['clave'])) {
            $_SESSION['usuario'] = [
                'id' => $usuario['id'],
                'correo' => $usuario['correo'],
                'rol' => $usuario['rol'],
                'nombre' => $usuario['nombre']
            ];
            $mensaje = 'Inicio de sesión exitoso. Redirigiendo...';
            $icono = 'bi-check-circle-fill';
            $mostrar_toast = true;
        } else {
            $mensaje = 'Correo o contraseña incorrectos.';
            $tipo = 'danger';
            $icono = 'bi-exclamation-triangle-fill';
            $mostrar_toast = true;
        }
    } else {
        $mensaje = 'Correo o contraseña incorrectos.';
        $tipo = 'danger';
        $icono = 'bi-exclamation-triangle-fill';
        $mostrar_toast = true;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar sesión - CuidaMiCiudad</title>
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
        window.location.href = 'dashboard.php';
      }, 2000);
    </script>
  <?php endif; ?>
<?php endif; ?>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow-sm border-0">
        <div class="card-body p-4">
          <h4 class="text-center mb-3"><i class="bi bi-shield-lock-fill me-2"></i>CuidaMiCiudad</h4>
          <p class="text-center text-muted mb-4">Inicia sesión para reportar incidencias</p>

          <form method="POST">
            <div class="mb-3">
              <label for="correo" class="form-label">Correo electrónico</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                <input type="email" name="correo" class="form-control" required>
              </div>
            </div>
            <div class="mb-3">
              <label for="clave" class="form-label">Contraseña</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" name="clave" class="form-control" required>
              </div>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar sesión
              </button>
            </div>
          </form>

          <div class="mt-3 text-center">
            <a href="registrar.php" class="text-decoration-none">
              <i class="bi bi-person-plus-fill"></i> ¿No tienes cuenta? Regístrate
            </a>
          </div>
        </div>
      </div>
      <p class="text-center mt-4 text-muted small">
        © <?= date('Y') ?> CuidaMiCiudad
      </p>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
