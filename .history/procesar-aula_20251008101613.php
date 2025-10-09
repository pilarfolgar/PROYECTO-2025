<?php
require("conexion.php");
$con = conectar_bd();

$codigo = $_POST['codigo'];
$capacidad = $_POST['capacidad'];
$ubicacion = $_POST['ubicacion'];
$imagenPath = null;

if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0){
    $nombreImagen = time() . "_" . $_FILES['imagen']['name'];
    $rutaDestino = __DIR__ . "/imagenes/aulas/" . $nombreImagen; // Ruta absoluta
    if(move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)){
        $imagenPath = "imagenes/aulas/" . $nombreImagen; // Ruta relativa para guardar en BD
    }
}

$sql = "INSERT INTO aula (codigo, capacidad, ubicacion, imagen) VALUES (?, ?, ?, ?)";
$stmt = $con->prepare($sql);
$stmt->bind_param("siss", $codigo, $capacidad, $ubicacion, $imagenPath);
if($stmt->execute()){
    session_start();
    $_SESSION['msg_aula'] = 'guardada';
    header("Location: aulas.php");
} else {
    session_start();
    $_SESSION['error_aula'] = 'error';
    header("Location: aulas.php");
}
$stmt->close();
$con->close();
?>
