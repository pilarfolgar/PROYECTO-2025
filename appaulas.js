// Función para filtrar espacios según categoría
function filtrar(categoria) {
    document.querySelectorAll('.boton-filtro').forEach(btn => btn.classList.remove('active'));
    const boton = document.getElementById(`filtro-${categoria}`);
    if (boton) boton.classList.add('active');

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

// Función para enviar la reserva a la base de datos
document.getElementById('formReserva').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this); // recoge todos los inputs automáticamente

    fetch('guardar_reserva.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("✅ Reserva confirmada correctamente");
            this.reset(); // limpia el formulario
            bootstrap.Modal.getInstance(document.getElementById('modalReserva')).hide();
        } else {
            alert("❌ Error al reservar: " + data.message);
        }
    })
    .catch(error => {
        console.error(error);
        alert("❌ Error de conexión o servidor");
    });
});
