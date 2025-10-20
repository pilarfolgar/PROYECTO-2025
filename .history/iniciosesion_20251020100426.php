<?php
session_start();  // Al inicio absoluto para sesiones (antes de cualquier output)
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
  <style>
    /* Fondo ilustrativo con curvas para login */
    body.login-page {
      background: linear-gradient(135deg, var(--primario), var(--secundario));
      background-image: url("data:image/svg+xml,%3Csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill='%23A2D5F2' fill-opacity='0.2' d='M0,300 C150,200 350,400 500,300 L500,00 L0,0 Z'/%3E%3Cpath fill='%23A2D5F2' fill-opacity='0.15' d='M0,400 C200,300 400,500 600,400 L600,0 L0,0 Z'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-size: cover;
      background-attachment: fixed;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    /* Formulario centrado y con contraste */
    #login_form {
      background-color: white;
      border-radius: 12px;
      box-shadow: 0 12px 24px rgba(0,0,0,0.15);
      padding: 2rem;
      max-width: 400px;
      width: 100%;
    }
  </style>
</head>
<body class="login-page">
<?php require("HeaderIndex.php"); ?>

<main class="container d-flex justify-content-center align-items-center">
  <form action="procesar_inicio.php" method="POST" id="login_form">
    <h3 class="text-center mb-4">Iniciar Sesión</h3>
    
    <div class="mb-3">
      <label for="cedula" class="form-label">Cédula (8 dígitos) *</label>
      <input type="text" name="cedula" id="cedula" class="form-control" 
             pattern="\d{8}" maxlength="8" title="Debe ser exactamente 8 dígitos numéricos" required>
    </div>

    <div class="mb-3">
      <label for="pass" class="form-label">Contraseña *</label>
      <input type="password" name="pass" id="pass" class="form-control" required minlength="6">
    </div>

    <?php if (isset($_SESSION['mensaje'])): ?>
      <div class="mb-3">
        <div class="alert alert-danger" role="alert">
          <?php echo htmlspecialchars($_SESSION['mensaje']);
          unset($_SESSION['mensaje']); ?>
        </div>
      </div>
    <?php endif; ?>

    <button type="submit" class="btn btn-primary w-100 mb-2">Ingresar</button>
    <button type="reset" class="btn btn-secondary w-100">Cancelar</button>
  </form>
</main>

<p class="mt-3 text-center text-white">
  ¿No tenés cuenta? <a href="registro.php" class="text-white text-decoration-underline">Registrate</a>
</p>

<?php require("footer.php"); ?>

<script src="app.js"></script>
<script src="header.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('login_form');
  const cedulaInput = document.getElementById('cedula');

  // Solo números en cédula
  cedulaInput.addEventListener('input', function() {
    this.value = this.value.replace(/\D/g, '');
    if (this.value.length > 8) this.value = this.value.slice(0, 8);
  });

  // Validación al submit
  form.addEventListener('submit', function(e) {
    const cedula = cedulaInput.value;
    if (!/^\d{8}$/.test(cedula)) {
      e.preventDefault();
      alert('La cédula debe tener exactamente 8 dígitos.');
      cedulaInput.focus();
      return false;
    }
  });
});
</script>
</body>
</html>
