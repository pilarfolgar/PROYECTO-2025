<?php
session_start();
require("conexion.php");
$con = conectar_bd();

$cedula_estudiante = $_SESSION['cedula'] ?? 0;

if(!$cedula_estudiante){
    echo "No se ha iniciado sesión.";
    exit();
}

// Obtenemos el grupo del estudiante
$sqlGrupo = "SELECT id_grupo FROM usuario WHERE cedula = ?";
$stmtG = $con->prepare($sqlGrupo);
$stmtG->bind_param("i", $cedula_estudiante);
$stmtG->execute();
$stmtG->bind_result($id_grupo);
$stmtG->fetch();
$stmtG->close();

// Marcar como leído
if(isset($_GET['marcar_visto']) && is_numeric($_GET['marcar_visto'])){
    $id_notificacion = intval($_GET['marcar_visto']);
    $sqlVisto = "UPDATE notificaciones SET visto_estudiante = 1 WHERE id = ? AND id_grupo = ?";
    $stmtV = $con->prepare($sqlVisto);
    $stmtV->bind_param("ii", $id_notificacion, $id_grupo);
    $stmtV->execute();
    $stmtV->close();
}

// Traer notificaciones del grupo del estudiante
$sql = "SELECT id, titulo, mensaje, fecha, visto_estudiante
        FROM notificaciones
        WHERE id_grupo = ?
        ORDER BY fecha DESC";

$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id_grupo);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($id_notificacion, $titulo, $mensaje, $fecha, $visto);
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

<?php while($stmt->fetch()): ?>
    <div class="notificacion <?php echo $visto ? 'visto' : 'nuevo'; ?>">
        <h3><?php echo htmlspecialchars($titulo); ?></h3>
        <p><?php echo nl2br(htmlspecialchars($mensaje)); ?></p>
        <p class="fecha"><?php echo $fecha; ?></p>
        <?php if(!$visto): ?>
            <a href="?marcar_visto=<?php echo $id_notificacion; ?>">Marcar como leído</a>
        <?php else: ?>
            <span>Leído</span>
        <?php endif; ?>
    </div>
<?php endwhile; ?>

<?php
$stmt->close();
$con->close();
?>
</body>
</html>
