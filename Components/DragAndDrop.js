const dropZones = document.querySelectorAll(".Cells");

dropZones.forEach(zone => {
  const fileInput = zone.querySelector(".ImageInput");
  const preview = zone.querySelector(".ImageActivite");
  const text = zone.querySelector(".Paragraph")

  
  zone.addEventListener("click", () => fileInput.click());

  
  fileInput.addEventListener("change", (event) => {
    handleFile(event.target.files[0], preview, text, zone);
  });

  
  ["dragover", "dragleave", "drop"].forEach(event => {
    zone.addEventListener(event, (e) => e.preventDefault());
  });

  
  zone.addEventListener("dragover", () => {
    zone.style.backgroundColor = "#e3e3e3";
  });

  zone.addEventListener("dragleave", () => {
    zone.style.backgroundColor = "#f9f9f9";
  });

  
  zone.addEventListener("drop", (event) => {
    zone.style.backgroundColor = "#f9f9f9";

    const file = event.dataTransfer.files[0];
    handleFile(file, preview, text, zone);
  });
});


function handleFile(file, previewElement, textElement, zone) {
  if (file && file.type.startsWith("image/")) {
    const reader = new FileReader();
    reader.onload = (e) => {
      previewElement.src = e.target.result;
      previewElement.style.display = "block";
      previewElement.style.width = "100%";
      previewElement.style.height = "150px";
      textElement.style.display = "none";
      zone.style.border = "none";
    };
    reader.readAsDataURL(file);
  }
}