<?php
session_start(); // Inicia sesión (agrega lógica de auth si es necesario)
// Ejemplo opcional: if (!isset($_SESSION['usuario'])) { header("Location: registro.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <title>InfraLex - Docentes</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400&display=swap" rel="stylesheet"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="style.css" rel="stylesheet"/>
</head>
<body>
  <header>
    <div class="HeaderIzq">
      <h1>InfraLex</h1>
      <h6>Instituto Tecnológico Superior de Paysandú</h6>
    </div>
    <div class="header-right">
      <a href="index.php">
        <img src="imagenes/logopoyecto.png" alt="Logo InfraLex" class="logo" />
      </a>
    </div>
  </header>

  <nav>
    <a href="masinfo.php"><i class="fas fa-info-circle"></i> Más información</a>
    <a href="registro.php"><i class="fas fa-sign-in-alt"></i> Ingresar</a>
  </nav>

  <main class="container">  <!-- Clase para flex y padding -->
    <div class="docentes-grid" tabindex="0" aria-label="Lista de docentes">  <!-- Cambiado a grid vertical -->
      <div class="docente-card" tabindex="0">
        <img class="docente-photo" src="imagenes/LOGO.jpeg" alt="Foto de Pierina Colet" />
        <div class="docente-name">Pierina Colet</div>
        <div class="docente-subject">Matemáticas</div>
      </div>

      <div class="docente-card" tabindex="0">
        <img class="docente-photo" src="imagenes/LOGO.jpeg" alt="Foto de Nara Saralegui" />
        <div class="docente-name">Nara Saralegui</div>
        <div class="docente-subject">Física</div>
      </div>

      <div class="docente-card" tabindex="0">
        <img class="docente-photo" src="imagenes/LOGO.jpeg" alt="Foto de Ana Iruleguy" />
        <div class="docente-name">Ana Iruleguy</div>
        <div class="docente-subject">Programación</div>
      </div>

      <div class="docente-card" tabindex="0">
        <img class="docente-photo" src="imagenes/LOGO.jpeg" alt="Foto de Diego Martínez" />
        <div class="docente-name">Diego Martínez</div>
        <div class="docente-subject">Diseño Gráfico</div>
      </div>

      <div class="docente-card" tabindex="0">
        <img class="docente-photo" src="imagenes/LOGO.jpeg" alt="Foto de Catherine Bianchi" />
        <div class="docente-name">Catherine Bianchi</div>
        <div class="docente-subject">Ciberseguridad</div>
      </div>

      <div class="docente-card" tabindex="0">
        <img class="docente-photo" src="imagenes/LOGO.jpeg" alt="Foto de Bruno Rodríguez" />
        <div class="docente-name">Bruno Rodríguez</div>
        <div class="docente-subject">Redes</div>
      </div>

      <div class="docente-card" tabindex="0">
        <img class="docente-photo" src="imagenes/LOGO.jpeg" alt="Foto de Valentina Díaz" />
        <div class="docente-name">Valentina Díaz</div>
        <div class="docente-subject">Secretariado Bilingüe</div>
      </div>

      <div class="docente-card" tabindex="0">
        <img class="docente-photo" src="imagenes/LOGO.jpeg" alt="Foto de Eduardo Demichelis" />
        <div class="docente-name">Eduardo Demichelis</div>
        <div class="docente-subject">Laboratorio</div>
      </div>
    </div>
  </main>

  <footer class="footer">
    © 2025 Instituto Tecnológico Superior de Paysandú | Contacto:
    <a href="mailto:evolutionit2008@gmail.com">
      evolutionit2008@gmail.com
    </a>
  </footer>
</body>
</html>