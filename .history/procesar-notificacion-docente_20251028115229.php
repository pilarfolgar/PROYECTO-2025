<?php
require("seguridad.php");
require("conexion.php");
$con = conectar_bd();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_grupo = intval($_POST['id_grupo']);
    $titulo = trim($_POST['titulo']);
    $mensaje = trim($_POST['mensaje']);
    $docente_cedula = $_SESSION['cedula'];

    if ($id_grupo && $titulo && $mensaje) {
        $sql = "INSERT INTO notificaciones (id_grupo, docente_cedula, titulo, mensaje, fecha, visto_estudiante, visto_adscripto, rol_emisor)
                VALUES (?, ?, ?, ?, NOW(), 0, 0, 'docente')";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("iiss", $id_grupo, $docente_cedula, $titulo, $mensaje);

        if ($stmt->execute()) {
            $_SESSION['msg_notificacion'] = true;
        } else {
            $_SESSION['error_notificacion'] = true;
        }
        $stmt->close();
    } else {
        $_SESSION['error_notificacion'] = true;
    }
}

header("Location: indexdocente.php");
exit;
?>
