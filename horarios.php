<?php
require("conexion.php");

$con = conectar_bd();
$id_grupo = $_SESSION['id_grupo']; // ID del grupo del estudiante desde la sesión

// Consultamos los horarios del grupo
$sql = "SELECT h.id_horario, a.nombre AS asignatura, h.dia, h.hora_inicio, h.hora_fin
        FROM horarios h
        INNER JOIN grupo_horario gh ON h.id_horario = gh.id_horario
        INNER JOIN asignatura a ON h.id_asignatura = a.id_asignatura
        WHERE gh.id_grupo = ?
        ORDER BY FIELD(h.dia, 'Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'), h.hora_inicio";

$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id_grupo);
$stmt->execute();
$result = $stmt->get_result();
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

<main class="container my-5">
    <h2 class="text-center mb-4">Horario de tu grupo</h2>

    <?php if($result->num_rows > 0): ?>
    <table class="table table-bordered table-striped mx-auto" style="max-width:800px;">
        <thead class="table-dark">
            <tr>
                <th>Asignatura</th>
                <th>Día</th>
                <th>Hora Inicio</th>
                <th>Hora Fin</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['asignatura']) ?></td>
                <td><?= htmlspecialchars($row['dia']) ?></td>
                <td><?= htmlspecialchars($row['hora_inicio']) ?></td>
                <td><?= htmlspecialchars($row['hora_fin']) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <div class="alert alert-info text-center">No hay horarios cargados para tu grupo.</div>
    <?php endif; ?>
</main>

<?php
$stmt->close();
$con->close();
require("footer.php");
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

