<?php
require("conexion.php");
$con = conectar_bd();

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "DELETE FROM horarios WHERE id_horario = $id";
    if ($con->query($sql)) {
        header("Location: indexadministrativo_datos.php?msg=Horario eliminado correctamente");
    } else {
        echo "Error al eliminar horario: " . $con->error;
    }
} else {
    echo "ID de horario no especificado.";
}
?>
