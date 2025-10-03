// Evento para focus en primer input al abrir modal
const modalReserva = document.getElementById('modalReserva');
modalReserva.addEventListener('shown.bs.modal', function () {
  document.getElementById('nombre').focus();  // Focus automático en nombre
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

  if (!turno || !inicio || !cantidad) { 
    horaFinInput.value = ""; 
    return; 
  }

  const horas = horariosPorTurno[turno];
  const indexInicio = horas.indexOf(inicio);
  if (indexInicio === -1) return;

  const indexFin = indexInicio + cantidad - 1;
  horaFinInput.value = (indexFin < horas.length) ? horas[indexFin] : "Fuera de rango";
}

horaInicioSelect.addEventListener("change", calcularHoraFin);
cantidadHorasSelect.addEventListener("change", calcularHoraFin);