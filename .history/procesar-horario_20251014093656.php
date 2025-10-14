<?php
session_start();
require("conexion.php");

$con = conectar_bd();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar que todos los campos requeridos estén completos
    if (
        !empty($_POST['id_asignatura']) &&
        !empty($_POST['dia']) &&
        !empty($_POST['hora_inicio']) &&
        !empty($_POST['hora_fin']) &&
        !empty($_POST['id_grupo']) &&
        !empty($_POST['clase']) &&
        !empty($_POST['aula'])
    ) {
        $id_asignatura = intval($_POST['id_asignatura']);
        $dia = trim($_POST['dia']);
        $hora_inicio = $_POST['hora_inicio'];
        $hora_fin = $_POST['hora_fin'];
        $id_grupo = intval($_POST['id_grupo']);
        $clase = trim($_POST['clase']);
        $aula = trim($_POST['aula']);

        // Verificar que la asignatura exista
        $sql_check_asig = "SELECT id_asignatura FROM asignatura WHERE id_asignatura = ?";
        $stmt_check = $con->prepare($sql_check_asig);
        $stmt_check->bind_param("i", $id_asignatura);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows == 0) {
            $_SESSION['error_horario'] = "asignatura_inexistente";
    header("Location: indexadministrativo.php");
            exit();
        }
        $stmt_check->close();

        // Verificar que el grupo exista
        $sql_check_grupo = "SELECT id_grupo FROM grupo WHERE id_grupo = ?";
        $stmt_check2 = $con->prepare($sql_check_grupo);
        $stmt_check2->bind_param("i", $id_grupo);
        $stmt_check2->execute();
        $stmt_check2->store_result();

        if ($stmt_check2->num_rows == 0) {
            $_SESSION['error_horario'] = "grupo_inexistente";
    header("Location: indexadministrativo.php");
            exit();
        }
        $stmt_check2->close();

        // Verificar duplicados: mismo grupo, asignatura, día, horario y aula
        $sql_check_dup = "SELECT * FROM horarios 
                          WHERE id_asignatura = ? 
                          AND id_grupo = ? 
                          AND dia = ? 
                          AND hora_inicio = ? 
                          AND hora_fin = ? 
                          AND clase = ? 
                          AND aula = ?";
        $stmt_dup = $con->prepare($sql_check_dup);
        $stmt_dup->bind_param("iisssss", $id_asignatura, $id_grupo, $dia, $hora_inicio, $hora_fin, $clase, $aula);
        $stmt_dup->execute();
        $result_dup = $stmt_dup->get_result();

        if ($result_dup->num_rows > 0) {
            $_SESSION['error_horario'] = "duplicado";
    header("Location: indexadministrativo.php");
            exit();
        }
        $stmt_dup->close();

        // Insertar el horario
        $sql_insert = "INSERT INTO horarios (id_asignatura, id_grupo, dia, hora_inicio, clase, hora_fin, aula) 
                       VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert = $con->prepare($sql_insert);
        $stmt_insert->bind_param("iisssss", $id_asignatura, $id_grupo, $dia, $hora_inicio, $clase, $hora_fin, $aula);

        if ($stmt_insert->execute()) {
            $_SESSION['msg_horario'] = "guardado";
        } else {
            $_SESSION['error_horario'] = "error_bd";
        }

        $stmt_insert->close();
    } else {
        $_SESSION['error_horario'] = "campos_vacios";
    }

    header("Location: indexadministrativo.php");
    exit();
}
?>
