<?php
require("conexion.php"); // tu archivo de conexión
$con = conectar_bd();    // función que conecta a la DB

// Datos del usuario administrativo
$cedula = 28109331;
$nombrecompleto = "Admin Principal";
$apellido = "ApellidoAdmin";
$email = "admin@tusitio.com";
$rol = "administrativo";
$telefono = "000000000";
$asignatura = null;
$id_grupo = null;
$foto = null;

// Contraseña en texto plano
$password = "holacarola";

// Generar hash seguro
$hash = password_hash($password, PASSWORD_DEFAULT);

// Preparar INSERT
$stmt = $con->prepare("INSERT INTO usuario (cedula, nombrecompleto, pass, apellido, email, rol, telefono, foto, asignatura, id_grupo)
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("isssssssss", $cedula, $nombrecompleto, $hash, $apellido, $email, $rol, $telefono, $foto, $asignatura, $id_grupo);

if($stmt->execute()){
    echo "Usuario administrativo creado correctamente.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$con->close();
?>
