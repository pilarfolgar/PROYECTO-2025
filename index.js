
// Scroll reveal
const reveals = document.querySelectorAll('.reveal');
const revealObserver = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if(entry.isIntersecting) entry.target.classList.add('active');
  });
},{ threshold: 0.2 });
reveals.forEach(el => revealObserver.observe(el));

// Filtro docentes
const buscarInput = document.getElementById('buscar-docente');
buscarInput.addEventListener('input', () => {
  const value = buscarInput.value.toLowerCase();
  document.querySelectorAll('.docente-card').forEach(card => {
    const name = card.querySelector('.docente-name').textContent.toLowerCase();
    card.style.display = name.includes(value) ? 'flex' : 'none';
  });
});

// Carrusel pausable al hover
const carouselEl = document.getElementById('carouselExampleInterval');
const carouselInstance = bootstrap.Carousel.getOrCreateInstance(carouselEl);
carouselEl.addEventListener('mouseenter', () => carouselInstance.pause());
carouselEl.addEventListener('mouseleave', () => carouselInstance.cycle());
