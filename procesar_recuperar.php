<?php
require("conexion.php");
$con = conectar_bd();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if (isset($_POST['email'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    
    $query = "SELECT * FROM usuario WHERE email='$email'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        $token = bin2hex(random_bytes(50)); // Genera token
        $expira = date("Y-m-d H:i:s", strtotime("+1 hour")); // Token válido 1 hora

        mysqli_query($con, "UPDATE usuario SET token='$token', token_expira='$expira' WHERE email='$email'");

        $link = "http://localhost/PáginaPrincipal/restablecer.php?token=$token";

        // Enviar correo usando PHPMailer
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // Cambiar si usas otro proveedor
            $mail->SMTPAuth   = true;
            $mail->Username   = 'tucorreo@gmail.com'; // Tu email
            $mail->Password   = 'tu_app_password'; // App password de Gmail
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('tucorreo@gmail.com', 'Sistema Recuperación');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Recuperación de contraseña';
            $mail->Body    = "Hola,<br><br>Haz clic en este enlace para restablecer tu contraseña:<br>
                            <a href='$link'>$link</a><br><br>Este enlace expira en 1 hora.";

            $mail->send();
            echo "Se ha enviado un enlace a tu correo.";
        } catch (Exception $e) {
            echo "Error al enviar el correo: {$mail->ErrorInfo}";
        }

    } else {
        echo "El email no está registrado.";
    }
} else {
    header("Location: recuperar.php");
}
