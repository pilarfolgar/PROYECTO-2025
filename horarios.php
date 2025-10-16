<?php
require("conexion.php");
$con = conectar_bd();

// Usamos directamente el id del grupo del estudiante
$id_grupo = $_SESSION['id_grupo'];

// Obtener el horario del grupo
$sql_horario = "SELECT dia, hora_inicio, hora_fin, asignatura, docente 
                FROM horarios 
                WHERE id_grupo = ? 
                ORDER BY FIELD(dia,'Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'), hora_inicio";
$stmt = $con->prepare($sql_horario);
$stmt->bind_param("i", $id_grupo); // "i" porque es un número
$stmt->execute();
$result_horario = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Horario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Horario de tu grupo</h2>
    <?php if ($result_horario->num_rows > 0): ?>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Día</th>
                    <th>Hora Inicio</th>
                    <th>Hora Fin</th>
                    <th>Asignatura</th>
                    <th>Docente</th>
                </tr>
            </thead>
            <tbody>
                <?php while($fila = $result_horario->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($fila['dia']); ?></td>
                        <td><?php echo htmlspecialchars($fila['hora_inicio']); ?></td>
                        <td><?php echo htmlspecialchars($fila['hora_fin']); ?></td>
                        <td><?php echo htmlspecialchars($fila['asignatura']); ?></td>
                        <td><?php echo htmlspecialchars($fila['docente']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay horarios cargados para tu grupo aún.</p>
    <?php endif; ?>
</div>
</body>
</html>
