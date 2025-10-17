<?php
session_start();
require("conexion.php");
$con = conectar_bd();

if (isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];
    $sql = "DELETE FROM aula WHERE codigo = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $codigo);
    $stmt->execute();

    $_SESSION['msg_aula'] = "Aula eliminada correctamente 🗑️";
}

header("Location: indexadministrativoDatos.php");
exit();
?>

