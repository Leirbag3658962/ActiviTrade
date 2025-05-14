const dropZones = document.querySelectorAll(".Cells");

dropZones.forEach(zone => {
  const fileInput = zone.querySelector(".ImageInput"); 
  const preview = zone.querySelector(".ImageActivite");
  const text = zone.querySelector(".Paragraph");

  
  zone.addEventListener("click", () => fileInput.click());

  
  fileInput.addEventListener("change", (event) => {
    if (event.target.files && event.target.files[0]) {
      handleFile(event.target.files[0], preview, text, zone, fileInput); // Passer fileInput ici aussi
    }
  });

  
  ["dragover", "dragleave", "drop"].forEach(event => {
    zone.addEventListener(event, (e) => {
      e.preventDefault(); 
      e.stopPropagation(); 
    });
  });

  zone.addEventListener("dragover", () => {
    zone.classList.add("dragover"); 
  });

  zone.addEventListener("dragleave", () => {
    zone.classList.remove("dragover"); 
  });

  
  zone.addEventListener("drop", (event) => {
    zone.classList.remove("dragover");
    

    if (event.dataTransfer.files && event.dataTransfer.files[0]) {
      const file = event.dataTransfer.files[0];

      const dataTransfer = new DataTransfer();
      dataTransfer.items.add(file);
      fileInput.files = dataTransfer.files;
      

      handleFile(file, preview, text, zone, fileInput); 
    }
  });
});


function handleFile(file, previewElement, textElement, zone, inputElement) { // inputElement ajouté en paramètre
  if (file && file.type.startsWith("image/")) {

    const reader = new FileReader();
    reader.onload = (e) => {
      previewElement.src = e.target.result;
      previewElement.style.display = "block";
      previewElement.style.width = "100%"; 
      previewElement.style.height = "100%";
      textElement.style.display = "none";
      zone.style.border = "none"; 
    };
    reader.readAsDataURL(file);

  } else if (file) { 
    alert("Veuillez déposer un fichier image (jpg, png, gif, etc.).");
    
    if (inputElement) {
        inputElement.value = ""; 
    }
    
    previewElement.src = "";
    previewElement.style.display = "none";
    textElement.style.display = "block";
    zone.style.border = "2px dashed #ccc"; 
  }
}