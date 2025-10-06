<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Panel Docentes - InfraLex</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="styleindexdocente.css" />
</head>
<body>

<header>
  <div class="header-left">
    <h1>InfraLex</h1>
    <h6>Instituto Tecnológico Superior de Paysandú</h6>
  </div>
</header>

<nav class="main-nav">
  <a href="#" class="nav-link">Mis cursos</a>
  <a href="#" class="nav-link" id="btnReservar">Reservar aulas</a>
  <a href="usuarios.html" class="nav-link">Mi Perfil</a>
</nav>

<!-- FORMULARIO RESERVA -->
<div id="formularioReserva" class="formulario">
  <form id="formReserva">
    <button type="button" class="cerrar" onclick="cerrarFormulario()">&times;</button>
    <h2 class="form-title">Reservar Aula</h2>

    <div class="mb-3">
      <label>Nombre</label>
      <input type="text" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>CI</label>
      <input type="text" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Turno</label>
      <select class="form-select" id="turno" required>
        <option selected disabled>Elija...</option>
        <option>Matutino</option>
        <option>Vespertino</option>
        <option>Nocturno</option>
      </select>
    </div>

    <div class="mb-3">
      <label>Aula</label>
      <select class="form-select" id="aula" required>
        <option selected disabled>Elija...</option>
      </select>
    </div>

    <div class="mb-3">
      <label>Hora de inicio</label>
      <select class="form-select" id="horaInicio" required>
        <option selected disabled>Primero elija un turno...</option>
      </select>
    </div>

    <div class="mb-3">
      <label>Cantidad de horas</label>
      <select class="form-select" id="cantidadHoras" required>
        <option selected disabled>Elija...</option>
        <option value="1">1 hora</option>
        <option value="2">2 horas</option>
        <option value="3">3 horas</option>
        <option value="4">4 horas</option>
      </select>
    </div>

    <div class="mb-3">
      <label>Hora de fin</label>
      <input type="text" class="form-control" id="horaFin" readonly>
    </div>

    <button class="boton" type="submit">Reservar</button>
  </form>
</div>

<main class="contenedor">
  <section class="mis-cursos">
    <h2>Mis cursos</h2>
    <div class="docentes-grid">
      <div class="docente-card">
        <div class="docente-photo"></div>
        <div class="docente-name">1°MA - Lengua</div>
        <div class="docente-subject">Turno matutino </div>
        <button class="boton ver-miembros" data-clase="1°MA - Lengua">Ver miembros</button>
        <ul class="lista-miembros" style="display:none;"></ul>
      </div>
      <div class="docente-card">
        <div class="docente-photo"></div>
        <div class="docente-name">2°BB - Matemática</div>
        <div class="docente-subject">Turno vespertino </div>
        <button class="boton ver-miembros" data-clase="2°BB - Matemática">Ver miembros</button>
        <ul class="lista-miembros" style="display:none;"></ul>
      </div>
    </div>
  </section>

  <section>
    <h2>Mis reservas</h2>
    <div id="reservas-container">
      <div class="no-reservas">No hay reservas por el momento.</div>
    </div>
  </section>
</main>

<footer class="footer">
  &copy; 2025 Instituto Tecnológico Superior de Paysandú
</footer>

<script>
  const aulasDisponibles = ["Aula 1","Aula 2","Aula 3","Salón 1","Salón 2","Laboratorio Química","Laboratorio Física","Laboratorio Robótica"];
  const horariosTurno = {
    "Matutino":["08:00","09:00","10:00","11:00","12:00"],
    "Vespertino":["13:00","14:00","15:00","16:00","17:00"],
    "Nocturno":["18:00","19:00","20:00","21:00"]
  };

  // Miembros de cada clase
  const miembrosClases = {
    "1°MA - Lengua": ["Martina Campopiano", "Pedro Suárez", "Lucía Fernández", "Juan Gómez"],
    "2°BB - Matemática": ["Ana López", "Carlos Rodríguez", "María Torres"]
  };

  function cerrarFormulario() {
    document.getElementById("formularioReserva").style.display = "none";
  }

  document.getElementById("btnReservar").addEventListener("click", function(e){
    e.preventDefault();
    let f = document.getElementById("formularioReserva");
    f.style.display = (f.style.display === "flex" || f.style.display==="") ? "none" : "flex";
  });

  // Cuando se selecciona un turno, actualizamos aulas y horarios
  const turnoSelect = document.getElementById("turno");
  const aulaSelect = document.getElementById("aula");
  const horaInicioSelect = document.getElementById("horaInicio");
  const cantidadHorasSelect = document.getElementById("cantidadHoras");
  const horaFinInput = document.getElementById("horaFin");

  turnoSelect.addEventListener("change", () => {
    // Limpiar y cargar horarios
    horaInicioSelect.innerHTML = '<option selected disabled>Elija hora...</option>';
    horariosTurno[turnoSelect.value].forEach(h => {
      const opt = document.createElement("option");
      opt.value = h; opt.textContent = h;
      horaInicioSelect.appendChild(opt);
    });

    // Limpiar y cargar aulas dinámicamente
    aulaSelect.innerHTML = '<option selected disabled>Elija...</option>';
    aulasDisponibles.forEach(a => {
      const opt = document.createElement("option");
      opt.value = a; opt.textContent = a;
      aulaSelect.appendChild(opt);
    });
  });

  // Calcular hora fin automáticamente
  function calcularHoraFin() {
    const inicio = horaInicioSelect.value;
    const horas = parseInt(cantidadHorasSelect.value);
    if(inicio && horas) {
      let [h,m] = inicio.split(":").map(Number);
      h += horas;
      if(h>=24) h -= 24;
      horaFinInput.value = `${h.toString().padStart(2,"0")}:${m.toString().padStart(2,"0")}`;
    }
  }

  horaInicioSelect.addEventListener("change", calcularHoraFin);
  cantidadHorasSelect.addEventListener("change", calcularHoraFin);

  // Simulación de reservas
  const reservasContainer = document.getElementById("reservas-container");
  const reservas = [
    { aula: "Aula 1", fecha: "2025-10-01", turno: "Matutino", docente: "Juan Pérez", inicio:"08:00", fin:"09:00" },
    { aula: "Aula 2", fecha: "2025-10-02", turno: "Vespertino", docente: "Ana López", inicio:"13:00", fin:"15:00" }
  ];

  function renderReservas() {
    reservasContainer.innerHTML = "";
    if(reservas.length === 0){
      reservasContainer.innerHTML = '<div class="no-reservas">No hay reservas por el momento.</div>';
      return;
    }
    reservas.forEach(r=>{
      const card = document.createElement("div");
      card.className = "reserva-card";
      card.innerHTML = `<h4>${r.aula}</h4>
                        <p><strong>Fecha:</strong> ${r.fecha}</p>
                        <p><strong>Turno:</strong> ${r.turno}</p>
                        <p><strong>Hora:</strong> ${r.inicio} - ${r.fin}</p>
                        <p><strong>Docente:</strong> ${r.docente}</p>`;
      reservasContainer.appendChild(card);
    });
  }
  renderReservas();

  // Ver miembros de cada curso
  document.querySelectorAll(".ver-miembros").forEach(btn => {
    btn.addEventListener("click", () => {
      const clase = btn.getAttribute("data-clase");
      const lista = btn.parentElement.querySelector(".lista-miembros");

      if(lista.style.display === "none" || lista.style.display===""){
        lista.innerHTML = "";
        miembrosClases[clase]?.forEach(m => {
          const li = document.createElement("li");
          li.textContent = m;
          lista.appendChild(li);
        });
        lista.style.display = "block";
        btn.textContent = "Ocultar miembros";
      } else {
        lista.style.display = "none";
        btn.textContent = "Ver miembros";
      }
    });
  });
</script>
</body>
</html>