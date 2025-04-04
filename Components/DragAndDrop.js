const dropZones = document.querySelectorAll(".Cells");

dropZones.forEach(zone => {
  const fileInput = zone.querySelector(".ImageInput");
  const preview = zone.querySelector(".ImageActivite");
  const text = zone.querySelector(".Paragraph")

  // Clique sur la zone => clique sur input
  zone.addEventListener("click", () => fileInput.click());

  // Changement de fichier
  fileInput.addEventListener("change", (event) => {
    handleFile(event.target.files[0], preview, text);
  });

  // Empêche le comportement par défaut (ouvrir le fichier)
  ["dragover", "dragleave", "drop"].forEach(event => {
    zone.addEventListener(event, (e) => e.preventDefault());
  });

  // Changement de fond au survol
  zone.addEventListener("dragover", () => {
    zone.style.backgroundColor = "#e3e3e3";
  });

  zone.addEventListener("dragleave", () => {
    zone.style.backgroundColor = "#f9f9f9";
  });

  // Quand un fichier est déposé
  zone.addEventListener("drop", (event) => {
    zone.style.backgroundColor = "#f9f9f9";
    const file = event.dataTransfer.files[0];
    handleFile(file, preview, text);
  });
});

// Fonction pour afficher l'image
function handleFile(file, previewElement) {
  if (file && file.type.startsWith("image/")) {
    const reader = new FileReader();
    reader.onload = (e) => {
      previewElement.src = e.target.result;
      previewElement.style.display = "block";
      text.style.display = "none";
    };
    reader.readAsDataURL(file);
  }
}