<?php
require("conexion.php");
$con = conectar_bd();

$id = $_GET['id'] ?? null;
if($id) {
    $sql = "DELETE FROM grupo WHERE id_grupo=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: indexadministrativoDatos.php");
exit;
?>
