<?php
session_start();
require("conexion.php");
$con = conectar_bd();

$nombre = trim($_POST['nombre']);
$codigo = trim($_POST['codigo']);
$docentes = $_POST['docentes'] ?? [];

if (empty($nombre) || empty($codigo) || empty($docentes)) {
    $_SESSION['error_asignatura'] = 'faltan_datos';
    header("Location: indexadministrativo.php");
    exit();
}

try {
    // 1. Insertar asignatura
    $sql = "INSERT INTO asignatura (nombre, codigo) VALUES (?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $nombre, $codigo);
    $stmt->execute();
    $id_asignatura = $con->insert_id;

    // 2. Insertar docentes en la tabla relacional
    $sql_rel = "INSERT INTO docente_asignatura (cedula_docente, id_asignatura) VALUES (?, ?)";
    $stmt_rel = $con->prepare($sql_rel);

    foreach ($docentes as $cedula) {
        $cedula = (int)$cedula; // asegurarse de que sea entero

        // Verificar si ya existe la relación
        $sql_exist = "SELECT COUNT(*) FROM docente_asignatura WHERE cedula_docente = ? AND id_asignatura = ?";
        $stmt_exist = $con->prepare($sql_exist);
        $stmt_exist->bind_param("ii", $cedula, $id_asignatura);
        $stmt_exist->execute();
        $stmt_exist->bind_result($count);
        $stmt_exist->fetch();
        $stmt_exist->close();

        if ($count > 0) {
            continue; // saltar si ya existe
        }

        // Verificar que sea docente
        $sql_check = "SELECT rol FROM usuario WHERE cedula = ?";
        $stmt_check = $con->prepare($sql_check);
        $stmt_check->bind_param("i", $cedula);
        $stmt_check->execute();
        $stmt_check->bind_result($rol);
        $stmt_check->fetch();
        $stmt_check->close();

        if ($rol === 'docente') {
            $stmt_rel->bind_param("ii", $cedula, $id_asignatura);
            $stmt_rel->execute();
        }
    }

    $_SESSION['msg_asignatura'] = 'guardada';
    header("Location: indexadministrativo.php");
    exit();

} catch (mysqli_sql_exception $e) {
    if ($e->getCode() == 1062) {
        $_SESSION['error_asignatura'] = 'codigo_existente';
    } else {
        $_SESSION['error_asignatura'] = 'error_generico';
    }
    header("Location: indexadministrativo.php");
    exit();
}
?>
