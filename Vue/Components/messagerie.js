let currentContact = null;
let previousMessageCount = 0;

function toggleMessagerie() {
  const box = document.getElementById("messagerie");
  const isOpen = box.style.display === "flex";
  box.style.display = isOpen ? "none" : "flex";
  if (!isOpen) loadContactHistory(); // charger l'historique au moment d'ouvrir
  else resetInterface();
}

function resetInterface() {
  currentContact = null;
  previousMessageCount = 0;
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
      previousMessageCount = 0; // reset message count
      document.getElementById("contactInfo").innerHTML = `
        <strong>${data.nom}</strong> - ${data.email}
        <span id="newMsgNotif" style="color: red; display: none;">🔔 Nouveau message !</span>
      `;

      document.getElementById("messageInput").disabled = false;
      document.querySelector("#messagerie button.send").disabled = false;
      fetchMessages();
      startMessagePolling(); // démarre la vérification périodique
    })
    .catch(() => alert("Erreur lors de la recherche"));
}

function fetchMessages() {
  fetch(`?contact=${currentContact}`)
    .then(res => res.json())
    .then(data => {
      const box = document.getElementById("messages");

      if (data.length > previousMessageCount && previousMessageCount !== 0) {
        // Afficher une notification visuelle
        const notif = document.getElementById("newMsgNotif");
        if (notif) notif.style.display = "inline";
        setTimeout(() => {
          if (notif) notif.style.display = "none";
        }, 3000);
      }

      previousMessageCount = data.length;

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
          searchUser();
        };
        ul.appendChild(li);
      });
    })
    .catch(() => console.error("Erreur chargement de l'historique"));
}

// Vérification périodique des messages
let messagePollingInterval = null;
function startMessagePolling() {
  if (messagePollingInterval) clearInterval(messagePollingInterval);
  messagePollingInterval = setInterval(() => {
    if (currentContact !== null) {
      fetchMessages();
    }
  }, 5000); // toutes les 5 secondes
}

document.getElementById("messageInput").addEventListener("keypress", function (e) {
  if (e.key === "Enter") {
    e.preventDefault();
    sendMessage();
  }
});
