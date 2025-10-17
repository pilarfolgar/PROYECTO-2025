<?php
require("conexion.php");

$con = conectar_bd();

if (!isset($_SESSION['cedula'])) {
    die("Error: Usuario no identificado.");
}

// Obtener el id_grupo del estudiante
$cedula = $_SESSION['cedula'];
$stmt = $con->prepare("SELECT id_grupo FROM usuario WHERE cedula = ?");
$stmt->bind_param("s", $cedula);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $id_grupo = $row['id_grupo'];
} else {
    die("Error: No se encontró el grupo del estudiante.");
}
$stmt->close();

// Obtener los horarios del grupo
// Usamos JOIN con grupo-horario si deseas filtrar por esa relación
$sql = "SELECT h.dia, h.hora_inicio, h.hora_fin, h.clase, h.aula, a.nombre AS asignatura
        FROM horarios h
        INNER JOIN grupo_horario gh ON h.id_horario = gh.id_horario
        INNER JOIN asignatura a ON h.id_asignatura = a.id_asignatura
        WHERE gh.id_grupo = ?
        ORDER BY FIELD(dia,'lunes','martes','miercoles','jueves','viernes'), h.hora_inicio ASC";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id_grupo);
$stmt->execute();
$result = $stmt->get_result();
$horarios = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Horario del Grupo</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h2 class="text-center mb-4">Horario de tu Grupo</h2>
    <?php if(count($horarios) > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Día</th>
                        <th>Hora Inicio</th>
                        <th>Hora Fin</th>
                        <th>Clase</th>
                        <th>Aula</th>
                        <th>Asignatura</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($horarios as $h): ?>
                    <tr>
                        <td><?= htmlspecialchars($h['dia']) ?></td>
                        <td><?= htmlspecialchars($h['hora_inicio']) ?></td>
                        <td><?= htmlspecialchars($h['hora_fin']) ?></td>
                        <td><?= htmlspecialchars($h['clase']) ?></td>
                        <td><?= htmlspecialchars($h['aula']) ?></td>
                        <td><?= htmlspecialchars($h['asignatura']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-center">No hay horarios asignados para tu grupo aún.</p>
    <?php endif; ?>
</div>
</body>
</html>
