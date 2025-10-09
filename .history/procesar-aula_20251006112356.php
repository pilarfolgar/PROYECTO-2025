<?php
session_start();
require("conexion.php");
$con = conectar_bd();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo    = trim($_POST["codigo"]);
    $capacidad = isset($_POST["capacidad"]) && $_POST["capacidad"] !== '' ? (int)$_POST["capacidad"] : null;
    $ubicacion = trim($_POST["ubicacion"]);

    // Validación básica
    if (empty($codigo)) {
        $_SESSION['error_aula'] = 'codigo_vacio';
        header("Location: indexadministrativo.php");
        exit();
    }

    // Consulta para insertar aula
    $sql = "INSERT INTO aula (codigo, capacidad, ubicacion) VALUES (?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sis", $codigo, $capacidad, $ubicacion);

    try {
        $stmt->execute();
        $_SESSION['msg_aula'] = 'guardada'; // ✅ Éxito
        header("Location: indexadministrativo.php");
        exit();
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            // ❌ Duplicado
            $_SESSION['error_aula'] = 'codigo_existente';
        } else {
            $_SESSION['error_aula'] = 'error_generico';
        }
        header("Location: indexadministrativo.php");
        exit();
    }
}
?>
