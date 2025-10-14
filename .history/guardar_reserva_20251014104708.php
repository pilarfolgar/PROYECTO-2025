<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require("conexion.php");
$con = conectar_bd();

header('Content-Type: application/json');
