// Filtrar espacios
function filtrar(categoria) {
    document.querySelectorAll('.boton-filtro').forEach(btn => btn.classList.remove('active'));
    const boton = document.getElementById(`filtro-${categoria}`);
    if (boton) boton.classList.add('active');

    document.querySelectorAll('.espacio').forEach(el => {
        el.style.display = (categoria === 'todo' || el.classList.contains(categoria)) ? 'block' : 'none';
    });
}

// Mostrar imagen en modal
function mostrarImagen(img) {
    document.getElementById('imagenAmpliada').src = img.src;
}

function abrirReserva(idAula, nombreAula) {
    document.getElementById('tituloReserva').innerText = `Reservar - ${nombreAula}`;
    document.getElementById('idAulaSeleccionada').value = idAula;
    document.getElementById('aulaSeleccionada').value = nombreAula;
    const modal = new bootstrap.Modal(document.getElementById('modalReserva'));
    modal.show();
}

// Enviar reserva al servidor
document.getElementById('formReserva').addEventListener('submit', function(e){
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    const mensaje = document.getElementById('mensajeReserva');

    fetch('guardar_reserva.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            mensaje.innerHTML = `<span class="text-success">${data.message}</span>`;
            form.reset();
            setTimeout(()=>bootstrap.Modal.getInstance(document.getElementById('modalReserva')).hide(), 1500);
        } else {
            mensaje.innerHTML = `<span class="text-danger">${data.message}</span>`;
        }
    })
    .catch(err => {
        console.error(err);
        mensaje.innerHTML = `<span class="text-danger">Error de conexión</span>`;
    });
});
