<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>
<body>


<?php require("header.php"); ?>

<main class="container my-5">
  <form action="procesar_registro.php" method="POST" id="registro_form" class="p-4 border rounded bg-light shadow-sm mx-auto" style="max-width: 500px;">
  
    <div class="mb-3">
      <label for="nombre" class="form-label">Nombre</label>
      <input type="text" name="nombre" id="nombre" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="apellido" class="form-label">Apellido</label>
      <input type="text" name="apellido" id="apellido" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" name="email" id="email" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="pass" class="form-label">Contraseña</label>
      <input type="password" name="pass" id="pass" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="cedula" class="form-label">Cédula</label>
      <input type="text" name="cedula" id="cedula" class="form-control">
    </div>

    <div class="mb-3">
      <label for="rol" class="form-label">Rol</label>
      <select name="rol" id="rol" class="form-select">
        <option value="">Seleccione...</option>
        <option value="estudiante">Estudiante</option>
        <option value="administrativo">Administrativo</option>
        <option value="docente">Docente</option>
      </select>
    </div>

    <button type="submit" class="btn btn-primary">Cargar</button>
    <button type="reset" class="btn btn-secondary">Cancelar</button>

    <p class="mt-3 text-center">
      ¿Ya tenés cuenta? <a href="iniciosesion.php">Iniciar sesión</a>
    </p>
  </form>
</main>

<?php require("footer.php"); ?>
<script src="app.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>