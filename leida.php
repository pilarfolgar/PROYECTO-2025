<?php
session_start();
require 'conexion.php';
$cedula = $_SESSION['cedula'];
$id = $_POST['id'];

$stmt = $conn->prepare("UPDATE recibe SET leido=1 WHERE cedula_usuario=? AND id_notificacion=?");
$stmt->execute([$cedula, $id]);
