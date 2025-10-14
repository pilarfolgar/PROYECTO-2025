<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// Verificar si el usuario está logueado
if (!isset($_SESSION["cedula"])) {
    header("Location: login.php");
    exit();
}

// Verificar si los datos vienen del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Escapar datos del formulario para evitar inyecciones SQL
    $cedula = $_SESSION["cedula"];
    $nombre = mysqli_real_escape_string($con, $_POST["nombre"]);
    $email = mysqli_real_escape_string($con, $_POST["email"]);
    $objeto = mysqli_real_escape_string($con, $_POST["objeto"]);
    $descripcion = mysqli_real_escape_string($con, $_POST["descripcion"]);
    $fecha = mysqli_real_escape_string($con, $_POST["fecha"]);

    // Validar que ningún campo esté vacío
    if (empty($nombre) || empty($email) || empty($objeto) || empty($descripcion) || empty($fecha)) {
        echo "<script>alert('Por favor complete todos los campos.'); window.history.back();</script>";
        exit();
    }

    // Insertar en la base de datos
    $sql = "INSERT INTO reportes (cedula, nombre, email, objeto, descripcion, fecha, creado_en)
            VALUES ('$cedula', '$nombre', '$email', '$objeto', '$descripcion', '$fecha', NOW())";

    if (mysqli_query($con, $sql)) {
        echo "<script>
                alert('✅ Reporte enviado correctamente.');
                window.location.href = 'indexestudiante.php';
              </script>";
    } else {
        echo "<script>
                alert('❌ Error al guardar el reporte: " . mysqli_error($con) . "');
                window.history.back();
              </script>";
    }

    mysqli_close($con);

} else {
    // Si alguien intenta acceder directamente sin enviar el formulario
    header("Location: indexestudiante.php");
    exit();
}
?>
