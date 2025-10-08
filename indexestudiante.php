<?php
session_start();
include 'conexion.php';

// Suponemos que el estudiante ya inició sesión y su cédula está en $_SESSION['cedula']
$cedula = $_SESSION['cedula'];
$nombre = $_SESSION['nombre'];
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
  <a href="materiales.php">Material de estudio</a>
  <a href="horarios.php">Horarios de clase</a>
  <a href="index.php">Cerrar sesión</a>
</nav>

<main class="container my-5">
  <h2 class="text-center mb-4">Bienvenido/a, <?php echo htmlspecialchars($nombre); ?></h2>

  <!-- Notificaciones -->
  <div class="mb-4">
    <h4>📢 Notificaciones recientes</h4>
    <ul id="notificaciones" class="list-group"></ul>
  </div>

  <!-- Chat/foro -->
  <div class="mb-4">
    <h4>💬 Foro de mensajes</h4>
    <div id="chatBox" class="border rounded p-3 mb-2" style="height:200px; overflow-y:scroll;"></div>
    <div class="input-group">
      <input type="text" id="mensajeInput" class="form-control" placeholder="Escribe un mensaje...">
      <button id="enviarMensaje" class="btn btn-primary">Enviar</button>
    </div>
  </div>
</main>

<!-- Botón flotante para reporte -->
<button id="btnAbrirReporte" class="btn-flotante">📝 Reportar Objeto Dañado</button>

<!-- Overlay -->
<div id="overlayReporte" class="formulario-overlay"></div>

<!-- Formulario flotante -->
<section id="form-reporte" class="formulario">
  <button type="button" class="cerrar" id="btnCerrarReporte">✖</button>
  <form id="reporteForm" action="#" method="POST" class="needs-validation form-reserva-style" novalidate>
    <h2 class="form-title">Reportar Objeto Dañado</h2>
    <input type="text" class="form-control mb-3" id="nombreReporte" name="nombre" placeholder="Nombre" required>
    <input type="email" class="form-control mb-3" id="emailReporte" name="email" placeholder="Email" required>
    <input type="text" class="form-control mb-3" id="objetoReporte" name="objeto" placeholder="Objeto o área" required>
    <textarea class="form-control mb-3" id="descripcionReporte" name="descripcion" rows="3" placeholder="Descripción" required></textarea>
    <input type="date" class="form-control mb-3" id="fechaReporte" name="fecha" required>
    <button type="submit" class="btn btn-primary w-100">Enviar Reporte</button>
    <div id="mensajeReporte" class="mt-3 text-center"></div>
  </form>
</section>

<footer class="footer mt-5">
  &copy; 2025 Instituto Tecnológico Superior de Paysandú | Contacto: evolutionit2008@gmail.com
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script src="estudiantes.js"></script>
</body>
</html>
