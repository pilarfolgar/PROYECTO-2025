<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>
<body>

<?php require("header.php"); ?>
<?php session_start(); // Iniciar sesión para manejar mensajes de error ?>

<main class="container my-5">
  <form action="procesar_inicio.php" method="POST" id="login_form" class="p-4 border rounded bg-light shadow-sm">
    <div class="mb-3">
      <label for="cedula" class="form-label">Cédula</label>
      <input type="number" name="cedula" id="cedula" class="form-control">
    </div>

    <div class="mb-3">
      <label for="pass" class="form-label">Contraseña</label>
      <input type="password" name="pass" id="pass" class="form-control">
    </div>

    <?php if (isset($_SESSION['mensaje'])): ?>
      <div class="mb-3">
        <div class="alert alert-danger d-flex justify-content-center" role="alert">
          <?php echo $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?>
        </div>
      </div>
    <?php endif; ?>

    <button type="submit" class="btn btn-primary">Ingresar</button>
    <button type="reset" class="btn btn-secondary">Cancelar</button>
  </form>
  <p class="mt-3 text-center">
    ¿No tenés cuenta? <a href="registro.php">Registrate</a>
  </p>
  <p class="mt-1 text-center">
      ¿Olvidaste tu contraseña? <a href="recuperar.php">Recuperarla</a>
    </p>
</main>

<?php require("footer.php"); ?>

<script src="app.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>