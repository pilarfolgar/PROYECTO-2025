<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Restablecer contraseña</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>
<body class="container my-5">

<h3 class="text-center mb-4">Nueva contraseña</h3>

<form method="POST" action="actualizar_contrasena.php" class="p-4 border rounded bg-light shadow-sm mx-auto" style="max-width:400px;">
  <div class="mb-3">
    <label for="pass1" class="form-label">Nueva contraseña</label>
    <input type="password" class="form-control" id="pass1" name="pass1" required minlength="6">
  </div>
  <div class="mb-3">
    <label for="pass2" class="form-label">Confirmar contraseña</label>
    <input type="password" class="form-control" id="pass2" name="pass2" required minlength="6">
  </div>
  <button type="submit" class="btn btn-primary w-100">Actualizar</button>
</form>

</body>
</html>
