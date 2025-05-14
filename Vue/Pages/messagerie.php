<?php
$pdo = new PDO('mysql:host=localhost;dbname=activititrade', 'root', '');

// Recherche infos utilisateur
if (isset($_GET['info']) && isset($_GET['search'])) {
    $input = $_GET['search'];
    $stmt = is_numeric($input)
        ? $pdo->prepare("SELECT idUtilisateur, nom, prenom, email, dateNaissance, numeroRue, nomRue, codePostal, ville, pays, telephone, role FROM utilisateur WHERE idUtilisateur = ?")
        : $pdo->prepare("SELECT idUtilisateur, nom, prenom, email, dateNaissance, numeroRue, nomRue, codePostal, ville, pays, telephone, role FROM utilisateur WHERE email = ?");
    $stmt->execute([$input]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo json_encode([
            "success" => true,
            "id" => $user['idUtilisateur'],
            "nom" => $user['nom'],
            "prenom" => $user['prenom'],
            "email" => $user['email'],
            "dateNaissance" => $user['dateNaissance'],
            "numeroRue" => $user['numeroRue'],
            "nomRue" => $user['nomRue'],
            "codePostal" => $user['codePostal'],
            "ville" => $user['ville'],
            "pays" => $user['pays'],
            "telephone" => $user['telephone'],
            "role" => $user['role']
        ]);
    } else {
        echo json_encode(["success" => false, "error" => "Utilisateur non trouvé"]);
    }
    exit;
}
if (isset($_GET['history'])) {
  $idMoi = 1; // ⚠️ Remplacer par $_SESSION['idUtilisateur'] en production

  $sql = "
    SELECT DISTINCT u.idUtilisateur, u.nom, u.email
    FROM discussion d
    JOIN utilisateur u ON u.idUtilisateur = 
      CASE 
        WHEN d.idUser1 = :idMoi THEN d.idUser2
        WHEN d.idUser2 = :idMoi THEN d.idUser1
      END
    WHERE d.idUser1 = :idMoi OR d.idUser2 = :idMoi
  ";

  $stmt = $pdo->prepare($sql);
  $stmt->execute(['idMoi' => $idMoi]);

  $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($contacts);
  exit;
}

// Récupération messages
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['contact'])) {
    $idMoi = 1;
    $idContact = intval($_GET['contact']);

    $stmt = $pdo->prepare("SELECT * FROM discussion WHERE (idUser1 = ? AND idUser2 = ?) OR (idUser1 = ? AND idUser2 = ?) ORDER BY date ASC");
    $stmt->execute([$idMoi, $idContact, $idContact, $idMoi]);

    $messages = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $messages[] = [
            "sender" => $row['idUser1'] == $idMoi ? "Moi" : "Lui",
            "text" => $row['contenu']
        ];
    }
    header('Content-Type: application/json');
    echo json_encode($messages);
    exit;
}

// Envoi message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'send') {
    $data = json_decode(file_get_contents('php://input'), true);
    $idMoi = 1;
    $idContact = intval($data['contact'] ?? 0);

    if (!$idContact || empty($data['text'])) {
        echo json_encode(["success" => false, "error" => "Utilisateur introuvable ou message vide"]);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO discussion (idUser1, idUser2, contenu, date) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$idMoi, $idContact, $data['text']]);
    echo json_encode(["success" => true]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Messagerie</title>
  <link rel="stylesheet" href="../Style/messagerie.css">
</head>
<body>

<button id="messagerie-toggle" onclick="toggleMessagerie()">Message</button>

<div id="messagerie">
  <header>
    Messagerie 123<span class="close-btn" onclick="toggleMessagerie()">✖</span>
  </header>

  <div class="messagerie-wrapper">
    <div class="sidebar">
      <div class="contact-input">
        <input type="text" id="idSearch" placeholder="ID ou email">
        <button onclick="searchUser()">OK</button>
      </div>
      <ul id="contactHistory"></ul>
    </div>

    <div class="chat-section">
      <div id="contactInfo" class="contact-info"></div>
      <div class="messages" id="messages"></div>
      <footer>
        <input type="text" id="messageInput" placeholder="Écrire un message..." disabled>
        <button class="send" onclick="sendMessage()" disabled>➤</button>
      </footer>
    </div>
  </div>
</div>

<script src="../Components/messagerie.js"></script>
</body>
</html>


