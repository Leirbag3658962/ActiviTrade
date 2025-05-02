<?php
$pdo = new PDO('mysql:host=localhost;dbname=activititrade', 'root', '');

// Création des utilisateurs s'ils n'existent pas
$users = [
    1 => 'user@example.com',
    2 => 'alice@example.com',
    3 => 'bob@example.com',
    4 => 'charlie@example.com'
];
foreach ($users as $id => $email) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateur WHERE idUtilisateur = ?");
    $stmt->execute([$id]);
    if ($stmt->fetchColumn() == 0) {
        $pdo->prepare("INSERT INTO utilisateur (idUtilisateur, email, password) VALUES (?, ?, 'password')")->execute([$id, $email]);
    }
}

// Récupération des messages
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['contact'])) {
    $map = ["Alice" => 2, "Bob" => 3, "Charlie" => 4];
    $idMoi = 1;
    $idContact = $map[$_GET['contact']] ?? null;

    if ($idContact) {
        $stmt = $pdo->prepare("SELECT * FROM discussion WHERE (idUser1 = ? AND idUser2 = ?) OR (idUser1 = ? AND idUser2 = ?) ORDER BY date ASC");
        $stmt->execute([$idMoi, $idContact, $idContact, $idMoi]);
        $messages = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $messages[] = [
                "sender" => $row['idUser1'] == $idMoi ? "Moi" : $_GET['contact'],
                "text" => $row['contenu']
            ];
        }
        header('Content-Type: application/json');
        echo json_encode($messages);
        exit;
    }
}

// Insertion d’un message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'send') {
    $data = json_decode(file_get_contents('php://input'), true);
    $map = ["Alice" => 2, "Bob" => 3, "Charlie" => 4];
    $idMoi = 1;
    $idContact = $map[$data['contact']] ?? null;

    if ($idContact && isset($data['text'])) {
        $stmt = $pdo->prepare("INSERT INTO discussion (idUser1, idUser2, contenu, date) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$idMoi, $idContact, $data['text']]);
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false]);
    }
    exit;
}

// Insérer une seule discussion par défaut
$idUser1 = 1;
$idUser2 = 2;
$contenu = "Salut Alice, comment ça va ?";
$date = date('Y-m-d H:i:s');

$check = $pdo->prepare("SELECT * FROM discussion WHERE idUser1 = :u1 AND idUser2 = :u2 AND contenu = :contenu");
$check->execute(['u1' => $idUser1, 'u2' => $idUser2, 'contenu' => $contenu]);

if ($check->rowCount() == 0) {
    $insert = $pdo->prepare("INSERT INTO discussion (idUser1, idUser2, contenu, date) VALUES (:u1, :u2, :contenu, :date)");
    $insert->execute(['u1' => $idUser1, 'u2' => $idUser2, 'contenu' => $contenu, 'date' => $date]);
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Messagerie Multi-Contacts</title>
  <style>
    /* Styles du chat */
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f8f9fa;
    }

    .open-chat-btn {
      position: fixed;
      bottom: 30px;
      right: 20px;
      background-color: #355e3b;
      color: white;
      border: none;
      border-radius: 50%;
      width: 50px;
      height: 50px;
      font-size: 24px;
      cursor: pointer;
      z-index: 999;
    }

    .chat-overlay {
      position: fixed;
      bottom: 80px;
      right: 20px;
      width: 350px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
      display: none;
      flex-direction: column;
      z-index: 998;
    }

    .chat-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px;
      background: #355e3b;
      color: white;
      font-weight: bold;
      border-radius: 10px 10px 0 0;
    }

    .close-btn {
      cursor: pointer;
      font-size: 16px;
    }

    .chat-body {
      display: flex;
    }

    .contact-list {
      width: 100px;
      padding: 10px;
      border-right: 1px solid #ddd;
      display: flex;
      flex-direction: column;
      gap: 10px;
      background-color: #f1f1f1;
    }

    .contact {
      background-color: #ffffff;
      color: #333;
      padding: 8px;
      border-radius: 20px;
      text-align: center;
      cursor: pointer;
      font-weight: bold;
      border: 1px solid #ccc;
      transition: background-color 0.2s, transform 0.2s;
    }

    .contact:hover {
      background-color: #355e3b;
      color: white;
      transform: scale(1.03);
    }

    .active-contact {
      background-color: #355e3b;
      color: white;
      border: 2px solid #355e3b;
    }

    .chat-content {
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    .messages {
      height: 250px;
      overflow-y: auto;
      padding: 10px;
      display: flex;
      flex-direction: column;
    }

    .message {
      padding: 8px 12px;
      margin: 6px 0;
      border-radius: 12px;
      font-size: 14px;
      line-height: 1.4;
      max-width: 80%;
    }

    .me {
      align-self: flex-end;
      background-color: #355e3b;
      color: white;
    }

    .other {
      align-self: flex-start;
      background-color: #f0f0f0;
      color: #333;
    }

    .input-container {
      display: flex;
      padding: 10px;
      border-top: 1px solid #ddd;
    }

    .input-container input {
      flex: 1;
      padding: 5px;
      border: 1px solid #ddd;
      border-radius: 5px;
    }

    .input-container button {
      background: #355e3b;
      color: white;
      border: none;
      padding: 5px 10px;
      margin-left: 5px;
      border-radius: 5px;
      cursor: pointer;
    }
  </style>
</head>
<body>

  <button class="open-chat-btn" onclick="toggleChat()">💬</button>

  <div class="chat-overlay" id="chatBox">
    <div class="chat-header">
      Messagerie
      <span class="close-btn" onclick="toggleChat()">✖</span>
    </div>
    <div class="chat-body">
      <div class="contact-list">
        <div class="contact" onclick="openChat('Alice')">Alice</div>
        <div class="contact" onclick="openChat('Bob')">Bob</div>
        <div class="contact" onclick="openChat('Charlie')">Charlie</div>
      </div>
      <div class="chat-content">
        <div class="messages" id="messages"></div>
        <div class="input-container">
          <input type="text" id="messageInput" placeholder="Écrire un message..." disabled>
          <button id="sendBtn" onclick="sendMessage()" disabled>Envoyer</button>
        </div>
      </div>
    </div>
  </div>

  <script>
  let currentContact = null;

function toggleChat() {
  const chatBox = document.getElementById("chatBox");
  if (chatBox.style.display === "flex") {
    chatBox.style.display = "none";
    clearChat();
  } else {
    chatBox.style.display = "flex";
  }
}

function openChat(contactName) {
  currentContact = contactName;
  document.getElementById("messageInput").disabled = false;
  document.getElementById("sendBtn").disabled = false;

  clearActiveContact();
  document.querySelectorAll(".contact").forEach(el => {
    if (el.textContent === contactName) {
      el.classList.add("active-contact");
    }
  });

  fetchMessages();
}

function clearActiveContact() {
  document.querySelectorAll(".contact").forEach(el => el.classList.remove("active-contact"));
}

function clearChat() {
  currentContact = null;
  document.getElementById("messageInput").value = "";
  document.getElementById("messageInput").disabled = true;
  document.getElementById("sendBtn").disabled = true;
  document.getElementById("messages").innerHTML = "";
  clearActiveContact();
}

function fetchMessages() {
  fetch(`?contact=${currentContact}`)
    .then(response => response.json())
    .then(data => {
      const messageBox = document.getElementById("messages");
      messageBox.innerHTML = "";
      data.forEach(msg => {
        const div = document.createElement("div");
        div.classList.add("message", msg.sender === "Moi" ? "me" : "other");
        div.textContent = msg.text;
        messageBox.appendChild(div);
      });
      messageBox.scrollTop = messageBox.scrollHeight;
    });
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

document.getElementById("messageInput").addEventListener("keypress", function (e) {
  if (e.key === "Enter") {
    e.preventDefault();
    sendMessage();
  }
});

  </script>
</body>
</html>
