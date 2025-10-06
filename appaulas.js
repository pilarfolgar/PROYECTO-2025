
function filtrar(categoria) {
  // Resalta el botón activo
  document.querySelectorAll('.boton-filtro').forEach(btn => btn.classList.remove('active'));
  if(categoria==='todo') document.getElementById('filtro-todo').classList.add('active');
  if(categoria==='aula') document.getElementById('filtro-aula').classList.add('active');
  if(categoria==='salon') document.getElementById('filtro-salon').classList.add('active');
  if(categoria==='lab') document.getElementById('filtro-lab').classList.add('active');

  // Filtrado de espacios
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

// La función abrirReserva ahora está definida en aulas.php

document.getElementById('formReserva').addEventListener('submit', function(e) {
  e.preventDefault();
  alert("Reserva confirmada ✅");
  bootstrap.Modal.getInstance(document.getElementById('modalReserva')).hide();
});
