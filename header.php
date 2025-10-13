<header class="header-simple">
  <div class="logo-text">
    <img src="imagenes/logopoyecto.png" alt="Logo" class="logo">
    <div>
      <h2>InfraLex</h2>
      <h5>Instituto Tecnológico Superior de Paysandú</h5>
    </div>
  </div>

  <nav class="nav-simple">
    <a href="indexdocente.php">Inicio</a>
    <a href="aulas.php">Aulas</a>
    <a href="docentes.php">Docentes</a>
    <a href="contacto.php">Contacto</a>
  </nav>
</header>

<style>
/* Header principal */
.header-simple {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.7rem 1.5rem;
  background-color: #f8f9fa;
  border-bottom: 1px solid #ddd;
  position: sticky;
  top: 0;
  z-index: 1000;
  flex-wrap: wrap;
  gap: 10px;
}

/* Logo y texto */
.logo-text {
  display: flex;
  align-items: center;
  gap: 10px;
}
.logo-text .logo { height: 50px; }
.logo-text h2 { margin: 0; font-size: 1.2rem; }
.logo-text h5 { margin: 0; font-size: 0.8rem; font-weight: 400; color: #555; }

/* Navegación */
.nav-simple {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
}
.nav-simple a {
  text-decoration: none;
  color: #121212;
  font-weight: 500;
  padding: 5px 10px;
  border-radius: 4px;
  transition: background 0.3s, color 0.3s;
}
.nav-simple a:hover {
  background-color: #007bff;
  color: #fff;
}

/* Responsive: menú colapsa en móvil */
@media (max-width: 768px) {
  .header-simple {
    flex-direction: column;
    align-items: flex-start;
  }
  .nav-simple {
    width: 100%;
    justify-content: space-around;
  }
}
</style>
