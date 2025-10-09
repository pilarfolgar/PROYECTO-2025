<?php
session_start();
include 'conexion.php';

$stmt = $conn->prepare("SELECT usuario, mensaje, fecha FROM chat ORDER BY fecha ASC");
$stmt->execute();
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
