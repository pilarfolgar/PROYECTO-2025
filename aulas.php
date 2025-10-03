<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reservas - InfraLex</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>
<body>

<header>
    <div>
    <h1>InfraLex</h1>
    <h6>Instituto Tecnológico Superior de Paysandú</h6>
    </div>
    <a href="index.php"><img src="logopoyecto.png" alt="Logo" class="logo"></a>
</header>
<nav>
    <a href="#horarios.html">Horarios</a>
    <a href="#funcionarios.html">Funcionarios</a>
    <a href="usuarios.html">Mi Perfil</a>
</nav>
<div class="container mt-4">
<div class="text-center mb-3">
    <button class="boton-filtro" onclick="filtrar('todo')">Todos</button>
    <button class="boton-filtro" onclick="filtrar('aula')">Aulas</button>
    <button class="boton-filtro" onclick="filtrar('salon')">Salones</button>
    <button class="boton-filtro" onclick="fi<ltrar('lab')">Laboratorios</button>
</div>

<div class="row g-3">

    <div class="col-md-4 espacio salon">
    <div class="tarjeta">
        <img src="salon1.jpg" alt="Salón de actos" data-bs-toggle="modal" data-bs-target="#modalImagen" onclick="mostrarImagen(this)">
        <h4>Salón de Actos</h4>
        <p>Capacidad: 100 personas</p>
        <button class="btn btn-primary" onclick="abrirReserva('Salón de Actos')">Reservar</button>
    </div>
    </div>

    <div class="col-md-4 espacio salon">
    <div class="tarjeta">
        <img src="salon1.jpg" alt="Salón 1" data-bs-toggle="modal" data-bs-target="#modalImagen" onclick="mostrarImagen(this)">
        <h4>Salón 1</h4>
        <p>Capacidad: 100 personas</p>
        <button class="btn btn-primary" onclick="abrirReserva('Salón 1')">Reservar</button>
    </div>
    </div>

    <div class="col-md-4 espacio salon">
    <div class="tarjeta">
        <img src="salon1.jpg" alt="Salón 2" data-bs-toggle="modal" data-bs-target="#modalImagen" onclick="mostrarImagen(this)">
        <h4>Salón 2</h4>
        <p>Capacidad: 100 personas</p>
        <button class="btn btn-primary" onclick="abrirReserva('Salón 2')">Reservar</button>
    </div>
    </div>

    <div class="col-md-4 espacio salon">
    <div class="tarjeta">
        <img src="salon1.jpg" alt="Salón 3" data-bs-toggle="modal" data-bs-target="#modalImagen" onclick="mostrarImagen(this)">
        <h4>Salón 3</h4>
        <p>Capacidad: 100 personas</p>
        <button class="btn btn-primary" onclick="abrirReserva('Salón 3')">Reservar</button>
    </div>
    </div>

    <div class="col-md-4 espacio salon">
    <div class="tarjeta">
        <img src="salon1.jpg" alt="Salón 4" data-bs-toggle="modal" data-bs-target="#modalImagen" onclick="mostrarImagen(this)">
        <h4>Salón 4</h4>
        <p>Capacidad: 100 personas</p>
        <button class="btn btn-primary" onclick="abrirReserva('Salón 4')">Reservar</button>
    </div>
    </div>

    <div class="col-md-4 espacio salon">
    <div class="tarjeta">
        <img src="salon1.jpg" alt="Salón 5" data-bs-toggle="modal" data-bs-target="#modalImagen" onclick="mostrarImagen(this)">
        <h4>Salón 5</h4>
        <p>Capacidad: 100 personas</p>
        <button class="btn btn-primary" onclick="abrirReserva('Salón 5')">Reservar</button>
    </div>
    </div>

    <div class="col-md-4 espacio lab">
      <div class="tarjeta">
        <img src="labrobotica.jpeg" alt="Laboratorio de Robótica" data-bs-toggle="modal" data-bs-target="#modalImagen" onclick="mostrarImagen(this)">
        <h4>Laboratorio de Robótica</h4>
        <p>Capacidad: 20 computadoras</p>
        <button class="btn btn-primary" onclick="abrirReserva('Laboratorio de Robótica')">Reservar</button>
      </div>
    </div>

    <div class="col-md-4 espacio lab">
      <div class="tarjeta">
        <img src="lab1.jpg" alt="Laboratorio de Química" data-bs-toggle="modal" data-bs-target="#modalImagen" onclick="mostrarImagen(this)">
        <h4>Laboratorio de Química</h4>
        <p>Capacidad: 20 computadoras</p>
        <button class="btn btn-primary" onclick="abrirReserva('Laboratorio de Química')">Reservar</button>
      </div>
    </div>

    <div class="col-md-4 espacio lab">
      <div class="tarjeta">
        <img src="lab1.jpg" alt="Laboratorio de Física" data-bs-toggle="modal" data-bs-target="#modalImagen" onclick="mostrarImagen(this)">
        <h4>Laboratorio de Física</h4>
        <p>Capacidad: 20 computadoras</p>
        <button class="btn btn-primary" onclick="abrirReserva('Laboratorio de Física')">Reservar</button>
      </div>
    </div>

    <div class="col-md-4 espacio lab">
      <div class="tarjeta">
        <img src="lab1.jpg" alt="Laboratorio de Taller de Mantenimiento" data-bs-toggle="modal" data-bs-target="#modalImagen" onclick="mostrarImagen(this)">
        <h4>Laboratorio de Taller de Mantenimiento</h4>
        <p>Capacidad: 20 computadoras</p>
        <button class="btn btn-primary" onclick="abrirReserva('Laboratorio de Taller de Mantenimiento')">Reservar</button>
      </div>
    </div>

    <div class="col-md-4 espacio aula">
      <div class="tarjeta">
        <img src="aula3.jpg" alt="Aula 1" data-bs-toggle="modal" data-bs-target="#modalImagen" onclick="mostrarImagen(this)">
        <h4>Aula 1</h4>
        <p>Capacidad: 30 personas</p>
        <button class="btn btn-primary" onclick="abrirReserva('Aula 1')">Reservar</button>
      </div>
    </div>

    <div class="col-md-4 espacio aula">
      <div class="tarjeta">
        <img src="aula3.jpg" alt="Aula 2" data-bs-toggle="modal" data-bs-target="#modalImagen" onclick="mostrarImagen(this)">
        <h4>Aula 2</h4>
        <p>Capacidad: 30 personas</p>
        <button class="btn btn-primary" onclick="abrirReserva('Aula 2')">Reservar</button>
      </div>
    </div>

    <div class="col-md-4 espacio aula">
      <div class="tarjeta">
        <img src="aula3.jpg" alt="Aula 3" data-bs-toggle="modal" data-bs-target="#modalImagen" onclick="mostrarImagen(this)">
        <h4>Aula 3</h4>
        <p>Capacidad: 30 personas</p>
        <button class="btn btn-primary" onclick="abrirReserva('Aula 3')">Reservar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalImagen" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-dark">
      <div class="modal-body p-0">
        <img id="imagenAmpliada" src="" class="w-100" style="object-fit: contain;">
      </div>
    </div>
  </div>
</div>

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
            <label class="form-label">Comentarios</label>
            <textarea class="form-control"></textarea>
          </div>
          <button type="submit" class="btn btn-success w-100">Confirmar Reserva</button>
        </form>
      </div>
    </div>
  </div>
</div>
<script src="appaulas.js"></script>
</body>
</html>