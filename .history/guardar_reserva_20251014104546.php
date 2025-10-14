<?php
require("conexion.php");
$con = conectar_bd();
header('Content-Type: application/json');
echo json_encode(['success'=>true]);