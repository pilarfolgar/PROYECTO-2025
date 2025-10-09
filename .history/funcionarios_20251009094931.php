<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// Consultar los docentes desde la base de datos
// (suponiendo que están en la tabla 'usuario' con rol = 'docente')
$sql = "SELECT nombrecompleto, apellido, email, telefono, foto, asignatura FROM usuario WHERE rol = 'docente' ORDER BY nombrecompleto";
$result = $con->query($sql);
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

  <main class="container">
    <div class="docentes-grid" tabindex="0" aria-label="Lista de docentes">
      <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
          <div class="docente-card" tabindex="0">
            <img class="docente-photo"
                 src="<?= !empty($row['foto']) ? htmlspecialchars($row['foto']) : 'imagenes/LOGO.jpeg' ?>"
                 alt="Foto de <?= htmlspecialchars($row['nombrecompleto'] . ' ' . $row['apellido']) ?>" />
            <div class="docente-name"><?= htmlspecialchars($row['nombrecompleto'] . ' ' . $row['apellido']) ?></div>
            <div class="docente-subject"><?= htmlspecialchars($row['asignatura'] ?? 'Sin asignatura') ?></div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No hay docentes registrados aún.</p>
      <?php endif; ?>
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
