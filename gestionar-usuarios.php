<?php
session_start();
require("conexion.php");

$con = conectar_bd();

// Verificar que sea administrativo
if (!isset($_SESSION['cedula'], $_SESSION['rol']) || $_SESSION['rol'] !== 'administrativo') {
    header("Location: iniciosesion.php");
    exit();
}

// Eliminar usuario si se recibe GET 'cedula'
if (isset($_GET['cedula'])) {
    $cedula = $con->real_escape_string($_GET['cedula']);
    $sql_eliminar = "DELETE FROM usuario WHERE cedula = '$cedula' AND rol='estudiante'";
    if ($con->query($sql_eliminar)) {
        $_SESSION['msg_usuario'] = "Usuario eliminado con éxito";
    } else {
        $_SESSION['error_usuario'] = "Error al eliminar usuario";
    }
    header("Location: gestionar-usuarios.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Usuarios - InfraLex</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php require("header.php"); ?>

<main class="container my-5">
    <h1 class="mb-4">👥 Gestión de Estudiantes</h1>

    <?php
    $sql = "SELECT * FROM usuario WHERE rol='estudiante' ORDER BY nombrecompleto";
    $result = $con->query($sql);
    if ($result && $result->num_rows > 0):
    ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Cédula</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['cedula']) ?></td>
                    <td><?= htmlspecialchars($row['nombrecompleto']) ?></td>
                    <td><?= htmlspecialchars($row['apellido']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['telefono']) ?></td>
                    <td>
                        <a href="gestionar-usuarios.php?cedula=<?= $row['cedula'] ?>" class="btn btn-sm btn-danger" 
                           onclick="return confirm('¿Seguro que deseas eliminar a este estudiante?');">Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
        <p>No hay estudiantes registrados.</p>
    <?php endif; ?>
</main>

<?php
// Mensajes con SweetAlert
if (isset($_SESSION['msg_usuario'])) {
    $msg = $_SESSION['msg_usuario'];
    echo "<script>Swal.fire({icon:'success', title:'Éxito', text:'$msg', confirmButtonColor:'#3085d6'});</script>";
    unset($_SESSION['msg_usuario']);
}
if (isset($_SESSION['error_usuario'])) {
    $msg = $_SESSION['error_usuario'];
    echo "<script>Swal.fire({icon:'error', title:'Error', text:'$msg', confirmButtonColor:'#d33'});</script>";
    unset($_SESSION['error_usuario']);
}
?>

<?php require("footer.php"); ?>
</body>
</html>
