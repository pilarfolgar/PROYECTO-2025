<?php
require("conexion.php");
$con = conectar_bd();
$mensaje = "";
$mostrar_form = false;

if (isset($_GET["token"])) {
    $token = $_GET["token"];
    $stmt = $con->prepare("SELECT email, expira FROM password_resets WHERE token=?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        if (strtotime($row["expira"]) > time()) {
            $mostrar_form = true;
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
                $email = $row["email"];

                $stmt = $con->prepare("UPDATE usuario SET password=? WHERE email=?");
                $stmt->bind_param("ss", $password, $email);
                $stmt->execute();

                $stmt = $con->prepare("DELETE FROM password_resets WHERE token=?");
                $stmt->bind_param("s", $token);
                $stmt->execute();

                $mensaje = "✅ Contraseña cambiada correctamente.";
                $mostrar_form = false;
            }
        } else {
            $mensaje = "⚠️ El link ha expirado.";
        }
    } else {
        $mensaje = "❌ Token inválido.";
    }
} else {
    $mensaje = "⚠️ No se proporcionó ningún token.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Restablecer contraseña</title>
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
h2 { margin-bottom: 20px; color: #333; }
input[type="password"] {
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
    <h2>Restablecer contraseña</h2>
    <?php if($mensaje) {
        $clase = strpos($mensaje, '✅') !== false ? 'exito' : '';
        echo "<p class='mensaje $clase'>$mensaje</p>";
    } ?>
    
    <?php if($mostrar_form): ?>
    <form method="POST">
        <input type="password" name="password" placeholder="Nueva contraseña" required>
        <button type="submit">Guardar</button>
    </form>
    <?php endif; ?>
</div>
</body>
</html>
