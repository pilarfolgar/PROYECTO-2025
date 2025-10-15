<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// Recoger datos del formulario
$codigo = $_POST['codigo'] ?? '';
$capacidad = !empty($_POST['capacidad']) ? intval($_POST['capacidad']) : null;
$ubicacion = $_POST['ubicacion'] ?? null;
$tipo = !empty($_POST['tipo']) ? $_POST['tipo'] : 'aula'; // Valor por defecto

$imagenPath = null;

// Procesar imagen si se subió
if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0){
    $nombreImagen = time() . "_" . basename($_FILES['imagen']['name']);
    $rutaDestino = __DIR__ . "/imagenes/aulas/" . $nombreImagen; // ruta absoluta
    if(move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)){
        $imagenPath = "imagenes/aulas/" . $nombreImagen; // ruta relativa para BD
    }
}

// SQL para insertar datos en aula
$sql = "INSERT INTO aula (codigo, capacidad, ubicacion, imagen, tipo) VALUES (?, ?, ?, ?, ?)";
$stmt = $con->prepare($sql);

// Depuración en caso de error
if(!$stmt){
    $_SESSION['error_aula'] = "Error en prepare(): " . $con->error;
    header("Location: aulas.php");
    exit;
}

// bind_param: s = string, i = integer
$stmt->bind_param("sisss", $codigo, $capacidad, $ubicacion, $imagenPath, $tipo);

// Ejecutar
if($stmt->execute()){
    $_SESSION['msg_aula'] = 'guardada';
} else {
    $_SESSION['error_aula'] = "Error en execute(): " . $stmt->error;
}

// Cerrar conexiones
$stmt->close();
$con->close();

// Redirigir
header("Location: aulas.php");
exit;
?>
