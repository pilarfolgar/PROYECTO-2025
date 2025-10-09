// appaulas.js

// Función para filtrar espacios según categoría
function filtrar(categoria) {
    // Actualiza el botón activo
    document.querySelectorAll('.boton-filtro').forEach(btn => btn.classList.remove('active'));
    const boton = document.getElementById(`filtro-${categoria}`);
    if (boton) boton.classList.add('active');

    // Muestra u oculta tarjetas según la categoría
    document.querySelectorAll('.espacio').forEach(el => {
        el.style.display = (categoria === 'todo' || el.classList.contains(categoria)) ? 'block' : 'none';
    });
}

// Función para mostrar imagen ampliada en el modal
function mostrarImagen(img) {
    const imagenModal = document.getElementById('imagenAmpliada');
    imagenModal.src = img.src;
}

// Función para abrir el modal de reserva con el aula seleccionada
function abrirReserva(nombreEspacio) {
    document.getElementById('tituloReserva').innerText = `Reservar - ${nombreEspacio}`;
    document.getElementById('aulaSeleccionada').value = nombreEspacio;
    const modal = new bootstrap.Modal(document.getElementById('modalReserva'));
    modal.show();
}

// Cierre del modal de reserva y confirmación
document.getElementById('formReserva').addEventListener('submit', function(e) {
    e.preventDefault();
    alert("Reserva confirmada ✅");
    bootstrap.Modal.getInstance(document.getElementById('modalReserva')).hide();
});
