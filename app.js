document.addEventListener('DOMContentLoaded', () => {
      const container = document.getElementById('formContainer');
      document.getElementById('loginLink').addEventListener('click', e => {
        e.preventDefault();
        container.innerHTML = `
          <div class="card mx-auto" style="max-width: 400px;">
            <div class="card-body">
              <h5 class="card-title">Iniciar Sesión</h5>
              <form>
                <div class="mb-3">
                  <label class="form-label">Usuario</label>
                  <input type="text" class="form-control" placeholder="Nombre de usuario" />
                </div>
                <div class="mb-3">
                  <label class="form-label">Contraseña</label>
                  <input type="password" class="form-control" placeholder="Contraseña" />
                </div>
                <button type="submit" class="btn btn-primary w-100">Entrar</button>
              </form>
            </div>
          </div>
        `;
      });
      document.getElementById('registerLink').addEventListener('click', e => {
        e.preventDefault();
        container.innerHTML = `
          <div class="card mx-auto" style="max-width: 600px;">
            <div class="card-body">
              <h5 class="card-title">Registro</h5>
              <form id="registerForm">
                <div class="row g-3">
                  <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Nombre" required />
                  </div>
                  <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Apellido" required />
                  </div>
                  <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Rol" required />
                  </div>
                  <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Dirección" required />
                  </div>
                  <div class="col-md-6">
                    <input type="email" class="form-control" placeholder="Correo Electrónico" required />
                  </div>
                  <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Cédula de Identidad" required />
                  </div>
                  <div class="col-md-6">
                    <input type="date" class="form-control" placeholder="Fecha de Nacimiento" required />
                  </div>
                </div>
                <button type="submit" class="btn btn-success w-100 mt-3">Registrar</button>
              </form>
            </div>
          </div>
        `;
      });
    });


    document.addEventListener("DOMContentLoaded", function () {
  const rolSelect = document.getElementById("rol");
  const claseContainer = document.getElementById("claseContainer");

  // Mostrar/Ocultar campo clase
  if (rolSelect) {
    rolSelect.addEventListener("change", function () {
      if (this.value === "estudiante") {
        claseContainer.classList.remove("d-none");
      } else {
        claseContainer.classList.add("d-none");
      }
    });
  }

  // Abrir modal de login
  document.getElementById("loginLink").addEventListener("click", function (e) {
    e.preventDefault();
    const modal = new bootstrap.Modal(document.getElementById("loginModal"));
    modal.show();
  });

  // Abrir modal de registro
  document.getElementById("registerLink").addEventListener("click", function (e) {
    e.preventDefault();
    const modal = new bootstrap.Modal(document.getElementById("registerModal"));
    modal.show();
  });
});
const usuario = {nombre, email, password, cedula, turno, curso, rol, clase};
  localStorage.setItem("usuario", JSON.stringify(usuario));

  // Redirigir según rol
  if(rol === "estudiante") {
    window.location.href = "estudiantes.html";
  } else {
    window.location.href = "indexregistrado.html";
  }

document.getElementById("rol").addEventListener("change", function () {
  const claseContainer = document.getElementById("claseContainer");
  if (this.value === "estudiante") {
    claseContainer.classList.remove("d-none");
  } else {
    claseContainer.classList.add("d-none");
  }
});

// Mostrar u ocultar selector de clase
document.getElementById("rol").addEventListener("change", function () {
  const claseContainer = document.getElementById("claseContainer");
  if (this.value === "estudiante") {
    claseContainer.classList.remove("d-none");
  } else {
    claseContainer.classList.add("d-none");
  }
});

function login() {
  const emailLogin = document.getElementById("emailLogin").value;
  const passwordLogin = document.getElementById("passwordLogin").value;
  const usuario = JSON.parse(localStorage.getItem("usuario"));

  if(usuario && usuario.email === emailLogin && usuario.password === passwordLogin){
    if(usuario.rol === "estudiante") {
      window.location.href = "estudiantes.html";
    } else {
      window.location.href = "indexregistrado.html";
    }
  } else {
    alert("Usuario o contraseña incorrectos");
  }
}
