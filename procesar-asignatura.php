<?php
session_start(); // ✅ siempre al inicio
require("conexion.php");
$con = conectar_bd();

$nombre = $_POST['nombre'];
$codigo = $_POST['codigo'];
$docentes = $_POST['docentes']; // array de cédulas seleccionadas

try {
    // 1. Insertar la asignatura
    $sql = "INSERT INTO asignatura (nombre, codigo) VALUES (?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $nombre, $codigo);
    $stmt->execute();
    $id_asignatura = $stmt->insert_id;

    // 2. Insertar docentes válidos y evitar duplicados
    foreach ($docentes as $cedula) {
        // Verificar relación existente
        $sql_exist = "SELECT COUNT(*) FROM docente_asignatura WHERE cedula_docente = ? AND id_asignatura = ?";
        $stmt_exist = $con->prepare($sql_exist);
        $stmt_exist->bind_param("si", $cedula, $id_asignatura);
        $stmt_exist->execute();
        $stmt_exist->bind_result($count);
        $stmt_exist->fetch();
        $stmt_exist->close();

        if ($count > 0) {
            $_SESSION['error_asignatura'] = 'relacion_duplicada';
            header("Location: indexadministrativo.php");
            exit();
        }

        // Validar rol
        $sql_check = "SELECT rol FROM usuario WHERE cedula = ?";
        $stmt_check = $con->prepare($sql_check);
        $stmt_check->bind_param("s", $cedula);
        $stmt_check->execute();
        $stmt_check->bind_result($rol);
        $stmt_check->fetch();
        $stmt_check->close();

        if ($rol === 'docente') {
            $sql_rel = "INSERT INTO docente_asignatura (cedula_docente, id_asignatura) VALUES (?, ?)";
            $stmt_rel = $con->prepare($sql_rel);
            $stmt_rel->bind_param("si", $cedula, $id_asignatura);
            $stmt_rel->execute();
        }
    }

    $_SESSION['msg_asignatura'] = 'guardada'; // éxito
    header("Location: indexadministrativo.php");
    exit();

} catch (mysqli_sql_exception $e) {
    if ($e->getCode() == 1062) { // código duplicado
        $_SESSION['error_asignatura'] = 'codigo_existente';
        header("Location: indexadministrativo.php");
        exit();
    } else {
        $_SESSION['error_asignatura'] = 'error_generico';
        header("Location: indexadministrativo.php");
        exit();
    }
}
?>
