<?php
session_start();
require("conexion.php");
$con = conectar_bd(); 
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro</title>
  <link rel="stylesheet" href="style.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>
<body>
<?php require("HeaderIndex.php"); ?>

<main class="container my-5">
  <h1 class="text-center mb-4">Registro</h1>

  <form action="procesar_registro.php" method="POST" id="registro_form" class="p-4 border rounded bg-light shadow-sm mx-auto" style="max-width: 500px;">
  
    <div class="mb-3">
      <label for="nombre" class="form-label">Nombre *</label>
      <input type="text" name="nombre" id="nombre" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="apellido" class="form-label">Apellido *</label>
      <input type="text" name="apellido" id="apellido" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="email" class="form-label">Email *</label>
      <input type="email" name="email" id="email" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="pass" class="form-label">Contraseña *</label>
      <input type="password" name="pass" id="pass" class="form-control" required minlength="6">
    </div>

    <div class="mb-3">
      <label for="cedula" class="form-label">Cédula (Uruguaya, 8 dígitos) *</label>
      <input type="text" name="cedula" id="cedula" class="form-control" 
             pattern="\d{8}" maxlength="8" required>
    </div>

    <div class="mb-3">
      <label for="rol" class="form-label">Rol *</label>
      <select name="rol" id="rol" class="form-select" required>
        <option value="">Seleccione...</option>
        <option value="estudiante">Estudiante</option>
        <option value="docente">Docente</option>
      </select>
    </div>

    <div class="mb-3" id="grupo_div" style="display: none;">
      <label for="grupo" class="form-label">Grupo *</label>
      <select name="grupo" id="grupo" class="form-select">
        <option value="">Seleccione un grupo...</option>
        <?php
        $res_grupo = $con->query("SELECT id_grupo, nombre, orientacion FROM grupo ORDER BY nombre");
        while($row = $res_grupo->fetch_assoc()) {
            $display = htmlspecialchars($row['nombre'] . ' - ' . $row['orientacion']);
            echo "<option value='".htmlspecialchars($row['id_grupo'])."'>$display</option>";
        }
        ?>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
  const rolSelect = document.getElementById('rol');
  const grupoDiv = document.getElementById('grupo_div');
  const grupoSelect = document.getElementById('grupo');
  const cedulaInput = document.getElementById('cedula');
  const form = document.getElementById('registro_form');

  cedulaInput.addEventListener('input', function() {
    this.value = this.value.replace(/\D/g, '').slice(0, 8);
  });

  rolSelect.addEventListener('change', function() {
    if (this.value === 'estudiante') {
      grupoDiv.style.display = 'block';
      grupoSelect.setAttribute('required', 'required');
    } else {
      grupoDiv.style.display = 'none';
      grupoSelect.removeAttribute('required');
      grupoSelect.value = '';
    }
  });

  form.addEventListener('submit', function(e) {
    if (rolSelect.value === 'estudiante' && !grupoSelect.value) {
      e.preventDefault();
      Swal.fire({
        icon: 'warning',
        title: 'Grupo requerido',
        text: 'Debes seleccionar un grupo si sos estudiante.'
      });
    }
    if (!/^\d{8}$/.test(cedulaInput.value)) {
      e.preventDefault();
      Swal.fire({
        icon: 'error',
        title: 'Cédula inválida',
        text: 'La cédula debe tener exactamente 8 dígitos.'
      });
    }
  });
});
</script>
</body>
</html>
