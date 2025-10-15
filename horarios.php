<?php
require("seguridad.php");
require("conexion.php");
$con = conectar_bd();

// Obtener id_clase del estudiante
$cedula = $_SESSION["cedula"];
$alumno = mysqli_query($con, "SELECT id_clase FROM alumnos WHERE cedula='$cedula'");
$alumno = mysqli_fetch_assoc($alumno);
$id_clase = $alumno['id_clase'];

// Traer horarios
$horarios = mysqli_query($con, "SELECT * FROM horarios WHERE id_clase='$id_clase' ORDER BY FIELD(dia,'Lunes','Martes','Miércoles','Jueves','Viernes'), hora_inicio");

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Horario</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
<h2 class="text-center mb-4">Tu Horario</h2>
<table class="table table-bordered text-center">
<thead class="table-dark">
<tr>
<th>Día</th>
<th>Hora Inicio</th>
<th>Hora Fin</th>
<th>Materia</th>
<th>Aula</th>
</tr>
</thead>
<tbody>
<?php while($h = mysqli_fetch_assoc($horarios)): ?>
<tr>
<td><?= $h['dia'] ?></td>
<td><?= substr($h['hora_inicio'],0,5) ?></td>
<td><?= substr($h['hora_fin'],0,5) ?></td>
<td><?= $h['materia'] ?></td>
<td><?= $h['aula'] ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
<a href="indexestudiante.php" class="btn btn-primary">Volver</a>
</div>
</body>
</html>

