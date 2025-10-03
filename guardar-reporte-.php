<?php
require("conexion.php"); // tu archivo de conexión

$con = conectar_bd();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"];
    $email = $_POST["email"];
    $objeto = $_POST["objeto"];
    $descripcion = $_POST["descripcion"];
    $fecha = $_POST["fecha"];

    $stmt = $con->prepare("INSERT INTO reportes (nombre, email, objeto, descripcion, fecha) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nombre, $email, $objeto, $descripcion, $fecha);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
}
?>