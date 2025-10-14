<?php
session_start();
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

<!-- Mensaje de éxito para registro guardado -->
<?php if (isset($_SESSION['msg_usuario']) && $_SESSION['msg_usuario'] === 'guardado'): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  Swal.fire({
    icon: 'success',
    title: '¡Éxito!',
    text: 'Usuario registrado con éxito',
    timer: 2500,
    showConfirmButton: false
  });
});
</script>
<?php unset($_SESSION['msg_usuario']); endif; ?>

<!-- Mensaje para verificación pendiente (docentes) -->
<?php if (isset($_SESSION['msg_usuario']) && $_SESSION['msg_usuario'] === 'pendiente_verificacion'): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  Swal.fire({
    icon: 'info',
    title: 'Registro pendiente',
    text: 'Tu cuenta como docente debe ser verificada por un administrador antes de poder ingresar.',
    confirmButtonText: 'Entendido'
  });
});
</script>
<?php unset($_SESSION['msg_usuario']); endif; ?>

<!-- Bloques de errores -->
<?php if (isset($_SESSION['error_usuario']) && $_SESSION['error_usuario'] === 'ci_invalida'): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  Swal.fire({
    icon: 'error',
    title: 'Cédula inválida',
    text: 'La cédula ingresada no es válida. Asegúrese de que tenga 8 dígitos y el dígito verificador correcto (formato uruguayo).',
    confirmButtonText: 'Entendido'
  });
});
</script>
<?php unset($_SESSION['error_usuario']); endif; ?>

<?php if (isset($_SESSION['error_usuario']) && $_SESSION['error_usuario'] === 'rol_invalido'): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  Swal.fire({
    icon: 'error',
    title: 'Rol inválido',
    text: 'Selecciona un rol válido (estudiante o docente).',
    confirmButtonText: 'Entendido'
  });
});
</script>
<?php unset($_SESSION['error_usuario']); endif; ?>

<?php if (isset($_SESSION['error_usuario']) && $_SESSION['error_usuario'] === 'email_existente'): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  Swal.fire({
    icon: 'error',
    title: 'Email duplicado',
    text: 'Ya existe un usuario con ese email. Usa otro o inicia sesión.',
    confirmButtonText: 'Entendido'
  });
});
</script>
<?php unset($_SESSION['error_usuario']); endif; ?>

<?php if (isset($_SESSION['error_usuario']) && $_SESSION['error_usuario'] === 'campos_vacios'): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  Swal.fire({
    icon: 'warning',
    title: 'Campos incompletos',
    text: 'Todos los campos son obligatorios. Completa el formulario y vuelve a intentar.',
    confirmButtonText: 'Entendido'
  });
});
</script>
<?php unset($_SESSION['error_usuario']); endif; ?>

<?php if (isset($_SESSION['error_usuario']) && in_array($_SESSION['error_usuario'], ['error_general', 'usuario_existente'])): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  let title = 'Error';
  let text = 'Ocurrió un error al registrar el usuario';
  if ($_SESSION['error_usuario'] === 'usuario_existente') {
    title = 'Cédula duplicada';
    text = 'Ya existe un usuario con esa cédula';
  } else if ($_SESSION['error_usuario'] === 'error_general') {
    title = 'Error en el servidor';
    text = 'Hubo un problema al guardar los datos. Intenta de nuevo o contacta al administrador.';
  }
  Swal.fire({
    icon: 'error',
    title: title,
    text: text,
    confirmButtonText: 'Entendido'
  });
});
</script>
<?php unset($_SESSION['error_usuario']); endif; ?>

<?php if (isset($_SESSION['error_usuario']) && !in_array($_SESSION['error_usuario'], ['ci_invalida', 'rol_invalido', 'email_existente', 'campos_vacios', 'error_general', 'usuario_existente'])): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  Swal.fire({
    icon: 'error',
    title: 'Error inesperado',
    text: 'Ocurrió un error inesperado. Revisa los datos e intenta de nuevo.',
    confirmButtonText: 'Entendido'
  });
});
</script>
<?php unset($_SESSION['error_usuario']); endif; ?>

<main class="container my-5">
  <h1 class="text-center mb-4">Inicio de Sesión</h1>

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
             pattern="\d{8}" maxlength="8" title="Debe ser exactamente 8 dígitos numéricos" required>
    </div>

    <div class="mb-3">
      <label for="rol" class="form-label">Rol *</label>
      <select name="rol" id="rol" class="form-select" required>
        <option value="">Seleccione...</option>
        <option value="estudiante">Estudiante</option>
        <option value="docente">Docente</option>
      </select>
    </div>

    <!-- ✅ NUEVO: Campo Clase -->
    <div class="mb-3" id="clase_div" style="display: none;">
      <label for="clase" class="form-label">Clase *</label>
      <select name="clase" id="clase" class="form-select">
        <option value="">Seleccione una clase...</option>
        <option value="1A">1A</option>
        <option value="1B">1B</option>
        <option value="2A">2A</option>
        <option value="2B">2B</option>
        <option value="3A">3A</option>
        <option value="3B">3B</option>
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
  const form = document.getElementById('registro_form');
  const cedulaInput = document.getElementById('cedula');
  const rolSelect = document.getElementById('rol');
  const claseDiv = document.getElementById('clase_div');
  const claseSelect = document.getElementById('clase');

  cedulaInput.addEventListener('input', function() {
    this.value = this.value.replace(/\D/g, '');
    if (this.value.length > 8) this.value = this.value.slice(0, 8);
  });

  rolSelect.addEventListener('change', function() {
    if (this.value === 'estudiante') {
      claseDiv.style.display = 'block';
      claseSelect.setAttribute('required', 'required');
    } else {
      claseDiv.style.display = 'none';
      claseSelect.removeAttribute('required');
      claseSelect.value = '';
    }
  });

  form.addEventListener('submit', function(e) {
    const cedula = cedulaInput.value;
    const rol = rolSelect.value;
    const clase = claseSelect.value;

    if (!rol) {
      e.preventDefault();
      Swal.fire({
        icon: 'warning',
        title: 'Rol requerido',
        text: 'Selecciona un rol (estudiante o docente).',
        confirmButtonText: 'OK'
      });
      rolSelect.focus();
      return;
    }

    if (rol === 'estudiante' && !clase) {
      e.preventDefault();
      Swal.fire({
        icon: 'warning',
        title: 'Clase requerida',
        text: 'Debes seleccionar una clase si sos estudiante.',
        confirmButtonText: 'OK'
      });
      claseSelect.focus();
      return;
    }

    if (cedula && !/^\d{8}$/.test(cedula)) {
      e.preventDefault();
      Swal.fire({
        icon: 'error',
        title: 'Cédula inválida',
        text: 'La cédula debe tener exactamente 8 dígitos numéricos.',
        confirmButtonText: 'OK'
      });
      cedulaInput.focus();
      return;
    }
  });
});
</script>
</body>
</html>
