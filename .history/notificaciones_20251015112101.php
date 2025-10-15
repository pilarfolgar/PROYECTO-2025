<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// 1️⃣ Verificar sesión y rol
$cedula_estudiante = $_SESSION['cedula'] ?? 0;
$rol = $_SESSION['rol'] ?? '';

if(!$cedula_estudiante || $rol !== 'estudiante'){
    echo "Acceso denegado.";
    exit();
}

// 2️⃣ Obtener el grupo del estudiante
$sqlGrupo = "SELECT id_grupo FROM usuario WHERE cedula = ?";
$stmtG = $con->prepare($sqlGrupo);
$stmtG->bind_param("i", $cedula_estudiante);
$stmtG->execute();
$stmtG->bind_result($id_grupo);
$stmtG->fetch();
$stmtG->close();

if(!$id_grupo){
    echo "No se encontró grupo asignado.";
    exit();
}

// 3️⃣ Marcar notificación como vista si se pasó parámetro
if(isset($_GET['marcar_visto']) && is_numeric($_GET['marcar_visto'])){
    $id_notificacion = intval($_GET['marcar_visto']);
    $sqlVisto = "UPDATE notificaciones SET visto_estudiante = 1 WHERE id = ? AND id_grupo = ?";
    $stmtV = $con->prepare($sqlVisto);
    $stmtV->bind_param("ii", $id_notificacion, $id_grupo);
    $stmtV->execute();
    $stmtV->close();
}

// 4️⃣ Obtener notificaciones del grupo
$sql = "SELECT id, titulo, mensaje, fecha, visto_estudiante 
        FROM notificaciones 
        WHERE id_grupo = ? 
        ORDER BY fecha DESC";

$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id_grupo);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($id_notificacion, $titulo, $mensaje, $fecha, $visto_estudiante);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Mis Notificaciones</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { font-family: Arial, sans-serif; padding: 20px; }
.notificacion { border: 1px solid #ccc; padding: 15px; margin-bottom: 10px; border-radius: 5px; }
.nuevo { background-color: #e8f4ff; }
.visto { background-color: #f4f4f4; }
.fecha { font-size: 0.8em; color: #666; }
.boton { text-decoration: none; padding: 5px 10px; border-radius: 4px; background-color: #007bff; color: white; }
.boton:hover { background-color: #0056b3; }
</style>
</head>
<body>

<h2>Mis Notificaciones</h2>

<?php 
$hay = false;
while($stmt->fetch()): 
    $hay = true;
?>
    <div class="notificacion <?php echo $visto_estudiante ? 'visto' : 'nuevo'; ?>">
        <h4><?php echo htmlspecialchars($titulo); ?></h4>
        <p><?php echo nl2br(htmlspecialchars($mensaje)); ?></p>
        <p class="fecha"><?php echo date("d/m/Y H:i", strtotime($fecha)); ?></p>
        <?php if(!$visto_estudiante): ?>
            <a href="?marcar_visto=<?php echo $id_notificacion; ?>" class="boton">Marcar como leído</a>
        <?php else: ?>
            <span>Leído</span>
        <?php endif; ?>
    </div>
<?php endwhile; 

if(!$hay){
    echo "<p>No hay notificaciones para tu grupo.</p>";
}

$stmt->close();
$con->close();
?>

</body>
</html>
