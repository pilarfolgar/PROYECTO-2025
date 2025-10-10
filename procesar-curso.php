<?php
session_start();
require("conexion.php");
$con = conectar_bd();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $codigo = trim($_POST['codigo']);
    $asignaturas = $_POST['asignaturas'] ?? [];

    // Validaciones básicas
    if (empty($nombre) || empty($codigo)) {
        $_SESSION['error_curso'] = 'Campos vacíos';
        header("Location: indexadministrativo.php");
        exit;
    }

    // Verificar que no exista un curso con el mismo código
    $stmt = $con->prepare("SELECT id_curso FROM curso WHERE codigo = ?");
    $stmt->bind_param("s", $codigo);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $_SESSION['error_curso'] = 'codigo_existente';
        $stmt->close();
        header("Location: indexadministrativo.php");
        exit;
    }
    $stmt->close();

    // Insertar curso
    $stmt = $con->prepare("INSERT INTO curso (nombre, codigo) VALUES (?, ?)");
    $stmt->bind_param("ss", $nombre, $codigo);
    if ($stmt->execute()) {
        $id_curso = $stmt->insert_id;
        $stmt->close();

        // Insertar relaciones curso-asignatura
        if (!empty($asignaturas)) {
            $stmt = $con->prepare("INSERT INTO curso_asignatura (id_curso, id_asignatura) VALUES (?, ?)");
            foreach ($asignaturas as $id_asig) {
                $id_asig = intval($id_asig);
                $stmt->bind_param("ii", $id_curso, $id_asig);
                $stmt->execute();
            }
            $stmt->close();
        }

        $_SESSION['msg_curso'] = 'guardado';
        header("Location: indexadministrativo.php");
        exit;
    } else {
        $_SESSION['error_curso'] = 'error_insert';
        header("Location: indexadministrativo.php");
        exit;
    }
} else {
    header("Location: indexadministrativo.php");
    exit;
}
?>
