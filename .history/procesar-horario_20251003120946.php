<?php
session_start();
require("conexion.php");
$con = conectar_bd();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_asignatura = $_POST['id_asignatura']; // ahora viene el id directamente
    $dia           = $_POST['dia'];
    $hora_inicio   = $_POST['hora_inicio'];
    $hora_fin      = $_POST['hora_fin'];
    $clase         = $_POST['clase'];

    try {
        // 1. Verificar que exista la asignatura
        $sql_check = "SELECT id_asignatura FROM asignatura WHERE id_asignatura = ?";
        $stmt_check = $con->prepare($sql_check);
        $stmt_check->bind_param("i", $id_asignatura);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows === 0) {
            $_SESSION['error_horario'] = 'asignatura_inexistente';
            header("Location: indexadministrativo.php");
            exit();
        }
        $stmt_check->close();

        // 2. Insertar el horario
        $sql_insert = "INSERT INTO horarios (id_asignatura, dia, hora_inicio, hora_fin, clase) VALUES (?, ?, ?, ?, ?)";
        $stmt_insert = $con->prepare($sql_insert);
        $stmt_insert->bind_param("issss", $id_asignatura, $dia, $hora_inicio, $hora_fin, $clase);
        $stmt_insert->execute();

        $_SESSION['msg_horario'] = 'guardado';
        header("Location: indexadministrativo.php");
        exit();

    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) { // si hay duplicado
            $_SESSION['error_horario'] = 'duplicado';
            header("Location: indexadministrativo.php");
            exit();
        } else {
            throw $e;
        }
    }
}
?>
