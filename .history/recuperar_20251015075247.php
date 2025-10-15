<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Recuperar contraseña</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>
<body class="container my-5">

<h3 class="text-center mb-4">Recuperar contraseña</h3>

<form method="POST" action="enviar_token_local.php" class="p-4 border rounded bg-light shadow-sm mx-auto" style="max-width:400px;">
  <div class="mb-3">
    <label for="email" class="form-label">Correo electrónico</label>
    <input type="email" class="form-control" id="email" name="email" required>
  </div>
  <button type="submit" class="btn btn-primary w-100">Generar enlace de recuperación</button>
</form>

</body>
</html>
