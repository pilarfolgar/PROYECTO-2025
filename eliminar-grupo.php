<?php
session_start();
require_once("conexion.php");

$con = conectar_bd();
if (!$con) {
    $_SESSION['error_grupo'] = "Error de conexión con la base de datos.";
    header("Location: indexadministrativoDatos.php");
    exit;
}

// Validar ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error_grupo'] = "ID de grupo no válido.";
    header("Location: indexadministrativoDatos.php");
    exit;
}

$id = (int) $_GET['id'];

// Eliminar relaciones con otras tablas
$stmt_rel1 = $con->prepare("DELETE FROM grupo_asignatura WHERE id_grupo = ?");
$stmt_rel2 = $con->prepare("DELETE FROM grupo_horario WHERE id_grupo = ?");

if ($stmt_rel1 && $stmt_rel2) {
    $stmt_rel1->bind_param("i", $id);
    $stmt_rel1->execute();
    $stmt_rel1->close();

    $stmt_rel2->bind_param("i", $id);
    $stmt_rel2->execute();
    $stmt_rel2->close();
} else {
    $_SESSION['error_grupo'] = "Error al preparar eliminación de relaciones: " . $con->error;
    header("Location: indexadministrativoDatos.php");
    exit;
}

// Eliminar el grupo
$stmt = $con->prepare("DELETE FROM grupo WHERE id_grupo = ?");
if (!$stmt) {
    $_SESSION['error_grupo'] = "Error en la consulta: " . $con->error;
    header("Location: indexadministrativoDatos.php");
    exit;
}

$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        $_SESSION['msg_grupo'] = "Grupo eliminado con éxito ✅";
    } else {
        $_SESSION['error_grupo'] = "No se encontró el grupo o ya fue eliminado.";
    }
} else {
    $_SESSION['error_grupo'] = "Error al eliminar el grupo: " . $stmt->error;
}

$stmt->close();
$con->close();

// Redirigir al panel
header("Location: indexadministrativoDatos.php");
exit;
?>
