<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// Recoger datos del formulario
$codigo = $_POST['codigo'];
$capacidad = $_POST['capacidad'];
$ubicacion = $_POST['ubicacion'];
$tipo = $_POST['tipo'];
$imagenPath = null;

// Procesar imagen
if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0){
    $nombreImagen = time() . "_" . $_FILES['imagen']['name'];
    $rutaDestino = __DIR__ . "/imagenes/aulas/" . $nombreImagen;
    if(move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)){
        $imagenPath = "imagenes/aulas/" . $nombreImagen;
    }
}

// Insertar aula
$sql = "INSERT INTO aula (codigo, capacidad, ubicacion, imagen, tipo) VALUES (?, ?, ?, ?, ?)";
$stmt = $con->prepare($sql);
$stmt->bind_param("sisss", $codigo, $capacidad, $ubicacion, $imagenPath, $tipo);

if($stmt->execute()){
    $id_aula = $stmt->insert_id;

    // Recursos
    $recursosExistentes = $_POST['recursos_existentes'] ?? [];
    $recursoNuevo = trim($_POST['recurso_nuevo'] ?? '');
    $todosRecursos = [];

    // Insertar recurso nuevo si existe
    if($recursoNuevo !== ''){
        $stmtNuevo = $con->prepare("INSERT INTO recurso (nombre) VALUES (?)");
        $stmtNuevo->bind_param("s", $recursoNuevo);
        if($stmtNuevo->execute()){
            $todosRecursos[] = $stmtNuevo->insert_id;
        }
        $stmtNuevo->close();
    }

    // Agregar recursos existentes
    foreach($recursosExistentes as $rec){
        $todosRecursos[] = $rec;
    }

    // Insertar relaciones aula-recurso
    if(!empty($todosRecursos)){
        $stmtAR = $con->prepare("INSERT INTO aula_recurso (id_aula, id_recurso) VALUES (?, ?)");
        foreach($todosRecursos as $rec_id){
            $stmtAR->bind_param("ii", $id_aula, $rec_id);
            $stmtAR->execute();
        }
        $stmtAR->close();
    }

    $stmt->close();
    $con->close();
    $_SESSION['msg_aula'] = 'guardada';
    header("Location: aulas.php");
    exit;

} else {
    $stmt->close();
    $con->close();
    $_SESSION['error_aula'] = 'error';
    header("Location: aulas.php");
    exit;
}
?>
