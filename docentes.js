

  const aulasDisponibles = ["Aula 1","Aula 2","Aula 3","Salón 1","Salón 2","Laboratorio Química","Laboratorio Física","Laboratorio Robótica"];
  const horariosTurno = {
    "Matutino":["08:00","09:00","10:00","11:00","12:00"],
    "Vespertino":["13:00","14:00","15:00","16:00","17:00"],
    "Nocturno":["18:00","19:00","20:00","21:00"]
  };

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

  const turnoSelect = document.getElementById("turno");
  const aulaSelect = document.getElementById("aula");
  const horaInicioSelect = document.getElementById("horaInicio");
  const cantidadHorasSelect = document.getElementById("cantidadHoras");
  const horaFinInput = document.getElementById("horaFin");

  turnoSelect.addEventListener("change", () => {
    horaInicioSelect.innerHTML = '<option selected disabled>Elija hora...</option>';
    horariosTurno[turnoSelect.value].forEach(h => {
      const opt = document.createElement("option");
      opt.value = h; opt.textContent = h;
      horaInicioSelect.appendChild(opt);
    });

    aulaSelect.innerHTML = '<option selected disabled>Elija...</option>';
    aulasDisponibles.forEach(a => {
      const opt = document.createElement("option");
      opt.value = a; opt.textContent = a;
      aulaSelect.appendChild(opt);
    });
  });

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

  flatpickr("#fechaReserva", {
    dateFormat: "Y-m-d",
    minDate: "today",
    locale: { firstDayOfWeek: 1 },
    disableMobile: true
  });

  

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








document.addEventListener('DOMContentLoaded', () => {
  const menuBtn = document.getElementById('menu-btn');
  const menuDropdown = document.getElementById('menu-dropdown');
  const themeToggle = document.getElementById('toggle-theme');

  if(menuBtn && menuDropdown) {
    menuBtn.addEventListener('click', () => {
      menuBtn.classList.toggle('active');
      menuDropdown.classList.toggle('show');
    });
  }

  if(themeToggle) {
    themeToggle.addEventListener('click', (e) => {
      e.preventDefault();
      document.body.classList.toggle('dark-mode');
      themeToggle.textContent = document.body.classList.contains('dark-mode') 
        ? '☀️ Modo claro' 
        : '🌙 Modo oscuro';
    });
  }
});

