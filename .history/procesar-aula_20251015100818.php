<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// Recoger datos del formulario
$codigo = $_POST['codigo'];
$capacidad = $_POST['capacidad'];
$ubicacion = $_POST['ubicacion'];
$tipo = $_POST['tipo']; // Aula / Salón / Lab
$imagenPath = null;

// Procesar imagen si se subió
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
    $id_aula = $stmt->insert_id; // ID del aula recién insertada
    $stmt->close();

    // -----------------------------
    // Manejar recursos
    // -----------------------------
    $recursosExistentes = $_POST['recursos_existentes'] ?? []; // array de ids de recursos seleccionados
    $recursoNuevo = trim($_POST['recurso_nuevo'] ?? '');

    $todosRecursos = [];

    // Si hay nuevo recurso, insertarlo en tabla recurso y obtener su id
    if($recursoNuevo !== ''){
        $sqlNuevo = "INSERT INTO recurso (nombre) VALUES (?)";
        $stmtNuevo = $con->prepare($sqlNuevo);
        $stmtNuevo->bind_param("s", $recursoNuevo);
        if($stmtNuevo->execute()){
            $idNuevo = $stmtNuevo->insert_id;
            $todosRecursos[] = $idNuevo;
        }
        $stmtNuevo->close();
    }

    // Recursos existentes seleccionados
    foreach($recursosExistentes as $id_rec){
        $todosRecursos[] = $id_rec;
    }

    // Insertar en aula_recurso
    if(!empty($todosRecursos)){
        $sqlAR = "INSERT INTO aula_recurso (id_aula, id_recurso) VALUES (?, ?)";
        $stmtAR = $con->prepare($sqlAR);
        foreach($todosRecursos as $id_rec){
            $stmtAR->bind_param("ii", $id_aula, $id_rec);
            $stmtAR->execute();
        }
        $stmtAR->close();
    }

    $_SESSION['msg_aula'] = 'guardada';
    $con->close();
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
