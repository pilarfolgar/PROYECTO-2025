<?php
require("seguridad.php");
require("conexion.php");
$con = conectar_bd();

// Cedula del usuario logueado
$cedula = $_SESSION['cedula'];

// Busco a qué grupo pertenece el usuario
$query_grupo = "SELECT id_grupo FROM usuario WHERE cedula = '$cedula'";
$res_grupo = mysqli_query($con, $query_grupo);
$grupo = mysqli_fetch_assoc($res_grupo)['id_grupo'] ?? null;

// Si el usuario tiene un grupo asignado, obtengo sus horarios
if ($grupo) {
    $query_horarios = "
        SELECT h.dia, h.hora_inicio, h.hora_fin, a.nombre_asignatura
        FROM horarios h
        INNER JOIN asignaturas a ON h.id_asignatura = a.id_asignatura
        WHERE h.id_grupo = '$grupo'
        ORDER BY FIELD(h.dia,'Lunes','Martes','Miércoles','Jueves','Viernes'), h.hora_inicio
    ";
    $result = mysqli_query($con, $query_horarios);
} else {
    $result = null;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi horario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <h2 class="mb-4">Mi horario</h2>

    <?php if ($result && mysqli_num_rows($result) > 0): ?>
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Día</th>
                    <th>Hora Inicio</th>
                    <th>Hora Fin</th>
                    <th>Asignatura</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['dia']; ?></td>
                        <td><?php echo $row['hora_inicio']; ?></td>
                        <td><?php echo $row['hora_fin']; ?></td>
                        <td><?php echo $row['nombre_asignatura']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay horarios cargados para tu grupo.</p>
    <?php endif; ?>
</body>
</html>

