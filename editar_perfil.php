<?php
session_start();
require("conexion.php");
$con = conectar_bd();

if (!isset($_SESSION['cedula'])) {
    header("Location: iniciosesion.php");
    exit;
}

$cedula = $_SESSION['cedula'];

// ✅ Traer datos del usuario sin filtrar por rol
$stmt = $con->prepare("SELECT * FROM usuario WHERE cedula=?");
$stmt->bind_param("s", $cedula);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) {
    echo "<h3 style='text-align:center;margin-top:50px;color:red;'>❌ Usuario no encontrado.</h3>";
    exit;
}

$rol = strtolower($user['rol']);
$mensaje = "";

// ✅ Obtener lista de grupos (solo para estudiantes)
$grupos = [];
if ($rol === 'estudiante') {
    $grupos_result = $con->query("SELECT id_grupo, nombre FROM grupo ORDER BY nombre");
    while ($row = $grupos_result->fetch_assoc()) {
        $grupos[$row['id_grupo']] = $row['nombre'];
    }
}

// ✅ Procesar edición
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombrecompleto']);
    $apellido = trim($_POST['apellido']);
    $telefono = trim($_POST['telefono']) ?: null;
    $ruta_foto = $user['foto'];

    // Subida de foto
    if (!empty($_FILES['foto']['name'])) {
        $nombre_foto = time() . '_' . basename($_FILES['foto']['name']);
        $ruta_destino = 'imagenes/' . $nombre_foto;
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_destino)) {
            $ruta_foto = $ruta_destino;
        }
    }

    if ($rol === 'estudiante') {
        $id_grupo = !empty($_POST['id_grupo']) ? (int)$_POST['id_grupo'] : null;
        $stmt = $con->prepare("UPDATE usuario SET nombrecompleto=?, apellido=?, telefono=?, id_grupo=?, foto=? WHERE cedula=?");
        $stmt->bind_param("sssiss", $nombre, $apellido, $telefono, $id_grupo, $ruta_foto, $cedula);
    } else {
        // Docente: actualizar asignatura si existe
        $asignatura = trim($_POST['asignatura'] ?? $user['asignatura']);
        $stmt = $con->prepare("UPDATE usuario SET nombrecompleto=?, apellido=?, telefono=?, asignatura=?, foto=? WHERE cedula=?");
        $stmt->bind_param("sssss", $nombre, $apellido, $telefono, $asignatura, $ruta_foto, $cedula);
    }

    $stmt->execute();
    if ($stmt->error) {
        $mensaje = "❌ Error al actualizar: " . $stmt->error;
    } elseif ($stmt->affected_rows > 0) {
        $mensaje = "✅ Perfil actualizado correctamente.";
    } else {
        $mensaje = "⚠️ No se detectaron cambios.";
    }
    $stmt->close();

    // ✅ Cambiar contraseña
    if (!empty($_POST['pass_actual']) && !empty($_POST['pass_nueva'])) {
        $pass_actual = $_POST['pass_actual'];
        $pass_nueva = password_hash($_POST['pass_nueva'], PASSWORD_DEFAULT);

        $stmt = $con->prepare("SELECT pass FROM usuario WHERE cedula=?");
        $stmt->bind_param("s", $cedula);
        $stmt->execute();
        $datos = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($datos && password_verify($pass_actual, $datos['pass'])) {
            $stmt = $con->prepare("UPDATE usuario SET pass=? WHERE cedula=?");
            $stmt->bind_param("ss", $pass_nueva, $cedula);
            $stmt->execute();
            $stmt->close();
            $mensaje = "✅ Perfil y contraseña actualizados correctamente.";
        } else {
            $mensaje = "⚠️ La contraseña actual no es correcta.";
        }
    }

    // Recargar datos actualizados
    $stmt = $con->prepare("SELECT * FROM usuario WHERE cedula=?");
    $stmt->bind_param("s", $cedula);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Perfil - InfraLex</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="perfil.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php require("header.php"); ?>

<div class="container mt-4">
    <div class="perfil-card">
        <h2 class="mb-4">Editar Perfil de <?= ucfirst($rol) ?></h2>

        <?php if(!empty($mensaje)): ?>
            <script>
            Swal.fire({
                icon: 'info',
                title: 'Resultado',
                text: '<?= addslashes($mensaje) ?>',
                confirmButtonText: 'OK'
            });
            </script>
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

            <?php if($rol === 'estudiante'): ?>
                <div class="mb-3">
                    <label class="form-label">Grupo</label>
                    <select name="id_grupo" class="form-control">
                        <option value="">-- Ningún grupo --</option>
                        <?php foreach($grupos as $id => $nombre_grupo): ?>
                            <option value="<?= $id ?>" <?= ($user['id_grupo']==$id ? 'selected' : '') ?>><?= htmlspecialchars($nombre_grupo) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php else: ?>
                <div class="mb-3">
                    <label class="form-label">Asignatura</label>
                    <input type="text" name="asignatura" class="form-control" value="<?= htmlspecialchars($user['asignatura'] ?? '') ?>">
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
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<?php include('footer.php'); ?>
</body>
</html>
