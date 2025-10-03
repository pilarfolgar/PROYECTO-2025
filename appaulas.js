
function filtrar(categoria) {
  let elementos = document.querySelectorAll('.espacio');
  elementos.forEach(el => {
    if (categoria === 'todo' || el.classList.contains(categoria)) {
      el.style.display = 'block';
    } else {
      el.style.display = 'none';
    }
  });
}

function mostrarImagen(img) {
  document.getElementById('imagenAmpliada').src = img.src;
}

function abrirReserva(nombreEspacio) {
  document.getElementById('tituloReserva').innerText = "Reservar - " + nombreEspacio;
  let modal = new bootstrap.Modal(document.getElementById('modalReserva'));
  modal.show();
}

document.getElementById('formReserva').addEventListener('submit', function(e) {
  e.preventDefault();
  alert("Reserva confirmada ✅");
  bootstrap.Modal.getInstance(document.getElementById('modalReserva')).hide();
});
