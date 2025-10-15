<?php
session_start();
require("conexion.php");
$con = conectar_bd();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $mensaje = trim($_POST["mensaje"]);
    $cedula = $_SESSION["cedula"] ?? 0;

    if ($cedula && strlen($mensaje) >= 5) {
        $stmt = $con->prepare("INSERT INTO sugerencias (cedula, mensaje) VALUES (?, ?)");
        $stmt->bind_param("is", $cedula, $mensaje);
        if ($stmt->execute()) {
            echo "ok";
        } else {
            echo "error";
        }
        $stmt->close();
    } else {
        echo "error";
    }
}
$con->close();
?>
