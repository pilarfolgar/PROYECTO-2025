<?php
session_start();
require("conexion.php");
$con = conectar_bd();

$grupo_id = $_SESSION['grupo_id'] ?? 0; // suponer que cada estudiante tiene un grupo

// Traer horarios del grupo
$sql = "SELECT dia, hora_inicio, hora_fin, materia, aula FROM horarios WHERE grupo_id = ? ORDER BY dia, hora_inicio";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $grupo_id);
$stmt->execute();
$result = $stmt->get_result();
$horario = [];
while($row = $result->fetch_assoc()){
    $horario[$row['dia']][] = $row;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Horario del Grupo</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body>
<?php require("header.php"); ?>

<section class="container my-5">
  <h2 class="text-center mb-4">Horario de tu Grupo</h2>
  <div class="table-responsive">
    <table class="table table-striped table-bordered text-center">
      <thead class="table-primary">
        <tr>
          <th>Día</th>
          <th>Hora Inicio</th>
          <th>Hora Fin</th>
          <th>Materia</th>
          <th>Aula</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($horario as $dia => $clases): ?>
            <?php foreach($clases as $clase): ?>
            <tr>
                <td><?php echo htmlspecialchars($dia); ?></td>
                <td><?php echo htmlspecialchars($clase['hora_inicio']); ?></td>
                <td><?php echo htmlspecialchars($clase['hora_fin']); ?></td>
                <td><?php echo htmlspecialchars($clase['materia']); ?></td>
                <td><?php echo htmlspecialchars($clase['aula']); ?></td>
            </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>

<footer class="footer">
  &copy; <?php echo date("Y"); ?> Instituto Tecnológico Superior de Paysandú
</footer>
</body>
</html>
