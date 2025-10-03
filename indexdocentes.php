<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Panel Docentes - InfraLex</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>
<body>

<header>
  <div class="HeaderIzq">
    <h1>InfraLex</h1>
    <h6>Instituto Tecnológico Superior de Paysandú</h6>
  </div>
  <div class="header-right">
    <a href="INDEX.PHP"><img src="imagenes/logopoyecto.png" alt="Logo" class="logo"></a>
  </div>
</header>

<nav>
  <a href="#">Mis cursos</a>
  <a href="aulas.php" id="btnReservar">Reservar aulas</a>
  <a href="usuarios.html">Mi Perfil</a>
</nav>

<!-- FORMULARIO RESERVA (ahora como overlay modal con estilo de .formulario) -->
<div id="formularioReserva" class="formulario">
  <form id="formReserva" action="reservar.php" method="POST" class="row g-3 needs-validation" novalidate>
    <button type="button" class="cerrar" onclick="cerrarFormulario()">&times;</button>
    <h2 class="form-title">Reservar Aula</h2>

    <div class="col-md-6">
      <label for="nombre" class="form-label">Nombre</label>
      <input type="text" class="form-control" name="nombre" id="nombre" required>
    </div>
    <div class="col-md-6">
      <label for="ci" class="form-label">CI</label>
      <input type="text" class="form-control" name="ci" id="ci" required>
    </div>

    <div class="col-md-6">
      <label for="turno" class="form-label">Turno</label>
      <select class="form-select" name="turno" id="turno" required>
        <option selected disabled value="">Elija...</option>
        <option value="matutino">Matutino</option>
        <option value="vespertino">Vespertino</option>
        <option value="nocturno">Nocturno</option>
      </select>
    </div>

    <div class="col-md-6">
      <label for="clase" class="form-label">Clase</label>
      <select class="form-select" name="clase" id="clase" required>
        <option selected disabled value="">Primero elija un turno...</option>
      </select>
    </div>

    <div class="col-md-6">
      <label for="materia" class="form-label">Materia</label>
      <select class="form-select" name="materia" id="materia" required>
        <option selected disabled value="">Primero elija una clase...</option>
      </select>
    </div>

    <div class="col-md-6">
      <label for="aula" class="form-label">Aula a reservar</label>
      <select class="form-select" name="aula" id="aula" required>
        <option selected disabled value="">Elija...</option>
        <option>Aula 1</option>
        <option>Aula 2</option>
        <option>Aula 3</option>
        <option>Salón 1</option>
        <option>Salón 2</option>
        <option>Laboratorio Química</option>
        <option>Laboratorio Física</option>
        <option>Laboratorio Robótica</option>
        <option>Salón de Actos</option>
      </select>
    </div>

    <div class="col-md-6">
      <label for="fecha" class="form-label">Fecha</label>
      <input type="date" class="form-control" name="fecha" id="fecha" required>
    </div>

    <div class="col-md-3">
      <label for="horaInicio" class="form-label">Hora inicio</label>
      <select class="form-select" name="horaInicio" id="horaInicio" required>
        <option selected disabled value="">Primero elija un turno...</option>
      </select>
    </div>

    <div class="col-md-3">
      <label for="cantidadHoras" class="form-label">Cantidad de horas</label>
      <select class="form-select" name="cantidadHoras" id="cantidadHoras" required>
        <option selected disabled value="">Elija...</option>
        <option value="1">1 hora</option>
        <option value="2">2 horas</option>
        <option value="3">3 horas</option>
        <option value="4">4 horas</option>
      </select>
    </div>

    <div class="col-md-3">
      <label for="horaFin" class="form-label">Hora fin</label>
      <input type="text" class="form-control" name="horaFin" id="horaFin" readonly>
    </div>

    <div class="col-12">
      <button class="boton" type="submit">Reservar</button>
    </div>
  </form>
</div>

<main class="contenedor">
  <div class="tarjeta">
    <h3>Mis cursos</h3>
    <p>Accede a tus cursos y materias dictadas.</p>
    <button class="boton">Ver cursos</button>
  </div>
  <div class="tarjeta">
    <h3>Reservas de aulas</h3>
    <p>Gestiona tus reservas de aulas y laboratorios.</p>
    <button class="boton">Ver reservas</button>
  </div>
  <div class="tarjeta">
    <h3>Perfil</h3>
    <p>Consulta tus datos y horarios.</p>
    <button class="boton">Mi perfil</button>
  </div>
</main>

<footer class="footer">
  &copy; 2025 Instituto Tecnológico Superior de Paysandú | Contacto: evolutionit2008@gmail.com
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Función para cerrar el formulario
  function cerrarFormulario() {
    document.getElementById("formularioReserva").style.display = "none";
  }

  // Mostrar/ocultar formulario (corregido y adaptado para .formulario)
  document.getElementById("btnReservar").addEventListener("click", function(e) {
    e.preventDefault();
    let formulario = document.getElementById("formularioReserva");
    if (formulario.style.display === "flex" || formulario.style.display === "") {
      formulario.style.display = "none";
    } else {
      formulario.style.display = "flex";
      formulario.scrollIntoView({ behavior: "smooth" });
    }
  });

  // Clases por turno
  const clasesPorTurno = {
    matutino: ["1°MC", "2°MA", "3°MA", "3°BA robótica"],
    vespertino: ["1°MA", "1°MB", "2°MC", "3°MC", "TC1 Diseño Gráfico"],
    nocturno: ["1°MA Nocturno", "2°MA Nocturno", "3°MA Nocturno"]
  };

  // Materias por clase
  const materiasPorClase = {
    "1°MA": ["Lengua", "Matemática", "Historia", "Biología", "Soporte"],
    "1°MB": ["Lengua", "Matemática", "Lógica", "Ciudadanía", "Programación"],
    "2°MC": ["Electrotecnia", "Física", "Inglés", "Programación"],
    "3°MC": ["Matemática", "Historia", "Inglés", "Programación"],
    "TC1 Diseño Gráfico": ["Diseño", "Multimedia", "Arte Digital"],
    "1°MA Nocturno": ["Lengua", "Historia", "Soporte"],
    "2°MA Nocturno": ["Programación", "Matemática", "Inglés"],
    "3°MA Nocturno": ["Redes", "Electrotecnia", "Física"]
  };

  // Horarios por turno
  const horariosPorTurno = {
    matutino: ["07:15", "07:55", "08:35", "09:15", "09:55", "10:35", "11:15", "11:55", "12:35", "13:15"],
    vespertino: ["13:45", "14:25", "15:05", "15:45", "16:25", "17:05", "17:45", "18:25", "19:05", "19:45"],
    nocturno: ["19:30", "20:10", "20:50", "21:30", "22:10", "22:50", "23:30"]
  };

  const turnoSelect = document.getElementById("turno");
  const claseSelect = document.getElementById("clase");
  const materiaSelect = document.getElementById("materia");
  const horaInicioSelect = document.getElementById("horaInicio");
  const cantidadHorasSelect = document.getElementById("cantidadHoras");
  const horaFinInput = document.getElementById("horaFin");

  turnoSelect.addEventListener("change", function() {
    const turno = this.value;
    claseSelect.innerHTML = '<option selected disabled value="">Elija...</option>';
    horaInicioSelect.innerHTML = '<option selected disabled value="">Elija...</option>';

    if (clasesPorTurno[turno]) {
      clasesPorTurno[turno].forEach(clase => {
        let option = document.createElement("option");
        option.textContent = clase;
        option.value = clase;
        claseSelect.appendChild(option);
      });
    }

    if (horariosPorTurno[turno]) {
      horariosPorTurno[turno].forEach(hora => {
        let option = document.createElement("option");
        option.textContent = hora;
        option.value = hora;
        horaInicioSelect.appendChild(option);
      });
    }
  });

  claseSelect.addEventListener("change", function() {
    const clase = this.value;
    materiaSelect.innerHTML = '<option selected disabled value="">Elija...</option>';
    if (materiasPorClase[clase]) {
      materiasPorClase[clase].forEach(materia => {
        let option = document.createElement("option");
        option.textContent = materia;
        option.value = materia;
        materiaSelect.appendChild(option);
      });
    }
  });

  function calcularHoraFin() {
    const turno = turnoSelect.value;
    const inicio = horaInicioSelect.value;
    const cantidad = parseInt(cantidadHorasSelect.value);

    if (!turno || !inicio || !cantidad) { horaFinInput.value = ""; return; }

    const horas = horariosPorTurno[turno];
    const indexInicio = horas.indexOf(inicio);
    if (indexInicio === -1) return;

    const indexFin = indexInicio + cantidad - 1;
    horaFinInput.value = (indexFin < horas.length) ? horas[indexFin] : "Fuera de rango";
  }

  horaInicioSelect.addEventListener("change", calcularHoraFin);
  cantidadHorasSelect.addEventListener("change", calcularHoraFin);
</script>
</body>
</html>