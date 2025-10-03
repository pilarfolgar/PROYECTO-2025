document.getElementById("rol").addEventListener("change", function () {
  const datosEstudiante = document.getElementById("datosEstudiante");
  if (this.value === "estudiante") {
    datosEstudiante.classList.remove("d-none");
  } else {
    datosEstudiante.classList.add("d-none");
  }
});