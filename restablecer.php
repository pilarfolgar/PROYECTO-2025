<?php
require("conexion.php");
$con = conectar_bd();
$mensaje = "";

if (isset($_GET["token"])) {
    $token = $_GET["token"];

    $stmt = $con->prepare("SELECT email, expira FROM password_resets WHERE token=?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        if (strtotime($row["expira"]) > time()) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
                $email = $row["email"];

                $stmt = $con->prepare("UPDATE usuario SET password=? WHERE email=?");
                $stmt->bind_param("ss", $password, $email);
                $stmt->execute();

                $stmt = $con->prepare("DELETE FROM password_resets WHERE token=?");
                $stmt->bind_param("s", $token);
                $stmt->execute();

                $mensaje = "Contraseña cambiada correctamente.";
            }
        } else {
            $mensaje = "El link ha expirado.";
        }
    } else {
        $mensaje = "Token inválido.";
    }
}
?>

<form method="POST">
    <h2>Restablecer contraseña</h2>
    <?php if($mensaje) echo "<p>$mensaje</p>"; ?>
    <input type="password" name="password" placeholder="Nueva contraseña" required>
    <button type="submit">Guardar</button>
</form>
