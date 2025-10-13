<?php
require("conexion.php");

$con = conectar_bd();

// Cambia esta cédula por una de prueba
$cedula = 12345678;  
// Cambia esta contraseña por la que quieres probar
$pass_ingresada = 'TuContraseña123';

$stmt = mysqli_prepare($con, "SELECT pass FROM usuario WHERE cedula=?");
mysqli_stmt_bind_param($stmt, "i", $cedula);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if ($fila = mysqli_fetch_assoc($resultado)) {
    $hash_bd = trim($fila['pass']); // eliminar espacios

    echo "Hash en BD: $hash_bd<br>";
    echo "Contraseña ingresada: $pass_ingresada<br>";

    if (password_verify($pass_ingresada, $hash_bd)) {
        echo "<strong>✅ Coinciden: la contraseña es correcta</strong>";
    } else {
        echo "<strong>❌ No coinciden: la contraseña es incorrecta</strong>";
    }
} else {
    echo "Usuario no encontrado.";
}
?>
