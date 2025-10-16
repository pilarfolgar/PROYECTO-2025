<?php 
require("seguridad.php");
require("conexion.php");
$con = conectar_bd();

$cedula_docente = $_SESSION['cedula'];
$nombre_docente = $_SESSION['nombrecompleto'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Panel Docente - InfraLex</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="styleindexdocente.css">
</head>
<body>
<?php require("header.php"); ?>

<section class="container mt-5">
  <h2 class="mb-3">Mis reservas</h2>
  <?php
  $sql_reservas = "SELECT aula, fecha, hora_inicio, hora_fin, grupo
                   FROM reserva 
                   WHERE nombre = '$cedula_docente'
                   ORDER BY fecha DESC, hora_inicio ASC";
  $result = $con->query($sql_reservas);

  if ($result && $result->num_rows > 0) {
      echo '<div class="table-responsive">';
      echo '<table class="table table-bordered text-center">';
      echo '<thead class="table-primary"><tr>
              <th>Aula</th>
              <th>Grupo</th>
              <th>Fecha</th>
              <th>Hora inicio</th>
              <th>Hora fin</th>
            </tr></thead><tbody>';
      while ($row = $result->fetch_assoc()) {
          echo '<tr>
                  <td>'.htmlspecialchars($row['aula']).'</td>
                  <td>'.htmlspecialchars($row['grupo']).'</td>
                  <td>'.htmlspecialchars($row['fecha']).'</td>
                  <td>'.htmlspecialchars($row['hora_inicio']).'</td>
                  <td>'.htmlspecialchars($row['hora_fin']).'</td>
                </tr>';
      }
      echo '</tbody></table></div>';
  } else {
      echo '<div class="alert alert-info text-center">No tenés reservas registradas aún.</div>';
  }
  ?>
</section>

<?php require("footer.php"); ?>
</body>
</html>
