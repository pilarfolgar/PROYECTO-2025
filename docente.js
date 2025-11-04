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
// ==============================
// FORMULARIO DE REPORTES
// ==============================
document.addEventListener('DOMContentLoaded', function() {
    const btnAbrir = document.getElementById('btnAbrirReporte');
    const overlay = document.getElementById('overlayReporte');
    const formReporte = document.getElementById('form-reporte');
    const btnCerrar = document.getElementById('btnCerrarReporte');

    // Abrir formulario
    btnAbrir.addEventListener('click', () => {
        overlay.classList.add('visible');
        formReporte.classList.add('visible');
    });

    // Cerrar formulario (botón X)
    btnCerrar.addEventListener('click', () => {
        overlay.classList.remove('visible');
        formReporte.classList.remove('visible');
    });

    // Cerrar formulario al hacer clic fuera
    overlay.addEventListener('click', () => {
        overlay.classList.remove('visible');
        formReporte.classList.remove('visible');
    });
});
// ==============================
// Envío del formulario de reporte con SweetAlert2
// ==============================
document.addEventListener("DOMContentLoaded", () => {
    const formReporte = document.getElementById("reporteForm");

    if (!formReporte) return; // Si no existe el form, no hace nada

    formReporte.addEventListener("submit", async (e) => {
        e.preventDefault();

        // Validación HTML5/Bootstrap
        if (!formReporte.checkValidity()) {
            formReporte.classList.add("was-validated");
            return;
        }

        // Mostrar alerta de carga
        Swal.fire({
            title: "Enviando reporte...",
            text: "Por favor espera unos segundos",
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const formData = new FormData(formReporte);

        try {
            const res = await fetch("guardar-reporte-.php", {
                method: "POST",
                body: formData
            });

            if (!res.ok) throw new Error("Error en la solicitud");

            // Cierra el loader y muestra confirmación
            Swal.fire({
                icon: "success",
                title: "¡Reporte enviado!",
                text: "Tu reporte fue registrado correctamente.",
                confirmButtonColor: "#588BAE",
                confirmButtonText: "Aceptar"
            });

            // Limpia formulario y cierra modal
            formReporte.reset();
            document.getElementById("overlayReporte").classList.remove("visible");
            document.getElementById("form-reporte").classList.remove("visible");

        } catch (err) {
            console.error(err);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Ocurrió un problema al enviar el reporte. Intenta nuevamente.",
                confirmButtonColor: "#d33"
            });
        }
    });
});

