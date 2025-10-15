<?php
require("conexion.php");
$con = conectar_bd();
session_start();

// Recoger datos del formulario
$codigo = $_POST['codigo'];
$capacidad = $_POST['capacidad'];
$ubicacion = $_POST['ubicacion'];
$tipo = $_POST['tipo'];
$imagenPath = null;

// Procesar imagen si se subió
if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0){
    $nombreImagen = time() . "_" . $_FILES['imagen']['name'];
    $rutaDestino = __DIR__ . "/imagenes/aulas/" . $nombreImagen;
    if(move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)){
        $imagenPath = "imagenes/aulas/" . $nombreImagen;
    }
}

// Insertar datos en aula
$sql = "INSERT INTO aula (codigo, capacidad, ubicacion, imagen, tipo) VALUES (?, ?, ?, ?, ?)";
$stmt = $con->prepare($sql);
$stmt->bind_param("sisss", $codigo, $capacidad, $ubicacion, $imagenPath, $tipo);

if($stmt->execute()){
    $id_aula = $con->insert_id; // ID del aula recién creada

    // === Procesar recursos ===
    $todosRecursos = [];

    // Recursos existentes seleccionados
    if(!empty($_POST['recursos_existentes'])){
        foreach($_POST['recursos_existentes'] as $nombreRecurso){
            // Buscar el id del recurso
            $stmtR = $con->prepare("SELECT id_recurso FROM recurso WHERE nombre = ?");
            $stmtR->bind_param("s", $nombreRecurso);
            $stmtR->execute();
            $stmtR->bind_result($id_recurso);
            if($stmtR->fetch()){
                $todosRecursos[] = $id_recurso;
            }
            $stmtR->close();
        }
    }

    // Recurso nuevo (si se escribió algo)
    if(!empty($_POST['recurso_nuevo'])){
        $nuevo = trim($_POST['recurso_nuevo']);
        if($nuevo != ''){
            // Insertar en recurso si no existe
            $stmtCheck = $con->prepare("SELECT id_recurso FROM recurso WHERE nombre = ?");
            $stmtCheck->bind_param("s", $nuevo);
            $stmtCheck->execute();
            $stmtCheck->bind_result($idNuevo);
            if(!$stmtCheck->fetch()){
                $stmtCheck->close();
                $stmtInsert = $con->prepare("INSERT INTO recurso (nombre) VALUES (?)");
                $stmtInsert->bind_param("s", $nuevo);
                $stmtInsert->execute();
                $idNuevo = $con->insert_id;
                $stmtInsert->close();
            } else {
                $stmtCheck->close();
            }
            $todosRecursos[] = $idNuevo;
        }
    }

    // Insertar relaciones en aula_recurso
    if(!empty($todosRecursos)){
        $stmtAR = $con->prepare("INSERT INTO aula_recurso (id_aula, id_recurso) VALUES (?, ?)");
        foreach($todosRecursos as $id_recurso){
            $stmtAR->bind_param("ii", $id_aula, $id_recurso);
            $stmtAR->execute();
        }
        $stmtAR->close();
    }

    $_SESSION['msg_aula'] = 'guardada';
    header("Location: aulas.php");
    exit;

} else {
    $_SESSION['error_aula'] = 'error';
    header("Location: aulas.php");
    exit;
}

$stmt->close();
$con->close();
?>
