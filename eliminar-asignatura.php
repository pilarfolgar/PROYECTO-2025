<?php
require("conexion.php");
$con = conectar_bd();

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "DELETE FROM asignaturas WHERE id_asignatura = $id";
    if ($con->query($sql)) {
        header("Location: indexadministrativo_datos.php?msg=Asignatura eliminada correctamente");
    } else {
        echo "Error al eliminar asignatura: " . $con->error;
    }
} else {
    echo "ID de asignatura no especificado.";
}
?>
