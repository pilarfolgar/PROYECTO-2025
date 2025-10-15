<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Verificar código</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>
<body class="container my-5">

<h3 class="text-center mb-4">Verificación de código</h3>

<form method="POST" action="verificar_codigo_procesar.php" class="p-4 border rounded bg-light shadow-sm mx-auto" style="max-width:400px;">
  <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>">
  <div class="mb-3">
    <label for="codigo" class="form-label">Código recibido</label>
    <input type="text" class="form-control" id="codigo" name="codigo" required>
  </div>
  <button type="submit" class="btn btn-success w-100">Verificar</button>
</form>

</body>
</html>
