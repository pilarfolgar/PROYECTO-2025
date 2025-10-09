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
  <div class="tarjeta">
  <h3>Grupos</h3>
  <p>Crear y administrar grupos de estudiantes.</p>
  <a href="#" class="boton" onclick="mostrarForm('form-grupo')">➕ Agregar Grupo</a>
</div>
<div class="tarjeta">
  <h3>Usuarios</h3>
  <p>Aprobar o eliminar cuentas de docentes pendientes de verificación.</p>
  <a href="#" class="boton" onclick="mostrarForm('form-usuarios')">👤 Gestionar Usuarios</a>
</div>

</main>
<?php require("footer.php"); ?>

<!-- FORM DOCENTE -->
<section id="form-docente" class="formulario" style="display: none;">
  <button type="button" class="cerrar" onclick="cerrarForm('form-docente')" aria-label="Cerrar formulario">✖</button>
  <form action="procesar-docente.php" method="POST" enctype="multipart/form-data" class="needs-validation form-reserva-style novalidate">
    <h2 class="form-title">Registrar Docente</h2>
    <div class="row g-3">
      <div class="col-md-6">
        <label for="nombreDocente" class="form-label">Nombre</label>
        <input type="text" class="form-control" id="nombreDocente" name="nombre" required>
      </div>
      <div class="col-md-6">
        <label for="apellidoDocente" class="form-label">Apellido</label>
        <input type="text" class="form-control" id="apellidoDocente" name="apellido" required>
      </div>
      <div class="col-md-6">
        <label for="documentoDocente" class="form-label">Cédula</label>
        <input type="number" class="form-control" id="documentoDocente" name="documento" required>
      </div>
      <div class="col-md-6">
        <label for="emailDocente" class="form-label">Correo electrónico</label>
        <input type="email" class="form-control" id="emailDocente" name="email" required>
      </div>
      <div class="col-md-6">
        <label for="telefonoDocente" class="form-label">Teléfono</label>
        <input type="tel" class="form-control" id="telefonoDocente" name="telefono">
      </div>
      <div class="col-md-6">
        <label for="fotoDocente" class="form-label">Foto del docente</label>
        <input type="file" class="form-control" id="fotoDocente" name="foto" accept="image/*">
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
            <option value="">Si no aparece la asignatura deseada, recuerde ingresar primero la asignatura.</option>

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
  <label for="grupoHorario" class="form-label">Grupo</label>
  <select class="form-select" id="grupoHorario" name="id_grupo" required>
    <option value="">Seleccione grupo...</option>
    <?php
    // Obtener todos los grupos cargados
    $sql = "SELECT id_grupo, nombre, orientacion FROM grupo ORDER BY nombre";
    $result = $con->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . $row['id_grupo'] . '">' 
                 . $row['nombre'] . ' - ' . $row['orientacion'] 
                 . '</option>';
        }
    } else {
        echo '<option value="">No hay grupos registrados</option>';
    }
    ?>
  </select>
</div>

    </div>
    <button type="submit" class="boton mt-3">Guardar</button>
  </form>
</section>

<!-- FORM AULA -->
<section id="form-aula" class="formulario" style="display: none;">
  <button type="button" class="cerrar" onclick="cerrarForm('form-aula')" aria-label="Cerrar formulario">✖</button>
  <form action="procesar-aula.php" method="POST" enctype="multipart/form-data" class="needs-validation form-reserva-style novalidate">
    <h2 class="form-title">Registrar Aula</h2>
    <div class="row g-3">
      <!-- Código de aula -->
      <div class="col-md-6">
        <label for="codigoAula" class="form-label">Número o código de aula</label>
        <input type="text" class="form-control" id="codigoAula" name="codigo" required placeholder="Ej. Aula 101">
      </div>
      <!-- Capacidad -->
      <div class="col-md-6">
        <label for="capacidadAula" class="form-label">Capacidad</label>
        <input type="number" class="form-control" id="capacidadAula" name="capacidad" min="1" placeholder="Ej. 30" required>
      </div>
      <!-- Ubicación -->
      <div class="col-12">
        <label for="ubicacionAula" class="form-label">Ubicación</label>
        <input type="text" class="form-control" id="ubicacionAula" name="ubicacion" placeholder="Ej. Piso 2, Bloque A" required>
      </div>
      <!-- Tipo de espacio -->
      <div class="col-12">
        <label for="tipoAula" class="form-label">Tipo de espacio</label>
        <select class="form-select" id="tipoAula" name="tipo" required>
          <option value="" disabled selected>Seleccione tipo...</option>
          <option value="aula">Aula</option>
          <option value="salon">Salón</option>
          <option value="lab">Laboratorio</option>
        </select>
      </div>
      <!-- Imagen -->
      <div class="col-12">
        <label for="imagenAula" class="form-label">Imagen</label>
        <input type="file" class="form-control" id="imagenAula" name="imagen" accept="image/*">
      </div>
    </div>
    <button type="submit" class="boton mt-3">Guardar</button>
  </form>
</section>


<!-- FORM GRUPO -->
<section id="form-grupo" class="formulario" style="display: none;">
  <button type="button" class="cerrar" onclick="cerrarForm('form-grupo')" aria-label="Cerrar formulario">✖</button>
  <form action="procesar-grupo.php" method="POST" class="needs-validation form-reserva-style novalidate">
    <h2 class="form-title">Registrar Grupo</h2>
    <div class="row g-3">
      
      <div class="col-md-6">
        <label for="orientacionGrupo" class="form-label">Orientación</label>
        <select class="form-select" id="orientacionGrupo" name="orientacion" required>
          <option value="">Seleccione orientación...</option>
          <option value="Tec. de la Información">Tecnologías de la información</option>
          <option value="Tec. de la Información Bilingüe">Tecnologías de la información Bilingüe</option>
          <option value="Tecnología">Tecnólogo en Ciberseguridad</option>
          <!-- Agregás las demás orientaciones que tengas -->
        </select>
      </div>

      <div class="col-md-6">
        <label for="nombreGrupo" class="form-label">Nombre del grupo</label>
        <input type="text" class="form-control" id="nombreGrupo" name="nombre" required placeholder="Ej. 3°A">
      </div>

      <div class="col-md-6">
        <label for="cantidadEstudiantes" class="form-label">Cantidad de estudiantes</label>
        <input type="number" class="form-control" id="cantidadEstudiantes" name="cantidad" min="1" required placeholder="Ej. 30">
      </div>

      <div class="col-md-6">
        <label for="asignaturasGrupo" class="form-label">Asignaturas</label>
        <select class="form-select" id="asignaturasGrupo" name="asignaturas[]" multiple required>
          <?php
          $sql = "SELECT id_asignatura, nombre FROM asignatura ORDER BY nombre";
          $result = $con->query($sql);
          while ($row = $result->fetch_assoc()) {
              echo '<option value="' . $row['id_asignatura'] . '">' . $row['nombre'] . '</option>';
          }
          ?>
        </select>
      </div>
<div class="col-md-6">
    <label for="horarioGrupo" class="form-label">Horarios del grupo</label>
    <select class="form-select" id="horarioGrupo" name="horarios[]" multiple required>

        <?php
        // Si $id_grupo no existe (registro nuevo), usamos 0
        $id_grupo = $id_grupo ?? 0;

        $sql = "SELECT h.id_horario, CONCAT(h.dia, ' ', h.hora_inicio, '-', h.hora_fin) AS horario,
                      IF(gh.id_horario IS NOT NULL, 1, 0) AS seleccionado
                FROM horarios h
                LEFT JOIN grupo_horario gh ON h.id_horario = gh.id_horario AND gh.id_grupo = ?
                ORDER BY h.dia, h.hora_inicio";

        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id_grupo);
        $stmt->execute();
        $result = $stmt->get_result();

        while($row = $result->fetch_assoc()){
            $selected = $row['seleccionado'] ? 'selected' : '';
            echo '<option value="'.$row['id_horario'].'" '.$selected.'>'.$row['horario'].'</option>';
        }
        $stmt->close();
        ?>
    </select>
</div>


    </div>
    <button type="submit" class="boton mt-3">Guardar</button>
  </form>
</section>
<!-- FORM USUARIOS PENDIENTES -->
<section id="form-usuarios" class="formulario" style="display: none;">
  <button type="button" class="cerrar" onclick="cerrarForm('form-usuarios')" aria-label="Cerrar formulario">✖</button>
  <h2 class="form-title">Usuarios Pendientes</h2>
  <div class="table-responsive">
    <table class="table table-striped table-bordered align-middle">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Apellido</th>
          <th>Email</th>
          <th>Cédula</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php
        require("conexion.php");
        $con = conectar_bd();
        $sql = "SELECT * FROM usuario WHERE rol = 'docente' AND verificado = 0";
        $result = $con->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['nombre']}</td>
                        <td>{$row['apellido']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['cedula']}</td>
                        <td>
                          <form action='aprobar_usuario.php' method='POST' style='display:inline;'>
                            <input type='hidden' name='cedula' value='{$row['cedula']}'>
                            <button type='submit' class='btn btn-success btn-sm'>Aprobar</button>
                          </form>
                          <form action='eliminar_usuario.php' method='POST' style='display:inline;'>
                            <input type='hidden' name='cedula' value='{$row['cedula']}'>
                            <button type='submit' class='btn btn-danger btn-sm'>Eliminar</button>
                          </form>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5' class='text-center'>No hay usuarios pendientes</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
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
<?php if (isset($_SESSION['msg_aula']) && $_SESSION['msg_aula'] === 'guardada'): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  Swal.fire({icon:'success', title:'¡Éxito!', text:'Aula registrada con éxito', timer:2500, showConfirmButton:false});
});
</script>
<?php unset($_SESSION['msg_aula']); endif; ?>

<?php if (isset($_SESSION['error_aula'])): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  mostrarForm('form-aula');
  const error = '<?php echo $_SESSION['error_aula']; ?>';
  if (error === 'codigo_existente') {
    Swal.fire({icon:'error', title:'Código duplicado', text:'Ya existe un aula con ese código'});
  } else if (error === 'codigo_vacio') {
    Swal.fire({icon:'warning', title:'Campo vacío', text:'Debe ingresar un código de aula'});
  } else {
    Swal.fire({icon:'error', title:'Error', text:'Ocurrió un error al registrar el aula'});
  }
});
</script>
<?php unset($_SESSION['error_aula']); endif; ?>


</body>
</html>
