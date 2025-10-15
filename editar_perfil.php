<?php
session_start();
require("conexion.php");
$con = conectar_bd();

if (!isset($_SESSION['email'])) {
    echo "<div class='container mt-5'><div class='alert alert-danger text-center'>No estás logueado.</div></div>";
    exit;
}

$email = $_SESSION['email'];

// Traer datos del usuario
$stmt = $con->prepare("SELECT cedula, nombrecompleto, apellido, email, rol, telefono, foto, asignatura, id_grupo FROM usuario WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$user) { echo "Usuario no encontrado."; exit; }

// Procesar actualización
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $email_nuevo = trim($_POST['email'] ?? '');
    $asignatura = trim($_POST['asignatura'] ?? '');
    $id_grupo = intval($_POST['id_grupo'] ?? 0);

    $fotoNombre = $user['foto'];
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $fotoNombre = 'uploads/' . uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], $fotoNombre);
    }

    if (!$nombre || !$apellido || !$email_nuevo) {
        $mensaje = '<div class="alert alert-danger">Nombre, apellido y email son obligatorios.</div>';
    } else {
        $stmt = $con->prepare("UPDATE usuario SET nombrecompleto=?, apellido=?, email=?, telefono=?, foto=?, asignatura=?, id_grupo=? WHERE cedula=?");
        $stmt->bind_param("ssssssii", $nombre, $apellido, $email_nuevo, $telefono, $fotoNombre, $asignatura, $id_grupo, $user['cedula']);
        $mensaje = $stmt->execute() ? '<div class="alert alert-success">Perfil actualizado ✅</div>' : '<div class="alert alert-danger">Error al actualizar.</div>';
        $_SESSION['email'] = $email_nuevo;
        $stmt->close();
    }
}

// Grupos solo para estudiantes
$grupos = [];
if ($user['rol'] === 'estudiante') {
    $res = $con->query("SELECT id_grupo, nombre, orientacion FROM grupo ORDER BY nombre");
    while ($r = $res->fetch_assoc()) $grupos[] = $r;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Perfil - InfraLex</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
<style>.perfil-card img { width:120px; height:120px; object-fit:cover; border-radius:50%; margin-bottom:15px; }</style>
</head>
<body>
<?php require("header.php"); ?>

<div class="container my-5">
    <h2 class="mb-4 text-center">Editar Perfil</h2>
    <?php if($mensaje) echo $mensaje; ?>
    <div class="card perfil-card mx-auto p-4 shadow-sm" style="max-width:500px;">
        <form method="POST" enctype="multipart/form-data">
            <div class="text-center mb-3">
                <img src="<?= htmlspecialchars($user['foto'] ?: 'imagenes/default-user.png') ?>" alt="Foto de perfil">
            </div>
            <input type="text" name="nombre" class="form-control mb-3" value="<?= htmlspecialchars($user['nombrecompleto']) ?>" required placeholder="Nombre">
            <input type="text" name="apellido" class="form-control mb-3" value="<?= htmlspecialchars($user['apellido']) ?>" required placeholder="Apellido">
            <input type="email" name="email" class="form-control mb-3" value="<?= htmlspecialchars($user['email']) ?>" required placeholder="Email">
            <input type="text" name="telefono" class="form-control mb-3" value="<?= htmlspecialchars($user['telefono']) ?>" placeholder="Teléfono">
            <input type="file" name="foto" class="form-control mb-3">
            
            <?php if($user['rol']==='docente'): ?>
                <input type="text" name="asignatura" class="form-control mb-3" value="<?= htmlspecialchars($user['asignatura']) ?>" placeholder="Asignatura">
            <?php else: ?>
                <select name="id_grupo" class="form-select mb-3" required>
                    <option disabled>Seleccione grupo...</option>
                    <?php foreach($grupos as $g): ?>
                        <option value="<?= intval($g['id_grupo']) ?>" <?= ($g['id_grupo']==$user['id_grupo'])?'selected':'' ?>>
                            <?= htmlspecialchars($g['nombre'].' - '.$g['orientacion']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>

            <button type="submit" class="btn btn-success w-100">Guardar cambios</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
