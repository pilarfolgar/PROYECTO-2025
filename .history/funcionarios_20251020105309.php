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
<?php require("HeaderIndex.php"); ?>


  <main class="container">
    <div class="docentes-grid" tabindex="0" aria-label="Lista de docentes">
      <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
          <div class="docente-card" tabindex="0">
            <img class="docente-photo"
                 src="<?= !empty($row['foto']) ? htmlspecialchars($row['foto']) : 'imagenes/LOGO.jpeg' ?>"
                 alt="Foto de <?= htmlspecialchars($row['nombrecompleto']) ?>" />
            <div class="docente-name"><?= htmlspecialchars($row['nombrecompleto']) ?></div>
            <div class="docente-subject"><?= htmlspecialchars($row['asignatura'] ?? 'Sin asignatura') ?></div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No hay docentes registrados aún.</p>
      <?php endif; ?>
    </div>
  </main>

<?php require("footer.php"); ?>
</body>
</html>
