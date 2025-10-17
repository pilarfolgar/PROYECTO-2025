<?php
require("conexion.php");
$con = conectar_bd();

if (isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];

    // Eliminamos relaciones en aula_recurso (si existen)
    $con->query("DELETE FROM aula_recurso WHERE id_aula = '$codigo'");

    // Eliminamos el aula
    $sql = "DELETE FROM aula WHERE codigo = '$codigo'";
    if ($con->query($sql)) {
        header("Location: indexadministrativo_datos.php?msg=Aula eliminada correctamente");
    } else {
        echo "Error al eliminar el aula: " . $con->error;
    }
} else {
    echo "Código de aula no especificado.";
}
?>
