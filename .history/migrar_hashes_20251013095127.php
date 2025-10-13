<?php
// Script de migración: Hashea contraseñas planas en BD (una sola ejecución)
// REQUIERE: conexion.php en la misma carpeta
require("conexion.php");

$con = conectar_bd();
echo "<h2>Migración de Hashes de Contraseñas</h2>";
echo "<p>Esto hasheará contraseñas en texto plano (cortas, <50 chars, sin formato de hash).</p>";
echo "<p><strong>¡HAZ BACKUP DE BD ANTES! (phpMyAdmin > Exportar)</strong></p>";
echo "<ul>";

// Consulta: Encuentra usuarios con pass probablemente plana
$sql = "SELECT cedula, pass FROM usuario WHERE LENGTH(pass) < 50 AND pass NOT REGEXP '^\$2[ayb]\$'";
$result = mysqli_query($con, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $cedula = $row['cedula'];
        $pass_plana = $row['pass'];
        
        // Genera nuevo hash
        $nuevo_hash = password_hash($pass_plana, PASSWORD_DEFAULT);
        
        // Actualiza BD con prepared statement (seguro)
        $update_sql = "UPDATE usuario SET pass = ? WHERE cedula = ?";
        $stmt = mysqli_prepare($con, $update_sql);
        mysqli_stmt_bind_param($stmt, "si", $nuevo_hash, $cedula);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "<li style='color: green;'>✅ Hasheado: Cédula $cedula (pass original: '$pass_plana' → ahora hasheada).</li>";
        } else {
            echo "<li style='color: red;'>❌ Error al hashear cédula $cedula: " . mysqli_error($con) . "</li>";
        }
        mysqli_stmt_close($stmt);
    }
} else {
    echo "<li style='color: blue;'>No hay usuarios con contraseñas planas para migrar (todos ya hasheados).</li>";
}

echo "</ul>";
echo "<hr><p><strong>Migración completada. Ahora prueba login. BORRA ESTE ARCHIVO POR SEGURIDAD.</strong></p>";

// Cierra conexión
mysqli_close($con);
?>