<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// 1️⃣ Cédula del estudiante desde sesión
$cedula_estudiante = $_SESSION['cedula'] ?? 0;

if(!$cedula_estudiante){
    echo "No se ha iniciado sesión.";
    exit();
}

// 2️⃣ Marcar como vistas las notificaciones nuevas
if(isset($_GET['marcar_visto']) && is_numeric($_GET['marcar_visto'])){
    $id_notificacion = intval($_GET['marcar_visto']);
    $sqlVisto = "UPDATE Recibe SET visto = 1 WHERE id_notificacion = ? AND cedula_usuario = ?";
    $stmtV = $con->prepare($sqlVisto);
    $stmtV->bind_param("ii", $id_notificacion, $cedula_estudiante);
    $stmtV->execute();
    $stmtV->close();
}

// 3️⃣ Obtener notificaciones del estudiante
$sql = "SELECT n.id_notificacion, n.titulo, n.mensaje, n.fecha, r.visto
        FROM notificaciones n
        INNER JOIN Recibe r ON n.id_notificacion = r.id_notificacion
        WHERE r.cedula_usuario = ?
        ORDER BY n.fecha DESC";

$stmt = $con->prepare($sql);
$stmt->bind_param("i", $cedula_estudiante);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Notificaciones</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .notificacion { border: 1px solid #ccc; padding: 10px; margin: 10px 0; border-radius: 5px; }
        .nuevo { background-color: #e8f4ff; }
        .visto { background-color: #f4f4f4; }
        .fecha { font-size: 0.8em; color: #666; }
    </style>
</head>
<body>
    <h2>Mis Notificaciones</h2>

    <?php while($row = $result->fetch_assoc()): ?>
        <div class="notificacion <?php echo $row['visto'] ? 'visto' : 'nuevo'; ?>">
            <h3><?php echo htmlspecialchars($row['titulo']); ?></h3>
            <p><?php echo nl2br(htmlspecialchars($row['mensaje'])); ?></p>
            <p class="fecha"><?php echo $row['fecha']; ?></p>
            <?php if(!$row['visto']): ?>
                <a href="?marcar_visto=<?php echo $row['id_notificacion']; ?>">Marcar como leído</a>
            <?php else: ?>
                <span>Leído</span>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>

</body>
</html>

<?php
$stmt->close();
$con->close();
?>



