<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// Recoger datos del formulario
$codigo = $_POST['codigo'] ?? '';
$capacidad = !empty($_POST['capacidad']) ? intval($_POST['capacidad']) : null;
$ubicacion = $_POST['ubicacion'] ?? null;
$tipo = $_POST['tipo'] ?? 'aula'; 

$imagenPath = null;
if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0){
    $nombreImagen = time() . "_" . basename($_FILES['imagen']['name']);
    $rutaDestino = __DIR__ . "/imagenes/aulas/" . $nombreImagen;
    if(move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)){
        $imagenPath = "imagenes/aulas/" . $nombreImagen;
    }
}

// Insertar aula
$sql = "INSERT INTO aula (codigo, capacidad, ubicacion, imagen, tipo) VALUES (?, ?, ?, ?, ?)";
$stmt = $con->prepare($sql);
if(!$stmt){
    $_SESSION['error_aula'] = $con->error;
    header("Location: aulas.php"); exit;
}
$stmt->bind_param("sisss", $codigo, $capacidad, $ubicacion, $imagenPath, $tipo);
if($stmt->execute()){
    $id_aula = $stmt->insert_id; // ID del aula recién insertada
    $stmt->close();

    // Recursos seleccionados
    $recursos = $_POST['recursos_existentes'] ?? [];

    // Recurso nuevo
    $recurso_nuevo = trim($_POST['recurso_nuevo'] ?? '');
    if($recurso_nuevo !== ''){
        // Insertar recurso nuevo en la tabla recurso
        $stmt_rec = $con->prepare("INSERT INTO recurso (nombre) VALUES (?)");
        $stmt_rec->bind_param("s", $recurso_nuevo);
        if($stmt_rec->execute()){
            $recursos[] = $stmt_rec->insert_id; // agregar el ID a los recursos del aula
        }
        $stmt_rec->close();
    }

    // Insertar relaciones aula_recurso
    if(count($recursos) > 0){
        $stmt_rel = $con->prepare("INSERT INTO aula_recurso (id_aula, id_recurso) VALUES (?, ?)");
        foreach($recursos as $id_recurso){
            $id_recurso = intval($id_recurso); // por seguridad
            $stmt_rel->bind_param("ii", $id_aula, $id_recurso);
            $stmt_rel->execute();
        }
        $stmt_rel->close();
    }

    $_SESSION['msg_aula'] = 'guardada';
} else {
    $_SESSION['error_aula'] = $stmt->error;
    $stmt->close();
}

$con->close();
header("Location: aulas.php");
exit;
?>
