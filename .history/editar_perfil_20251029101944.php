<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// Verificar sesión
if (!isset($_SESSION['cedula'])) {
    header("Location: login.php");
    exit;
}

$cedula = $_SESSION['cedula'];

// Traer datos del usuario
$stmt = $con->prepare("SELECT cedula, nombrecompleto, pass, apellido, email, rol, telefono, foto, asignatura, id_grupo FROM usuario WHERE cedula=?");
$stmt->bind_param("s", $cedula);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) {
    echo "Usuario no encontrado.";
    exit;
}

$mensaje = '';

// ---------------------------
// ELIMINAR CUENTA
// ---------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_cuenta'])) {
    $del_stmt = $con->prepare("DELETE FROM usuario WHERE cedula=?");
    $del_stmt->bind_param("s", $cedula);
    $del_stmt->execute();
    $del_stmt->close();

    session_destroy();
    header("Location: login.php");
    exit;
}

// ---------------------------
// PROCESAR EDICIÓN DE PERFIL
// ---------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['eliminar_cuenta'])) {
    $nombre = trim($_POST['nombrecompleto'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $asignatura = trim($_POST['asignatura'] ?? '');
    $id_grupo = $_POST['id_grupo'] !== '' ? intval($_POST['id_grupo']) : null;
    $pass_actual = $_POST['pass_actual'] ?? '';
    $pass_nueva = $_POST['pass_nueva'] ?? '';

    // -------------------
    // Manejar subida de foto
    // -------------------
    $foto_ruta = $user['foto'];
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $archivo_tmp = $_FILES['foto']['tmp_name'];
        $nombre_archivo = basename($_FILES['foto']['name']);
        $ext = strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));
        $ext_permitidas = ['jpg','jpeg','png','gif'];

        if (!in_array($ext, $ext_permitidas)) {
            $mensaje = "Formato de imagen no permitido. Solo jpg, png y gif.";
        } else {
            $nuevo_nombre = 'uploads/' . $cedula . '_' . time() . '.' . $ext;
            if (!is_dir('uploads')) mkdir('uploads', 0755, true);
            if (move_uploaded_file($archivo_tmp, $nuevo_nombre)) {
                $foto_ruta = $nuevo_nombre;
                $upd_foto = $con->prepare("UPDATE usuario SET foto=? WHERE cedula=?");
                $upd_foto->bind_param("ss", $foto_ruta, $cedula);
                $upd_foto->execute();
                $upd_foto->close();
            } else {
                $mensaje = "Error al subir la foto.";
            }
        }
    }

    // -------------------
    // Cambiar contraseña
    // -------------------
    if (!empty($pass_actual) || !empty($pass_nueva)) {
        if (empty($pass_actual) || empty($pass_nueva)) {
            $mensaje = "Completa ambos campos para cambiar la contraseña.";
        } elseif (!password_verify($pass_actual, $user['pass'])) {
            $mensaje = "Contraseña actual incorrecta.";
        } else {
            $pass_hashed = password_hash($pass_nueva, PASSWORD_DEFAULT);
            $upd_pass = $con->prepare("UPDATE usuario SET pass=? WHERE cedula=?");
            $upd_pass->bind_param("ss", $pass_hashed, $cedula);
            $upd_pass->execute();
            $upd_pass->close();
            $mensaje = "Contraseña actualizada con éxito.";
        }
    }

    // -------------------
    // Actualizar datos del perfil
    // -------------------
    if ($id_grupo === null) {
        $upd = $con->prepare("UPDATE usuario SET nombrecompleto=?, apellido=?, telefono=?, asignatura=?, id_grupo=NULL WHERE cedula=?");
        $upd->bind_param("sssss", $nombre, $apellido, $telefono, $asignatura, $cedula);
    } else {
        $upd = $con->prepare("UPDATE usuario SET nombrecompleto=?, apellido=?, telefono=?, asignatura=?, id_grupo=? WHERE cedula=?");
        $upd->bind_param("ssssis", $nombre, $apellido, $telefono, $asignatura, $id_grupo, $cedula);
    }
    $upd->execute();
    $upd->close();

    if (empty($mensaje)) $mensaje = "Perfil actualizado con éxito.";

    header("Refresh: 2; URL=perfil.php");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Perfil - InfraLex</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="perfil.css">
<style>
.edit-card { max-width: 600px; margin: 40px auto; padding: 2rem; border-radius: 12px; background: #fff; box-shadow: 0 8px 20px rgba(0,0,0,0.1); text-align: center; }
.edit-card img { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; margin-bottom: 20px; border: 4px solid #588BAE; }
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php require("header.php"); ?>

<div class="container">
    <div class="edit-card">
        <h2 class="mb-4">Editar Perfil</h2>

        <?php if(!empty($mensaje)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <img src="<?= htmlspecialchars($user['foto'] ?: 'imagenes/default-user.png') ?>" alt="Foto de perfil">

        <form method="post" enctype="multipart/form-data" class="text-start mt-4">
            <div class="mb-3">
                <label class="form-label">Foto de Perfil</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
            </div>
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombrecompleto" class="form-control" value="<?= htmlspecialchars($user['nombrecompleto']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Apellido</label>
                <input type="text" name="apellido" class="form-control" value="<?= htmlspecialchars($user['apellido']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($user['telefono']) ?>">
            </div>

            <?php if($user['rol'] === 'docente'): ?>
            <div class="mb-3">
                <label class="form-label">Asignatura</label>
                <input type="text" name="asignatura" class="form-control" value="<?= htmlspecialchars($user['asignatura']) ?>">
            </div>
            <?php else: ?>
            <div class="mb-3">
                <label class="form-label">Grupo</label>
                <input type="text" name="id_grupo" class="form-control" value="<?= htmlspecialchars($user['id_grupo']) ?>">
            </div>
            <?php endif; ?>

            <hr>
            <h5>Cambiar Contraseña</h5>
            <div class="mb-3">
                <label class="form-label">Contraseña Actual</label>
                <input type="password" name="pass_actual" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Nueva Contraseña</label>
                <input type="password" name="pass_nueva" class="form-control">
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="perfil.php" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
        </form>

        <hr class="my-4">

        <a href="logout.php" class="btn btn-danger w-100 mb-2">Cerrar Sesión</a>

        <!-- Botón para eliminar cuenta -->
        <form id="eliminarCuentaForm" method="post">
            <input type="hidden" name="eliminar_cuenta" value="1">
            <button type="button" class="btn btn-danger w-100 mt-2" id="btnEliminar">Eliminar Cuenta</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Confirmar eliminación de cuenta
document.getElementById('btnEliminar').addEventListener('click', function(e) {
    e.preventDefault();
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede deshacer.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('eliminarCuentaForm').submit();
        }
    });
});
</script>

</body>
</html>
