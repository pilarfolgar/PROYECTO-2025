<?php
session_start(); // Inicia sesión
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Administrativo - InfraLex</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="admin-script.js"></script>
</head>
<body>

<!-- HEADER -->
<header>
  <div class="HeaderIzq">
    <h1>InfraLex</h1>
    <h6>Instituto Tecnológico Superior de Paysandú</h6>
  </div>
  <div class="header-right">
    <a href="index.php"><img src="imagenes/logopoyecto.png" alt="Logo" class="logo"></a>
  </div>
</header>

<!-- NAV -->
<nav>
  <a href="index.php">Inicio</a>
  <a href="">Reservas</a>
  <a href="#reportes">Reportes</a>
  <a href="#usuarios">Usuarios</a>
  <a href="logout.php">Log Out</a>
</nav>

<!-- PANEL ADMINISTRATIVO -->
<main class="contenedor" id="gestion">
  <!-- Tarjetas de acciones -->
  <div class="tarjeta">
    <h3>Docentes</h3>
    <p>Registrar y actualizar datos de los docentes.</p>
    <a href="#" class="boton" onclick="mostrarForm('form-docente')">➕ Agregar Docente</a>
  </div>

  <div class="tarjeta">
    <h3>Asignaturas</h3>
    <p>Crear, modificar y administrar asignaturas.</p>
    <a href="#" class="boton" onclick="mostrarForm('form-asignatura')">➕ Agregar Asignatura</a>
  </div>

  <div class="tarjeta">
    <h3>Horarios</h3>
    <p>Organizar y actualizar los horarios de clases.</p>
    <a href="#" class="boton" onclick="mostrarForm('form-horario')">➕ Agregar Horario</a>
  </div>

  <div class="tarjeta">
    <h3>Aulas</h3>
    <p>Administrar aulas disponibles y asignaciones.</p>
    <a href="#" class="boton" onclick="mostrarForm('form-aula')">➕ Agregar Aula</a>
  </div>
</main>
<?php require("footer.php"); ?>

<!-- FORM DOCENTE -->
<section id="form-docente" class="formulario" style="display: none;">
  <button type="button" class="cerrar" onclick="cerrarForm('form-docente')" aria-label="Cerrar formulario">✖</button>
  <form action="procesar-docente.php" method="POST" class="needs-validation form-reserva-style novalidate">
    <h2 class="form-title">Registrar Docente</h2>
    <div class="row g-3">
      <div class="col-md-6">
        <label for="nombreDocente" class="form-label">Nombre</label>
        <input type="text" class="form-control" id="nombreDocente" name="nombre" required placeholder="Ej. Juan">
      </div>
      <div class="col-md-6">
        <label for="apellidoDocente" class="form-label">Apellido</label>
        <input type="text" class="form-control" id="apellidoDocente" name="apellido" required placeholder="Ej. Pérez">
      </div>
      <div class="col-md-6">
        <label for="documentoDocente" class="form-label">Cédula</label>
        <input type="number" class="form-control" id="documentoDocente" name="documento" required placeholder="Ej. 12345678">
      </div>
      <div class="col-md-6">
        <label for="emailDocente" class="form-label">Correo electrónico</label>
        <input type="email" class="form-control" id="emailDocente" name="email" required placeholder="ej. juan@infra.com">
      </div>
      <div class="col-md-6">
        <label for="telefonoDocente" class="form-label">Teléfono</label>
        <input type="tel" class="form-control" id="telefonoDocente" name="telefono" placeholder="Ej. +598 12345678">
      </div>
    </div>
    <button type="submit" class="boton mt-3">Guardar</button>
  </form>
</section>

<!-- FORM ASIGNATURA -->
<section id="form-asignatura" class="formulario" style="display: none;">
  <button type="button" class="cerrar" onclick="cerrarForm('form-asignatura')" aria-label="Cerrar formulario">✖</button>
  <form action="procesar-asignatura.php" method="POST" class="needs-validation form-reserva-style novalidate">
    <h2 class="form-title">Registrar Asignatura</h2>
    <div class="row g-3">
      <div class="col-md-6">
        <label for="nombreAsignatura" class="form-label">Nombre de la asignatura</label>
        <input type="text" class="form-control" id="nombreAsignatura" name="nombre" required placeholder="Ej. Programación II">
      </div>
      <div class="col-md-6">
        <label for="codigoAsignatura" class="form-label">Código</label>
        <input type="text" class="form-control" id="codigoAsignatura" name="codigo" required placeholder="Ej. PROG201">
      </div>
      <div class="col-12">
        <label for="docentesAsignatura" class="form-label">Docentes asignados (seleccione múltiples)</label>
        <select class="form-select" id="docentesAsignatura" name="docentes[]" multiple required>
          <?php
          require("conexion.php");
          $con = conectar_bd();
          $sql = "SELECT cedula, nombrecompleto, apellido FROM usuario WHERE rol = 'docente'";
          $result = $con->query($sql);
          while ($row = $result->fetch_assoc()) {
              echo '<option value="' . $row['cedula'] . '">Prof. ' . $row['nombrecompleto'] . ' ' . $row['apellido'] . '</option>';
          }
          ?>
        </select>
      </div>
    </div>
    <button type="submit" class="boton mt-3">Guardar</button>
  </form>
</section>

<!-- FORM HORARIO -->
<section id="form-horario" class="formulario" style="display: none;">
  <button type="button" class="cerrar" onclick="cerrarForm('form-horario')" aria-label="Cerrar formulario">✖</button>
  <form action="procesar-horario.php" method="POST" class="needs-validation form-reserva-style novalidate">
    <h2 class="form-title">Registrar Horario</h2>
    <div class="row g-3">
      <div class="col-md-6">
        <label for="asignaturaHorario" class="form-label">Asignatura</label>
        <select class="form-select" id="asignaturaHorario" name="id_asignatura" required>
          <option value="">Seleccione asignatura...</option>
          <?php
          $sql = "SELECT id_asignatura, nombre, codigo FROM asignatura ORDER BY nombre";
          $result = $con->query($sql);
          while ($row = $result->fetch_assoc()) {
              echo '<option value="' . $row['id_asignatura'] . '">' . $row['nombre'] . ' (' . $row['codigo'] . ')</option>';
          }
          ?>
        </select>
      </div>
      <div class="col-md-6">
        <label for="diaHorario" class="form-label">Día</label>
        <select class="form-select" id="diaHorario" name="dia" required>
          <option value="">Elija...</option>
          <option value="lunes">Lunes</option>
          <option value="martes">Martes</option>
          <option value="miercoles">Miércoles</option>
          <option value="jueves">Jueves</option>
          <option value="viernes">Viernes</option>
        </select>
      </div>
      <div class="col-md-6">
        <label for="horaInicioHorario" class="form-label">Hora de inicio</label>
        <input type="time" class="form-control" id="horaInicioHorario" name="hora_inicio" required>
      </div>
      <div class="col-md-6">
        <label for="horaFinHorario" class="form-label">Hora de fin</label>
        <input type="time" class="form-control" id="horaFinHorario" name="hora_fin" required>
      </div>
      <div class="col-md-6">
        <label for="claseHorario" class="form-label">Clase/Grupo (opcional)</label>
        <input type="text" class="form-control" id="claseHorario" name="clase" placeholder="Ej. 1°MA">
      </div>
    </div>
    <button type="submit" class="boton mt-3">Guardar</button>
  </form>
</section>

<!-- FORM AULA -->
<section id="form-aula" class="formulario" style="display: none;">
  <button type="button" class="cerrar" onclick="cerrarForm('form-aula')" aria-label="Cerrar formulario">✖</button>
  <form action="procesar-aula.php" method="POST" class="needs-validation form-reserva-style novalidate">
    <h2 class="form-title">Registrar Aula</h2>
    <div class="row g-3">
      <div class="col-md-6">
        <label for="codigoAula" class="form-label">Número o código de aula</label>
        <input type="text" class="form-control" id="codigoAula" name="codigo" required placeholder="Ej. Aula 101">
      </div>
      <div class="col-md-6">
        <label for="capacidadAula" class="form-label">Capacidad</label>
        <input type="number" class="form-control" id="capacidadAula" name="capacidad" min="1" placeholder="Ej. 30">
      </div>
      <div class="col-12">
        <label for="ubicacionAula" class="form-label">Ubicación</label>
        <input type="text" class="form-control" id="ubicacionAula" name="ubicacion" placeholder="Ej. Piso 2, Bloque A">
      </div>
    </div>
    <button type="submit" class="boton mt-3">Guardar</button>
  </form>
</section>

<!-- ===========================
  BLOQUES DE SWEETALERT (ENVUELTOS EN DOMCONTENTLOADED)
=========================== -->

<?php if (isset($_SESSION['msg_docente']) && $_SESSION['msg_docente'] === 'guardado'): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  Swal.fire({icon:'success', title:'¡Éxito!', text:'Docente registrado con éxito', timer:2500, showConfirmButton:false});
});
</script>
<?php unset($_SESSION['msg_docente']); endif; ?>

<?php if (isset($_SESSION['error_docente'])): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  mostrarForm('form-docente');
  Swal.fire({
    icon: 'error',
    title: '<?php echo ($_SESSION['error_docente'] === "docente_existente") ? "Cédula duplicada" : "Error"; ?>',
    text: '<?php echo ($_SESSION['error_docente'] === "docente_existente") ? "Ya existe un docente con esa cédula" : "Ocurrió un error al guardar el docente"; ?>'
  });
});
</script>
<?php unset($_SESSION['error_docente']); endif; ?>

<?php if (isset($_SESSION['msg_asignatura']) && $_SESSION['msg_asignatura'] === 'guardada'): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  Swal.fire({icon:'success', title:'¡Éxito!', text:'Asignatura registrada con éxito', timer:2500, showConfirmButton:false});
});
</script>
<?php unset($_SESSION['msg_asignatura']); endif; ?>

<?php if (isset($_SESSION['error_asignatura'])): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  mostrarForm('form-asignatura');
  Swal.fire({
    icon: '<?php echo ($_SESSION['error_asignatura'] === "codigo_existente") ? "error" : "warning"; ?>',
    title: '<?php echo ($_SESSION['error_asignatura'] === "codigo_existente") ? "Código duplicado" : "Relación existente"; ?>',
    text: '<?php echo ($_SESSION['error_asignatura'] === "codigo_existente") ? "Ya existe una asignatura con ese código" : "Ya ha sido asignada esta asignatura a ese docente"; ?>'
  });
});
</script>
<?php unset($_SESSION['error_asignatura']); endif; ?>

<?php if (isset($_SESSION['msg_horario']) && $_SESSION['msg_horario'] === 'guardado'): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  Swal.fire({icon:'success', title:'¡Éxito!', text:'Horario registrado con éxito', timer:2500, showConfirmButton:false});
});
</script>
<?php unset($_SESSION['msg_horario']); endif; ?>

<?php if (isset($_SESSION['error_horario'])): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  mostrarForm('form-horario');
  if('<?php echo $_SESSION['error_horario']; ?>' === 'asignatura_inexistente'){
    Swal.fire({icon:'error', title:'Asignatura inexistente', text:'No se encontró la asignatura seleccionada'});
  } else if('<?php echo $_SESSION['error_horario']; ?>' === 'duplicado'){
    Swal.fire({icon:'warning', title:'Horario duplicado', text:'Ya existe un horario registrado con estos datos'});
  }
});
</script>
<?php unset($_SESSION['error_horario']); endif; ?>

</body>
</html>
