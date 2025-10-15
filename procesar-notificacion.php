<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// 1️⃣ Recibir datos del formulario
$id_grupo = $_POST['id_grupo'] ?? 0;
$titulo   = trim($_POST['titulo'] ?? '');
$mensaje  = trim($_POST['mensaje'] ?? '');

// 2️⃣ Cédula del docente desde la sesión
$docente_cedula = isset($_SESSION['cedula']) ? intval($_SESSION['cedula']) : 0;

// 3️⃣ Validar datos
if($id_grupo && $titulo && $mensaje && $docente_cedula){

    $fecha = date("Y-m-d H:i:s");
    $visto_estudiante = 0;
    $visto_adscripto  = 0;

    // 4️⃣ Insertar en notificaciones
    $sql = "INSERT INTO notificaciones 
            (id_grupo, docente_cedula, titulo, mensaje, fecha, visto_estudiante, visto_adscripto)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $con->prepare($sql);
    if(!$stmt){
        $_SESSION['error_notificacion'] = "Error en prepare (notificaciones): ".$con->error;
        header("Location: indexadministrativo.php");
        exit();
    }

    $stmt->bind_param("isssiii", $id_grupo, $docente_cedula, $titulo, $mensaje, $fecha, $visto_estudiante, $visto_adscripto);

    if($stmt->execute()){
        $id_notificacion = $stmt->insert_id; // ID recién creado

        // 5️⃣ Insertar en 'recibe' para cada estudiante del grupo
        $sqlEstudiantes = "SELECT cedula FROM usuario WHERE id_grupo = ?";
        $stmtEst = $con->prepare($sqlEstudiantes);
        if(!$stmtEst){
            $_SESSION['error_notificacion'] = "Error en prepare (usuarios): ".$con->error;
            header("Location: indexadministrativo.php");
            exit();
        }

        $stmtEst->bind_param("i", $id_grupo);
        $stmtEst->execute();
        $resEst = $stmtEst->get_result();

        while($row = $resEst->fetch_assoc()){
            $cedula_estudiante = $row['cedula'];

            $sqlRecibe = "INSERT INTO Recibe (cedula_usuario, id_notificacion, visto) VALUES (?, ?, 0)";
            $stmtRecibe = $con->prepare($sqlRecibe);
            if($stmtRecibe){
                $stmtRecibe->bind_param("ii", $cedula_estudiante, $id_notificacion);
                $stmtRecibe->execute();
                $stmtRecibe->close();
            }
        }

        $stmtEst->close();
        $_SESSION['msg_notificacion'] = "Notificación enviada correctamente";
    } else {
        $_SESSION['error_notificacion'] = "Error al enviar notificación: ".$stmt->error;
    }

    $stmt->close();
} else {
    $_SESSION['error_notificacion'] = "Faltan datos obligatorios";
}

// Redirigir al panel administrativo
header("Location: indexadministrativo.php");
exit();
?>


