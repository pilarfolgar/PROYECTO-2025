<?php
session_start();

// Si no hay sesión activa, redirige al login
if (!isset($_SESSION['cedula']) || $_SESSION['rol'] !== 'estudiante') {
    header("Location: iniciosesion.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Panel Estudiantes - InfraLex</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>

<header>
  <div class="HeaderIzq">
    <h1>InfraLex</h1>
    <h6>Instituto Tecnológico Superior de Paysandú</h6>
  </div>
  <div class="header-right">
    <a href="indexEstudiantes.php"><img src="imagenes/LOGO.jpeg" alt="Logo" class="logo"></a>
  </div>
</header>

<nav>
  <a href="horarios.php">Horarios de clase</a>
  <a href="cerrar_sesion.php">Cerrar sesión</a>
</nav>

<main class="container my-5">
  <h2 class="text-center mb-4">Bienvenido/a, Estudiante</h2>

  <!-- FORMULARIO que carga la lógica desde otro archivo -->
  <form action="panel_estudiantes_logic.php" method="post">
    <input type="hidden" name="cedula" value="<?php echo $_SESSION['cedula']; ?>">
    <button type="submit" class="btn btn-primary d-block mx-auto mb-4">
      Cargar información 📚
    </button>
  </form>

  <?php if (isset($_SESSION['horarios']) && isset($_SESSION['notificaciones']) && isset($_SESSION['avisos'])): ?>
  <div class="row">
    <!-- HORARIOS -->
    <div class="col-md-6 mb-4">
      <div class="p-3 border rounded bg-light shadow-sm">
        <h4 class="text-center mb-3">Calendario de clases</h4>
        <ul class="list-group">
          <?php foreach($_SESSION['horarios'] as $h): ?>
            <li class="list-group-item">
              📅 <?php echo $h['dia_semana']; ?> - <?php echo $h['asignatura']; ?> 
              - <?php echo $h['aula']; ?> 
              (<?php echo substr($h['hora_inicio'],0,5) . " - " . substr($h['hora_fin'],0,5); ?>)
            </li>
          <?php endforeach; ?>
          <?php if(count($_SESSION['horarios']) == 0): ?>
            <li class="list-group-item text-center">No hay clases asignadas.</li>
          <?php endif; ?>
        </ul>
      </div>
    </div>

    <!-- NOTIFICACIONES Y AVISOS -->
    <div class="col-md-6 mb-4">
      <div class="p-3 border rounded bg-white shadow-sm">
        <h4 class="text-center mb-3">Notificaciones y avisos</h4>
        <ul class="list-group">
          <?php foreach($_SESSION['notificaciones'] as $n): ?>
            <li class="list-group-item">
              <strong><?php echo $n['titulo']; ?></strong><br>
              <?php echo $n['mensaje']; ?><br>
              <small class="text-muted"><?php echo date("d/m/Y H:i", strtotime($n['fecha'])); ?></small>
            </li>
          <?php endforeach; ?>

          <?php foreach($_SESSION['avisos'] as $a): ?>
            <li class="list-group-item list-group-item-warning">
              <strong><?php echo $a['titulo']; ?></strong><br>
              <?php echo $a['mensaje']; ?><br>
              <small class="text-muted"><?php echo date("d/m/Y H:i", strtotime($a['fecha'])); ?></small>
            </li>
          <?php endforeach; ?>

          <?php if(count($_SESSION['notificaciones']) == 0 && count($_SESSION['avisos']) == 0): ?>
            <li class="list-group-item text-center">No hay notificaciones ni avisos.</li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </div>
  <?php endif; ?>
</main>

<footer class="footer mt-5">
  &copy; 2025 Instituto Tecnológico Superior de Paysandú | Contacto: evolutionit2008@gmail.com
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script src="estudiantes.js"></script>
</body>
</html>
