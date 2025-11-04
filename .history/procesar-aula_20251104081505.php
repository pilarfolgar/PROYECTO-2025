<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// Recoger datos del formulario
$codigo = trim($_POST['codigo'] ?? '');
$capacidad = !empty($_POST['capacidad']) ? intval($_POST['capacidad']) : null;
$ubicacion = $_POST['ubicacion'] ?? null;
$tipo = $_POST['tipo'] ?? 'aula';

// Verificar que el código no exista ya
$sql_check = "SELECT id_aula FROM aula WHERE codigo = ?";
$stmt_check = $con->prepare($sql_check);
$stmt_check->bind_param("s", $codigo);
$stmt_check->execute();
$stmt_check->store_result();

if($stmt_check->num_rows > 0){
    $_SESSION['error_aula'] = "El código de aula '$codigo' ya existe.";
    $stmt_check->close();
    header("Location: aulas.php");
    exit;
}
$stmt_check->close();

// Procesar imagen
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
    $id_aula = $stmt->insert_id;
    $stmt->close();

    // Recursos seleccionados
    $recursos = $_POST['recursos_existentes'] ?? [];

    // Recurso nuevo
    $recurso_nuevo = trim($_POST['recurso_nuevo'] ?? '');
    if($recurso_nuevo !== ''){
        $stmt_rec = $con->prepare("INSERT INTO recurso (nombre) VALUES (?)");
        $stmt_rec->bind_param("s", $recurso_nuevo);
        if($stmt_rec->execute()){
            $nuevo_id = $stmt_rec->insert_id;
            if($nuevo_id){
                $recursos[] = $nuevo_id;
            }
        }
        $stmt_rec->close();
    }

    // Filtrar solo IDs válidos en la tabla recurso
    $recursos_validos = [];
    foreach($recursos as $id_recurso){
        $id_recurso = intval($id_recurso);
        $res = $con->query("SELECT id_recurso FROM recurso WHERE id_recurso = $id_recurso");
        if($res && $res->num_rows > 0){
            $recursos_validos[] = $id_recurso;
        }
    }

    // Insertar relaciones aula_recurso
    if(count($recursos_validos) > 0){
        $stmt_rel = $con->prepare("INSERT INTO aula_recurso (id_aula, id_recurso) VALUES (?, ?)");
        foreach($recursos_validos as $id_recurso){
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
