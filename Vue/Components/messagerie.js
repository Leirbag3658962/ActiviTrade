let currentContact = null;

function toggleMessagerie() {
  const box = document.getElementById("messagerie");
  const isOpen = box.style.display === "flex";
  box.style.display = isOpen ? "none" : "flex";
  if (!isOpen) loadContactHistory(); // charger l'historique au moment d'ouvrir
  else resetInterface();
}


function resetInterface() {
  currentContact = null;
  document.getElementById("messages").innerHTML = "";
  document.getElementById("contactInfo").innerHTML = "";
  document.getElementById("messageInput").value = "";
  document.getElementById("messageInput").disabled = true;
  document.querySelector("#messagerie button.send").disabled = true;
}

function searchUser() {
  const input = document.getElementById("idSearch").value.trim();
  if (!input) return alert("Entrez un ID ou une adresse email valide");

  fetch(`?info=1&search=${encodeURIComponent(input)}`)
    .then(res => res.json())
    .then(data => {
      if (!data.success) {
        alert(data.error || "Utilisateur non trouvé");
        return;
      }

      currentContact = data.id;
      document.getElementById("contactInfo").innerHTML = `
        <strong>${data.nom}</strong> - ${data.email}
      `;

      document.getElementById("messageInput").disabled = false;
      document.querySelector("#messagerie button.send").disabled = false;
      fetchMessages();
    })
    .catch(() => alert("Erreur lors de la recherche"));
}


function fetchMessages() {
    fetch(`?contact=${currentContact}`)
        .then(res => res.json())
        .then(data => {
            const box = document.getElementById("messages");
            box.innerHTML = "";
            data.forEach(msg => {
                const div = document.createElement("div");
                div.classList.add("message", msg.sender === "Moi" ? "me" : "other");
                div.textContent = msg.text;
                box.appendChild(div);
            });
            box.scrollTop = box.scrollHeight;
        })
        .catch(() => alert("Erreur lors de la récupération des messages"));
}

function sendMessage() {
  const input = document.getElementById("messageInput");
  const text = input.value.trim();
  if (text === "") return;

  fetch('?action=send', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ contact: currentContact, text })
  }).then(() => {
    input.value = "";
    fetchMessages();
  });
}
// Fonction pour charger l'historique des contacts
function loadContactHistory() {
    fetch('?history=1')
        .then(res => res.json())
        .then(data => {
            const ul = document.getElementById("contactHistory");
            ul.innerHTML = "";
            data.forEach(contact => {
                const li = document.createElement("li");
                li.textContent = `${contact.nom} (${contact.email})`;
                li.onclick = () => {
                    document.getElementById("idSearch").value = contact.email;
                    searchUser(); // relance la recherche pour charger les messages
                };
                ul.appendChild(li);
            });
        })
        .catch(() => console.error("Erreur chargement de l'historique"));
}

document.getElementById("messageInput").addEventListener("keypress", function (e) {
  if (e.key === "Enter") {
    e.preventDefault();
    sendMessage();
  }
});
