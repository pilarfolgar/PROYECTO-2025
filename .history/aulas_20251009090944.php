<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reservas - InfraLex</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="styleindexdocente.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>
<body>

<<<<<<< HEAD
<header>
    <div>
        <h1>InfraLex</h1>
        <h6>Instituto Tecnológico Superior de Paysandú</h6>
    </div>
    <a href="index.php"><img src="imagenes/logopoyecto.png" alt="Logo" class="logo"></a>
</header>
=======

>>>>>>> fcd9a64b6f14ee2cb2b60aac8d7afad8384037fd
<nav>
    <a href="funcionarios.php">Funcionarios</a>
    <a href="usuarios.html">Mi Perfil</a>
</nav>

<div class="container mt-4">
<<<<<<< HEAD
    <div class="text-center mb-3">
        <button class="boton-filtro active" id="filtro-todo" onclick="filtrar('todo')">Todos</button>
        <button class="boton-filtro" id="filtro-aula" onclick="filtrar('aula')">Aulas</button>
        <button class="boton-filtro" id="filtro-salon" onclick="filtrar('salon')">Salones</button>
        <button class="boton-filtro" id="filtro-lab" onclick="filtrar('lab')">Laboratorios</button>
    </div>

    <?php
    require("conexion.php");
    $con = conectar_bd();
    $sql = "SELECT id_aula, codigo, capacidad, ubicacion, imagen, tipo FROM aula ORDER BY codigo";
    $result = $con->query($sql);
    ?>

    <div class="row g-3">
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="col-md-4 espacio <?= htmlspecialchars($row['tipo']) ?>">
            <div class="tarjeta">
                <img src="<?= $row['imagen'] ?: 'imagenes/default-aula.jpg' ?>" 
                     alt="<?= htmlspecialchars($row['codigo']) ?>" 
                     data-bs-toggle="modal" 
                     data-bs-target="#modalImagen" 
                     onclick="mostrarImagen(this)">
                <h4><?= htmlspecialchars($row['codigo']) ?></h4>
                <p>Capacidad: <?= $row['capacidad'] ?> personas<br>Ubicación: <?= htmlspecialchars($row['ubicacion']) ?></p>
                <button class="btn btn-primary" onclick="abrirReserva('<?= htmlspecialchars($row['codigo']) ?>')">Reservar</button>
            </div>
        </div>
    <?php endwhile; ?>
    </div>
=======
<div class="text-center mb-3">
  <button class="boton-filtro active" id="filtro-todo" onclick="filtrar('todo')">Todos</button>
  <button class="boton-filtro" id="filtro-aula" onclick="filtrar('aula')">Aulas</button>
  <button class="boton-filtro" id="filtro-salon" onclick="filtrar('salon')">Salones</button>
  <button class="boton-filtro" id="filtro-lab" onclick="filtrar('lab')">Laboratorios</button>
</div>

<div class="row g-3">

    <div class="col-md-4 espacio salon">
    <div class="tarjeta">
  <img src="Imagenes/salonactos1.png" alt="Salón de actos" data-bs-toggle="modal" data-bs-target="#modalImagen" onclick="mostrarImagen(this)">
        <h4>Salón de Actos</h4>
        <p>Capacidad: 100 personas</p>
        <button class="btn btn-primary" onclick="abrirReserva('Salón de Actos')">Reservar</button>
    </div>
    </div>

    <div class="col-md-4 espacio salon">
    <div class="tarjeta">
  <img src="Imagenes/salon1.jpeg" alt="Salón 1" data-bs-toggle="modal" data-bs-target="#modalImagen" onclick="mostrarImagen(this)">
        <h4>Salón 1</h4>
        <p>Capacidad: 100 personas</p>
        <button class="btn btn-primary" onclick="abrirReserva('Salón 1')">Reservar</button>
    </div>
    </div>

    <div class="col-md-4 espacio salon">
    <div class="tarjeta">
  <img src="Imagenes/salon2.jpeg" alt="Salón 2" data-bs-toggle="modal" data-bs-target="#modalImagen" onclick="mostrarImagen(this)">
        <h4>Salón 2</h4>
        <p>Capacidad: 35 personas
          Recursos: 1 televisión, mesa de escritorio, 34 mesas, 34 sillas
        </p>
        <button class="btn btn-primary" onclick="abrirReserva('Salón 2')">Reservar</button>
    </div>
    </div>

    <div class="col-md-4 espacio salon">
    <div class="tarjeta">
  <img src="Imagenes/salon 3.jpeg" alt="Salón 3" data-bs-toggle="modal" data-bs-target="#modalImagen" onclick="mostrarImagen(this)">
        <h4>Salón 3</h4>
        <p>Capacidad: 100 personas
          Recursos: 1 televisión, mesa de escritorio, 34 mesas, 34 sillas, aire, ventilador
        </p>
        <button class="btn btn-primary" onclick="abrirReserva('Salón 3')">Reservar</button>
    </div>
    </div>

    <div class="col-md-4 espacio salon">
    <div class="tarjeta">
  <img src="Imagenes/salon 4.jpeg" alt="Salón 4" data-bs-toggle="modal" data-bs-target="#modalImagen" onclick="mostrarImagen(this)">
        <h4>Salón 4</h4>
        <p>Capacidad: 100 personas</p>
        <button class="btn btn-primary" onclick="abrirReserva('Salón 4')">Reservar</button>
    </div>
    </div>

    <div class="col-md-4 espacio salon">
    <div class="tarjeta">
  <img src="Imagenes/salon5.jpeg" alt="Salón 5" data-bs-toggle="modal" data-bs-target="#modalImagen" onclick="mostrarImagen(this)">
        <h4>Salón 5</h4>
        <p>Capacidad: 100 personas</p>
        <button class="btn btn-primary" onclick="abrirReserva('Salón 5')">Reservar</button>
    </div>
    </div>

    <div class="col-md-4 espacio lab">
      <div class="tarjeta">
  <img src="Imagenes/ROBOTICA.jpeg" alt="Laboratorio de Robótica" data-bs-toggle="modal" data-bs-target="#modalImagen" onclick="mostrarImagen(this)">
        <h4>Laboratorio de Robótica</h4>
        <p>Capacidad: 20 computadoras
          Recursos: Mesas compartidas, 22 sillas, 14 computadoras, impresora 3D
        </p>
        <button class="btn btn-primary" onclick="abrirReserva('Laboratorio de Robótica')">Reservar</button>
      </div>
    </div>

    <div class="col-md-4 espacio lab">
      <div class="tarjeta">
  <img src="Imagenes/LAB QUIMICA.jpeg" alt="Laboratorio de Química" data-bs-toggle="modal" data-bs-target="#modalImagen" onclick="mostrarImagen(this)">
        <h4>Laboratorio de Química</h4>
        <p>Capacidad: 20 computadoras</p>
        <button class="btn btn-primary" onclick="abrirReserva('Laboratorio de Química')">Reservar</button>
      </div>
    </div>

    <div class="col-md-4 espacio lab">
      <div class="tarjeta">
  <img src="Imagenes/LABFISICA.jpeg" alt="Laboratorio de Física" data-bs-toggle="modal" data-bs-target="#modalImagen" onclick="mostrarImagen(this)">
        <h4>Laboratorio de Física</h4>
        <p>Capacidad: 20 computadoras</p>
        <button class="btn btn-primary" onclick="abrirReserva('Laboratorio de Física')">Reservar</button>
      </div>
    </div>

    <div class="col-md-4 espacio lab">
      <div class="tarjeta">
  <img src="Imagenes/TALLER.jpeg" alt="Laboratorio de Taller de Mantenimiento" data-bs-toggle="modal" data-bs-target="#modalImagen" onclick="mostrarImagen(this)">
        <h4>Laboratorio de Taller de Mantenimiento</h4>
        <p>Capacidad: 20 computadoras</p>
        <button class="btn btn-primary" onclick="abrirReserva('Laboratorio de Taller de Mantenimiento')">Reservar</button>
      </div>
    </div>

        <div class="col-md-4 espacio lab">
      <div class="tarjeta">
  <img src="Imagenes/LABELECTRONICA.jpeg" alt="Laboratorio de Taller de Mantenimiento" data-bs-toggle="modal" data-bs-target="#modalImagen" onclick="mostrarImagen(this)">
        <h4>Laboratorio de Electrónica</h4>
        <p>Capacidad: 30 personas aproximadamente
          Recursos: 1 televisión, 18 mesas, 18 sillas
        </p>
        <button class="btn btn-primary" onclick="abrirReserva('Laboratorio de Taller de Mantenimiento')">Reservar</button>
      </div>
    </div>

    <div class="col-md-4 espacio aula">
      <div class="tarjeta">
  <img src="Imagenes/aula1.jpeg" alt="Aula 1" data-bs-toggle="modal" data-bs-target="#modalImagen" onclick="mostrarImagen(this)">
        <h4>Aula 1</h4>
        <p>Capacidad: 30 personas<br>
          Recursos: Proyector, Televisión, Aire, 13 computadoras
        </p>
        <button class="btn btn-primary" onclick="abrirReserva('Aula 1')">Reservar</button>
      </div>
    </div>

    <div class="col-md-4 espacio aula">
      <div class="tarjeta">
  <img src="Imagenes/aula2.jpeg" alt="Aula 2" data-bs-toggle="modal" data-bs-target="#modalImagen" onclick="mostrarImagen(this)">
        <h4>Aula 2</h4>
        <p>Capacidad: 30 personas
           Recursos: Proyector, Televisión, Aire, 13 computadoras
        </p>
        <button class="btn btn-primary" onclick="abrirReserva('Aula 2')">Reservar</button>
      </div>
    </div>

    <div class="col-md-4 espacio aula">
      <div class="tarjeta">
  <img src="Imagenes/aula3.jpeg" alt="Aula 3" data-bs-toggle="modal" data-bs-target="#modalImagen" onclick="mostrarImagen(this)">
        <h4>Aula 3</h4>
        <p>Capacidad: 30 personas</p>
        <button class="btn btn-primary" onclick="abrirReserva('Aula 3')">Reservar</button>
      </div>
    </div>
  </div>
>>>>>>> fcd9a64b6f14ee2cb2b60aac8d7afad8384037fd
</div>

<!-- Modal Imagen -->
<div class="modal fade" id="modalImagen" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-dark">
      <div class="modal-body p-0">
        <img id="imagenAmpliada" src="" class="w-100" style="object-fit: contain;">
      </div>
    </div>
  </div>
</div>

<!-- Modal Reserva -->
<div class="modal fade" id="modalReserva" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="tituloReserva">Reservar</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="formReserva">
          <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Aula</label>
            <input type="text" class="form-control" id="aulaSeleccionada" readonly required>
          </div>
          <div class="mb-3">
            <label class="form-label">Fecha</label>
            <input type="date" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Hora de inicio</label>
            <input type="time" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Hora de fin</label>
            <input type="time" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Grupo</label>
            <select class="form-select" id="grupoReserva" required>
              <option selected disabled>Seleccione grupo...</option>
              <option value="1MA">1°MA</option>
              <option value="2BB">2°BB</option>
              <option value="3CA">3°CA</option>
              <option value="4DA">4°DA</option>
              <option value="5EA">5°EA</option>
              <option value="6FA">6°FA</option>
            </select>
          </div>
          <button type="submit" class="btn btn-success w-100">Confirmar Reserva</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script src="appaulas.js"></script>
</body>
</html>
