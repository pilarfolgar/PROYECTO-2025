<?php 
session_start();
require("conexion.php");
$con = conectar_bd();

// Obtener cédula del estudiante
$cedula_estudiante = $_SESSION['cedula'] ?? 0;
if(!$cedula_estudiante){
    die("No se ha iniciado sesión.");
}

// Obtener grupo del estudiante
$sqlGrupo = "SELECT id_grupo FROM usuario WHERE cedula = ?";
$stmtG = $con->prepare($sqlGrupo);
$stmtG->bind_param("i", $cedula_estudiante);
$stmtG->execute();
$stmtG->bind_result($id_grupo);
$stmtG->fetch();
$stmtG->close();

// Marcar como visto (solo para notificaciones enviadas por docente)
if(isset($_GET['marcar_visto']) && is_numeric($_GET['marcar_visto'])){
    $id_notificacion = intval($_GET['marcar_visto']);
    $sqlVisto = "UPDATE notificaciones 
                 SET visto_estudiante = 1 
                 WHERE id = ? AND id_grupo = ? AND rol_emisor = 'docente'";
    $stmtV = $con->prepare($sqlVisto);
    $stmtV->bind_param("ii", $id_notificacion, $id_grupo);
    $stmtV->execute();
    $stmtV->close();
}

// Traer todas las notificaciones del grupo (docente o adscripto)
$sql = "SELECT id, titulo, mensaje, fecha, visto_estudiante, rol_emisor
        FROM notificaciones
        WHERE id_grupo = ?
        ORDER BY fecha DESC";

$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id_grupo);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($id_notificacion, $titulo, $mensaje, $fecha, $visto, $rol_emisor);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Mis Notificaciones</title>
<link rel="stylesheet" href="notificaciones.css">
</head>
<body>
<?php include('header.php'); ?>
<main>
    <h2>Mis Notificaciones</h2>
    <div class="notificaciones-container">
        <?php while($stmt->fetch()): ?>
            <div class="notificacion <?php echo $visto ? 'visto' : 'nuevo'; ?>">
                <h3><?= htmlspecialchars($titulo) ?></h3>
                <p><?= nl2br(htmlspecialchars($mensaje)) ?></p>
                <p class="fecha"><?= $fecha ?> - <em><?= ucfirst($rol_emisor) ?></em></p>
                
                <?php if(!$visto && $rol_emisor == 'docente'): ?>
                    <a href="?marcar_visto=<?= $id_notificacion ?>" class="btn-marcar">Marcar como leído</a>
                <?php elseif($visto || $rol_emisor == 'adscriptor'): ?>
                    <span class="leido">Leído</span>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</main>
<?php
$stmt->close();
$con->close();
include('footer.php');
?>
</body>
</html>
