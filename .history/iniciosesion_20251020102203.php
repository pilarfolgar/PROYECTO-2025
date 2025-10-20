<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="styleinicio.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>
<body class="login-page">
<?php require("HeaderIndex.php"); ?>

<div class="lineas-art"></div>

<main class="container my-5">
  <form action="procesar_inicio.php" method="POST" id="login_form">
    <h3>Iniciar Sesión</h3>
    
    <div class="mb-3">
      <label for="cedula" class="form-label">Cédula (8 dígitos) *</label>
      <input type="text" name="cedula" id="cedula" class="form-control" 
             pattern="\d{8}" maxlength="8" title="Debe ser exactamente 8 dígitos" required>
    </div>

    <div class="mb-3">
      <label for="pass" class="form-label">Contraseña *</label>
      <input type="password" name="pass" id="pass" class="form-control" required minlength="6">
    </div>

    <?php if(isset($_SESSION['mensaje'])): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['mensaje']); unset($_SESSION['mensaje']); ?></div>
    <?php endif; ?>

    <button type="submit" class="btn btn-primary w-100 mb-2">Ingresar</button>
    <button type="reset" class="btn btn-secondary w-100">Cancelar</button>
  </form>

  <p class="mt-3 text-center">
    ¿No tenés cuenta? <a href="registro.php">Registrate</a>
  </p>
</main>

<?php require("footer.php"); ?>

<script src="app.js"></script>
<script src="header.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('login_form');
  const cedulaInput = document.getElementById('cedula');

  cedulaInput.addEventListener('input', function() {
    this.value = this.value.replace(/\D/g, '');
    if(this.value.length > 8) this.value = this.value.slice(0,8);
  });

  form.addEventListener('submit', function(e){
    if(!/^\d{8}$/.test(cedulaInput.value)){
      e.preventDefault();
      alert('La cédula debe tener exactamente 8 dígitos.');
      cedulaInput.focus();
    }
  });
});
</script>
</body>
</html>
