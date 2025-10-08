<?php
session_start();
include 'conexion.php';

$mensaje = $_POST['mensaje'];
$usuario = $_SESSION['nombre'];

$stmt = $conn->prepare("INSERT INTO chat (usuario, mensaje, fecha) VALUES (?, ?, NOW())");
$stmt->execute([$usuario, $mensaje]);
