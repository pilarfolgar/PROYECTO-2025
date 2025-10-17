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
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
  <style>
    /* Barra de fuerza de contraseña */
    #password-strength {
      height: 8px;
      border-radius: 5px;
      background-color: #ddd;
      margin-top: 5px;
    }
    #password-strength-fill {
      height: 100%;
      width: 0%;
      border-radius: 5px;
      transition: width 0.3s;
    }
  </style>
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
      <input type="password" name="pass" id="pass" class="form-control" required minlength="12" maxlength="16">
      <div class="form-text">
        12-16 caracteres, incluir mayúsculas, minúsculas, números y símbolos (@!$%&*?).
      </div>
      <div id="password-strength">
        <div id="password-strength-fill"></div>
      </div>
    </div>

    <div class="mb-3">
      <label for="cedula" class="form-label">Cédula (Uruguaya, 8 dígitos) *</label>
      <input type="text" name="cedula" id="cedula" class="form-control" pattern="\d{8}" maxlength="8" required>
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

    <!-- RECAPTCHA -->
    <div class="mb-3 text-center">
      <div class="g-recaptcha" data-sitekey="6LfHIusrAAAAAClke9PL9JJcbDnGAijza1w_IARk"></div>
    </div>

    <button type="submit" class="btn btn-primary">Registrarse</button>
    <button type="reset" class="btn btn-secondary">Cancelar</button>

    <p class="mt-3 text-center">
      ¿Ya tenés cuenta? <a href="iniciosesion.php">Iniciar sesión</a>
    </p>
  </form>
</main>

<?php require("footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const rolSelect = document.getElementById('rol');
  const grupoDiv = document.getElementById('grupo_div');
  const grupoSelect = document.getElementById('grupo');
  const cedulaInput = document.getElementById('cedula');
  const passInput = document.getElementById('pass');
  const strengthFill = document.getElementById('password-strength-fill');
  const form = document.getElementById('registro_form');

  // Solo números en cédula
  cedulaInput.addEventListener('input', function() {
    this.value = this.value.replace(/\D/g, '').slice(0, 8);
  });

  // Mostrar grupo si es estudiante
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

  // Función de fuerza de contraseña
  passInput.addEventListener('input', function() {
    const val = passInput.value;
    let strength = 0;

    if (val.length >= 12) strength += 25;
    if (/[A-Z]/.test(val)) strength += 20;
    if (/[a-z]/.test(val)) strength += 20;
    if (/\d/.test(val)) strength += 20;
    if (/[@!$%&*?]/.test(val)) strength += 15;

    strength = Math.min(strength, 100);

    strengthFill.style.width = strength + "%";

    if(strength < 50) strengthFill.style.backgroundColor = "red";
    else if(strength < 80) strengthFill.style.backgroundColor = "orange";
    else strengthFill.style.backgroundColor = "green";
  });

  // Validaciones al enviar
  form.addEventListener('submit', function(e) {
    // Grupo requerido para estudiantes
    if (rolSelect.value === 'estudiante' && !grupoSelect.value) {
      e.preventDefault();
      Swal.fire({icon:'warning', title:'Grupo requerido', text:'Debes seleccionar un grupo si sos estudiante.'});
      return;
    }

    // Cédula válida
    if (!/^\d{8}$/.test(cedulaInput.value)) {
      e.preventDefault();
      Swal.fire({icon:'error', title:'Cédula inválida', text:'La cédula debe tener exactamente 8 dígitos.'});
      return;
    }

    // Contraseña segura
    const pass = passInput.value;
    const passRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@!$%&*?])[A-Za-z\d@!$%&*?]{12,16}$/;
    if (!passRegex.test(pass)) {
      e.preventDefault();
      Swal.fire({icon:'error', title:'Contraseña insegura', text:'La contraseña debe tener 12-16 caracteres, mayúsculas, minúsculas, números y símbolos.'});
      return;
    }
  });

  // SweetAlert según sesión PHP
  <?php if(!empty($_SESSION['registro_exitoso'])): ?>
    Swal.fire({icon:'success', title:'Registro exitoso', text:'¡Te registraste correctamente!'});
    <?php unset($_SESSION['registro_exitoso']); ?>
  <?php endif; ?>

  <?php if(!empty($_SESSION['error_usuario'])): ?>
    let error = "<?php echo $_SESSION['error_usuario']; ?>";
    let mensaje = '';
    if (error === 'cedula_existente') mensaje = 'La cédula ya está registrada.';
    else if (error === 'email_existente') mensaje = 'El email ya está registrado.';
    else if (error === 'pass_insegura') mensaje = 'La contraseña no cumple con los requisitos de seguridad.';
    else if (error === 'ci_invalida') mensaje = 'Cédula inválida.';
    else if (error === 'campos_vacios') mensaje = 'Debes completar todos los campos.';
    else mensaje = 'Ocurrió un error. Intenta nuevamente.';
    Swal.fire({icon:'error', title:'Error', text:mensaje});
    <?php unset($_SESSION['error_usuario']); ?>
  <?php endif; ?>
});
</script>
</body>
</html>
