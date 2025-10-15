<?php
session_start();
require("conexion.php");

$con = conectar_bd();

// Cargamos PHPMailer desde tu carpeta local
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if(isset($_POST['email'])){
    $email = trim($_POST['email']);

    // Verificar si existe el usuario
    $stmt = mysqli_prepare($con, "SELECT cedula, nombrecompleto FROM usuario WHERE email=?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if($user = mysqli_fetch_assoc($res)) {
        // Generar código de 6 dígitos
        $codigo = random_int(100000, 999999);
        $expira = date("Y-m-d H:i:s", time() + 900); // 15 minutos

        // Guardar en BD
        $stmt = mysqli_prepare($con, "UPDATE usuario SET reset_token=?, reset_expira=? WHERE email=?");
        mysqli_stmt_bind_param($stmt, "sss", $codigo, $expira, $email);
        mysqli_stmt_execute($stmt);

        // Enviar correo con PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';          // SMTP de Gmail
            $mail->SMTPAuth = true;
            $mail->Username = 'tu_correo@gmail.com'; // tu correo
            $mail->Password = 'tu_app_password';     // app password de Gmail
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('no-reply@tuweb.com', 'Sistema de Recuperación');
            $mail->addAddress($email, $user['nombrecompleto']);

            $mail->isHTML(true);
            $mail->Subject = 'Código de recuperación de contraseña';
            $mail->Body = "<p>Hola <b>".$user['nombrecompleto']."</b>,</p>
                           <p>Tu código de recuperación es: <b>$codigo</b></p>
                           <p>Expira en 15 minutos.</p>";

            $mail->send();

            $_SESSION['mensaje'] = "Se ha enviado un código a tu correo.";
            header("Location: verificar_codigo.php?email=" . urlencode($email));
            exit;

        } catch (Exception $e) {
            $_SESSION['mensaje'] = "No se pudo enviar el correo. Error: {$mail->ErrorInfo}";
            header("Location: recuperar.php");
            exit;
        }

    } else {
        $_SESSION['mensaje'] = "No existe una cuenta con ese correo.";
        header("Location: recuperar.php");
        exit;
    }
}
?>
