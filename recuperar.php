<?php
require("conexion.php");
$con = conectar_bd();
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    // Verificar si el usuario existe
    $stmt = $con->prepare("SELECT email FROM usuario WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $token = bin2hex(random_bytes(16));
        $expira = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Guardar token en password_resets
        $stmt = $con->prepare("INSERT INTO password_resets (email, token, expira) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $token, $expira);
        $stmt->execute();

        // Aquí deberías enviar el email con el link (simulado)
        $link = "http://tu-sitio.com/restablecer.php?token=$token";
        $mensaje = "✅ Link de recuperación generado: <a href='$link'>Restablecer contraseña</a>";
    } else {
        $mensaje = "❌ Este correo no está registrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Recuperar contraseña</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f5f5f5;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
.container {
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    width: 350px;
    text-align: center;
}
h2 {
    margin-bottom: 20px;
    color: #333;
}
input[type="email"] {
    width: 100%;
    padding: 12px 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 16px;
}
button {
    width: 100%;
    padding: 12px;
    background: #007BFF;
    color: #fff;
    font-size: 16px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.3s;
}
button:hover { background: #0056b3; }
.mensaje { margin-bottom: 15px; font-weight: bold; color: #d9534f; }
.mensaje.exito { color: #28a745; }
</style>
</head>
<body>
<div class="container">
    <h2>Recuperar contraseña</h2>
    <?php if($mensaje) {
        $clase = strpos($mensaje, '✅') !== false ? 'exito' : '';
        echo "<p class='mensaje $clase'>$mensaje</p>";
    } ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Tu correo" required>
        <button type="submit">Enviar link</button>
    </form>
</div>
</body>
</html>
