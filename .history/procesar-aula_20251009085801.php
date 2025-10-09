<?php
require("conexion.php");
$con = conectar_bd();

// Recoger datos del formulario
$codigo = $_POST['codigo'];
$capacidad = $_POST['capacidad'];
$ubicacion = $_POST['ubicacion'];
$tipo = $_POST['tipo']; // Nuevo campo para aula/salon/lab
$imagenPath = null;

// Procesar imagen si se subió
if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0){
    $nombreImagen = time() . "_" . $_FILES['imagen']['name'];
    $rutaDestino = __DIR__ . "/imagenes/aulas/" . $nombreImagen; // Ruta absoluta
    if(move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)){
        $imagenPath = "imagenes/aulas/" . $nombreImagen; // Ruta relativa para BD
    }
}

// SQL insertar datos
$sql = "INSERT INTO aula (codigo, capacidad, ubicacion, imagen, tipo) VALUES (?, ?, ?, ?, ?)";
$stmt = $con->prepare($sql);
$stmt->bind_param("sisss", $codigo, $capacidad, $ubicacion, $imagenPath, $tipo);

// Ejecutar y redirigir con mensaje
session_start();
if($stmt->execute()){
    $_SESSION['msg_aula'] = 'guardada';
    header("Location: aulas.php");
} else {
    $_SESSION['error_aula'] = 'error';
    header("Location: aulas.php");
}

$stmt->close();
$con->close();
?>
