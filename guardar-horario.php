<?php
session_start();
require("conexion.php");
$con = conectar_bd();

if (!isset($_SESSION["cedula"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_clase = mysqli_real_escape_string($con, $_POST['id_clase']);
    $dia = mysqli_real_escape_string($con, $_POST['dia']);
    $hora_inicio = mysqli_real_escape_string($con, $_POST['hora_inicio']);
    $hora_fin = mysqli_real_escape_string($con, $_POST['hora_fin']);
    $materia = mysqli_real_escape_string($con, $_POST['materia']);
    $aula = mysqli_real_escape_string($con, $_POST['aula']);

    $sql = "INSERT INTO horarios (id_clase, dia, hora_inicio, hora_fin, materia, aula)
            VALUES ('$id_clase', '$dia', '$hora_inicio', '$hora_fin', '$materia', '$aula')";

    if (mysqli_query($con, $sql)) {
        echo "<script>alert('✅ Horario guardado.'); window.location.href='indexadministrativo.php';</script>";
    } else {
        echo "Error: " . mysqli_error($con);
    }
} else {
    header("Location: indexadministrativo.php");
}
?>
