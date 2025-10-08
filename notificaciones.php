<?php
session_start();
require 'conexion.php';
$cedula = $_SESSION['cedula'];

$stmt = $conn->prepare("
    SELECT n.id, n.mensaje, r.leido
    FROM notificaciones n
    JOIN recibe r ON n.id = r.id_notificacion
    WHERE r.cedula_usuario = ?
    ORDER BY n.fecha_creacion DESC
");
$stmt->execute([$cedula]);
$notificaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($notificaciones);
