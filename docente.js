// ==============================
// Ver miembros (AJAX dinámico)
// ==============================
document.querySelectorAll('.ver-miembros').forEach(btn => {
  btn.addEventListener('click', async () => {
    const grupoId = btn.dataset.grupo;
    const ul = document.getElementById('miembros-' + grupoId);

    if (ul.style.display === 'block') {
      ul.style.display = 'none';
      btn.textContent = 'Ver miembros';
      return;
    }

    btn.textContent = 'Cargando...';
    try {
      const res = await fetch(`indexdocente.php?ajax=miembros&id_grupo=${grupoId}`);
      const data = await res.json();
      ul.innerHTML = '';

      if (data.length > 0) {
        data.forEach(m => {
          const li = document.createElement('li');
          li.textContent = `${m.nombrecompleto}`;
          ul.appendChild(li);
        });
      } else {
        ul.innerHTML = '<li class="text-muted">Sin estudiantes registrados</li>';
      }
      ul.style.display = 'block';
      btn.textContent = 'Ocultar miembros';
    } catch (error) {
      console.error(error);
      btn.textContent = 'Error';
    }
  });
});

// ==============================
// Reservar bloque (demo)
// ==============================
function abrirReservaBloque(td) {
  const aula = td.dataset.aula;
  const hora = td.dataset.hora;
  alert('Abrir modal para reservar aula ' + aula + ' a las ' + hora);
}

function filtrar(categoria) {
    document.querySelectorAll('.boton-filtro').forEach(btn => btn.classList.remove('active'));
    const boton = document.getElementById(`filtro-${categoria}`);
    if (boton) boton.classList.add('active');

    document.querySelectorAll('.espacio').forEach(el => {
        el.style.display = (categoria === 'todo' || el.classList.contains(categoria)) ? 'block' : 'none';
    });
}

function mostrarImagen(img) {
    alert('Imagen: ' + img.alt);
}

function abrirReserva(idAula, nombreAula) {
    document.getElementById('tituloReserva').innerText = `Reservar - ${nombreAula}`;
    document.getElementById('idAulaSeleccionada').value = idAula;
    document.getElementById('aulaSeleccionada').value = nombreAula;
    const modal = new bootstrap.Modal(document.getElementById('modalReserva'));
    modal.show();
}
function abrirModalNotificacion(){
    const modal = new bootstrap.Modal(document.getElementById('modalNotificacion'));
    modal.show();
}

document.addEventListener("DOMContentLoaded", () => {
    const formNotificacion = document.getElementById("formNotificacion");

    formNotificacion.addEventListener("submit", function(e) {
        e.preventDefault(); // Evita que la página recargue

        const formData = new FormData(formNotificacion);

        fetch("enviar-notificacion.php", { // archivo que procesará la notificación
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.success){
                alert("Notificación enviada correctamente");
                formNotificacion.reset();
                // Cierra el modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalNotificacion'));
                modal.hide();
            } else {
                alert("Error: " + data.message);
            }
        })
        .catch(err => {
            console.error(err);
            alert("Ocurrió un error al enviar la notificación.");
        });
    });
});

document.addEventListener("DOMContentLoaded", () => {
  const botones = document.querySelectorAll(".enviar-notificacion");
  botones.forEach(btn => {
    btn.addEventListener("click", () => {
      const idGrupo = btn.getAttribute("data-grupo");
      document.getElementById("noti_id_grupo").value = idGrupo;
      const modal = new bootstrap.Modal(document.getElementById("modalNotificacion"));
      modal.show();
    });
  });
});
// docente.js
document.addEventListener('DOMContentLoaded', function() {
    const btnAbrir = document.getElementById('btnAbrirReporte');
    const overlay = document.getElementById('overlayReporte');
    const formReporte = document.getElementById('form-reporte');
    const btnCerrar = document.getElementById('btnCerrarReporte');

    btnAbrir.addEventListener('click', () => {
        overlay.style.display = 'block';
        formReporte.style.display = 'block';
    });

    btnCerrar.addEventListener('click', () => {
        overlay.style.display = 'none';
        formReporte.style.display = 'none';
    });

    overlay.addEventListener('click', () => {
        overlay.style.display = 'none';
        formReporte.style.display = 'none';
    });
});
