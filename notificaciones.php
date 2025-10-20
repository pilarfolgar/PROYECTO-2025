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
<link rel="stylesheet" href="notificaciones.css">
</head>
<body>

<?php include('header.php'); ?>

<main>
    <h2>Mis Notificaciones</h2>
    <div class="notificaciones-container">
        <?php while($stmt->fetch()): ?>
            <div class="notificacion <?php echo $visto ? 'visto' : 'nuevo'; ?>">
                <h3><?php echo htmlspecialchars($titulo); ?></h3>
                <p><?php echo nl2br(htmlspecialchars($mensaje)); ?></p>
                <p class="fecha"><?php echo $fecha; ?></p>
                <?php if(!$visto): ?>
                    <a href="?marcar_visto=<?php echo $id_notificacion; ?>" class="btn-marcar">Marcar como leído</a>
                <?php else: ?>
                    <span class="leido">Leído</span>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</main>

<?php
$stmt->close();
$con->close();
?>

<script>
    // Animación simple: revelar notificaciones al cargar
    document.addEventListener('DOMContentLoaded', () => {
        const notis = document.querySelectorAll('.notificacion');
        notis.forEach((n, i) => {
            setTimeout(() => n.classList.add('visible'), i * 100);
        });
    });
</script>

</body>
</html>

<?php require('footer.php'); ?>
