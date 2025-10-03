<?php
session_start();
require("conexion.php");
$con = conectar_bd();

$nombre = $_POST['nombre'];
$capacidad = $_POST['capacidad'];
$ubicacion = $_POST['ubicacion'];
$tipo = $_POST['tipo'];

try {
    $sql = "INSERT INTO espacio (nombre, capacidad, ubicacion, tipo) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("siss", $nombre, $capacidad, $ubicacion, $tipo);
    $stmt->execute();

    $_SESSION['msg'] = "espacio_guardado";
    header("Location: indexadministrativo.php");
    exit();

} catch (mysqli_sql_exception $e) {
    if ($e->getCode() == 1062) {
        $_SESSION['error'] = "espacio_duplicado";
        header("Location: indexadministrativo.php");
        exit();
    } else {
        throw $e;
    }
}
?>
