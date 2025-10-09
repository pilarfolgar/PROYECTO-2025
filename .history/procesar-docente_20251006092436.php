<?php
session_start();
require("conexion.php");
$con = conectar_bd();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre   = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $documento = $_POST["documento"]; // cédula
    $email    = $_POST["email"];
    $telefono = $_POST["telefono"];

    // Generamos una contraseña inicial
    $pass = password_hash($documento, PASSWORD_DEFAULT);
    $rol  = "docente";

    $sql = "INSERT INTO usuario (cedula, nombrecompleto, apellido, pass, email, rol, telefono) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("issssss", $documento, $nombre, $apellido, $pass, $email, $rol, $telefono);

    try {
        $stmt->execute();
        $_SESSION['msg_docente'] = 'guardado'; // ✅ Éxito
        header("Location: indexadministrativo.php");
        exit();
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            // ❌ Duplicado
            $_SESSION['error_docente'] = 'docente_existente';
            header("Location: indexadministrativo.php");
            exit();
        } else {
            $_SESSION['error_docente'] = 'error_generico';
            header("Location: indexadministrativo.php");
            exit();
        }
    }
}
?>
