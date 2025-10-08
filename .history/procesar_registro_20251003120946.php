<?php
require("conexion.php");
$con = conectar_bd();

// Verificamos si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Coinciden con el form
    $nombre   = mysqli_real_escape_string($con, $_POST["nombre"]);
    $apellido = mysqli_real_escape_string($con, $_POST["apellido"]);
    $email    = mysqli_real_escape_string($con, $_POST["email"]);
    $password = mysqli_real_escape_string($con, $_POST["pass"]); // antes era "password"
    $cedula = isset($_POST["cedula"]) ? intval($_POST["cedula"]) : 0;
    $rol      = isset($_POST["rol"]) ? mysqli_real_escape_string($con, $_POST["rol"]) : NULL;

    // Campos opcionales (para estudiantes)
    $turno = !empty($_POST["turno"]) ? mysqli_real_escape_string($con, $_POST["turno"]) : NULL;
    $curso = !empty($_POST["curso"]) ? mysqli_real_escape_string($con, $_POST["curso"]) : NULL;
    $clase = !empty($_POST["clase"]) ? mysqli_real_escape_string($con, $_POST["clase"]) : NULL;

    // Verificar si ya existe usuario con ese email
    if (consultar_existe_usr($con, $cedula)) {
        echo "El usuario ya existe.";
    } else {
        insertar_datos($con, $nombre, $apellido, $email, $password, $cedula, $rol, $turno, $curso, $clase);
    }
}

// ============= FUNCIONES ==============
function consultar_existe_usr($con, $cedula) {
    $consulta = "SELECT cedula FROM usuario WHERE cedula = '$cedula'";
    $resultado = mysqli_query($con, $consulta);
    return (mysqli_num_rows($resultado) > 0);
}

function insertar_datos($con, $nombre, $apellido, $email, $password, $cedula, $rol, $turno, $curso, $clase) {
    // $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $consulta_insertar = "INSERT INTO usuario
        (cedula, nombrecompleto, pass, apellido, email, rol)
        VALUES
        ('$cedula', '$nombre','$password','$apellido', '$email', '$rol')";


        
 mysqli_query($con, $consulta_insertar);
        
    if (mysqli_query($con, $consulta_insertar)) {
        echo "Registro exitoso.";
        header("Location: indexadministrativo.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($con);
    }
}



?>
