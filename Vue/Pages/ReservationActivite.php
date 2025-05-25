<?php
session_start();

require_once(__DIR__ . '../../../Modele/Database.php');
require_once(__DIR__ . '../../Components/Navbar2.php');
require_once(__DIR__ . '../../Components/Footer2.php');
$pdo = getPDO();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//$_SESSION['idUser'] = 11;

if (!isset($_SESSION['user']['id'])) {
    header("Location: LogIn.php");
    exit;
}

$idUser = $_SESSION['user']['id'];

//if (isset($_GET['id'])) {
//    $_SESSION['idActivite'] = (int)$_GET['id'];
//}

$idActivite = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$nomActivite = "Nom de l'activité";

if ($idActivite > 0) {
    $stmt = $pdo->prepare("SELECT nomActivite FROM activite WHERE idActivite = ?");
    $stmt->execute([$idActivite]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $nomActivite = $result['nomActivite'];
    } else {
        $nomActivite = "Activité inconnue";
    }
}

// var_dump($idUser);
// var_dump($idActivite);
// echo "idUser: " . $idUser . "<br>";
// echo "idActivite: " . $idActivite . "<br>";

$participants = [];
$participant_count = 0;
$personnesTierces = $pdo->prepare("SELECT * FROM personnetierce WHERE idUser = :idUser");
$personnesTierces->execute([':idUser' => $idUser]);



if ($_SERVER["REQUEST_METHOD"] === "POST" && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
    $json = file_get_contents("php://input");
    $data = json_decode($json, true);

    if (isset($data['participants']) && is_array($data['participants'])) {
        $stmtReservation = $pdo->prepare("INSERT INTO reservation (nom, prenom, dateNaissance, idUser, idActivite, dateAjouter) VALUES (:nom, :prenom, :dateNaissance, :idUser, :idActivite, :dateAjouter)");
        $stmtPersonneTierce = $pdo->prepare("SELECT COUNT(*) FROM personnetierce WHERE nom = :nom AND prenom = :prenom AND dateNaissance = :dateNaissance AND idUser = :idUser");
        $insertPersonneTierce = $pdo->prepare("INSERT INTO personnetierce (nom, prenom, dateNaissance, idUser) VALUES (:nom, :prenom, :dateNaissance, :idUser)");

        $stmtUser = $pdo->prepare("SELECT nom, prenom, dateNaissance FROM utilisateur WHERE idUtilisateur = ?");
        $stmtUser->execute([$idUser]);
        $currentUser = $stmtUser->fetch(PDO::FETCH_ASSOC);

        $userNom = $currentUser['nom'];
        $userPrenom = $currentUser['prenom'];
        $userDateNaissance = date('Y-m-d', strtotime($currentUser['dateNaissance']));

        foreach ($data['participants'] as $p) {
            $dateNaissance = date('Y-m-d', strtotime($p['dateNaissance']));

            $stmtReservation->execute([
                ':nom' => htmlspecialchars($p['nom']),
                ':prenom' => htmlspecialchars($p['prenom']),
                ':dateNaissance' => $dateNaissance,
                ':idUser' => $idUser,
                ':idActivite' => $idActivite,
                ':dateAjouter' => date('Y-m-d H:i:s')
            ]);

            if (
                strtolower(trim($p['nom'])) === strtolower(trim($userNom)) &&
                strtolower(trim($p['prenom'])) === strtolower(trim($userPrenom)) &&
                $dateNaissance === $userDateNaissance
            ) {
                continue;
            }

            $stmtPersonneTierce->execute([
                ':nom' => htmlspecialchars($p['nom']),
                ':prenom' => htmlspecialchars($p['prenom']),
                ':dateNaissance' => $dateNaissance,
                ':idUser' => $idUser
            ]);

            $exists = $stmtPersonneTierce->fetchColumn();

            if (!$exists) {
                $insertPersonneTierce->execute([
                    ':nom' => htmlspecialchars($p['nom']),
                    ':prenom' => htmlspecialchars($p['prenom']),
                    ':dateNaissance' => $dateNaissance,
                    ':idUser' => $idUser
                ]);
            }
        }

        echo "success";
        exit;
    }
}

$stmt = $pdo->prepare("SELECT * FROM reservation WHERE idUser = :idUser AND idActivite = :idActivite ORDER BY dateAjouter DESC");
$stmt->execute([
    ':idUser' => $idUser,
    ':idActivite' => $idActivite
]);
$participants = $stmt->fetchAll(PDO::FETCH_ASSOC);
$participant_count = count($participants);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Style/Navbar2.css">
    <link rel="stylesheet" href="../Style/Footer2.css">
    <link rel="stylesheet" href="../Style/ReservationActivite.css">
    <title>ReservationActivite</title>
</head>

<body>
<header id="navbar" class="navbar">
    <?php echo Navbar2(); ?>
</header>
<br><br>
<h1>Réservation: <?php echo htmlspecialchars($nomActivite); ?></h1>
<div class="container">
    <div class="boxform">
        <h2>Participant</h2>
        <form method="post">
            <div class="checkbox-line">
                <input type="checkbox" id="self-participate">
                <label for="self-participate">Je vais participer.</label>
            </div>
            <label for="selectParticipant">Sélectionnez un participant enregistré</label>
            <select id="saved-participant-select">
                <option value="">-- Personne --</option>
                <?php
                $stmtSaved = $pdo->prepare("SELECT nom, prenom, dateNaissance FROM personnetierce WHERE idUser = ?");
                $stmtSaved->execute([$idUser]);
                $savedParticipants = $stmtSaved->fetchAll(PDO::FETCH_ASSOC);
                foreach ($savedParticipants as $sp) {
                    $label = htmlspecialchars($sp['nom'] . ' ' . $sp['prenom'] . ' (' . $sp['dateNaissance'] . ')');
                    $value = htmlspecialchars(json_encode($sp));
                    echo "<option value='$value'>$label</option>";
                }
                ?>
            </select><br><br>
            <label for="nouveauParticipant">Nouveaux participants:</label>
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" placeholder="Smith" required>
            <label for="prenom">Prénom</label>
            <input type="text" id="prenom" name="prenom" placeholder="David" required>
            <label for="dateNaissance">Date de Naissance</label>
            <input type="date" id="dateNaissance" name="dateNaissance" placeholder="" required><br>
            <button id="Button" type="button">Ajouter</button>
        </form>
    </div>
    <table class="info-table">
        <thead>
        <tr>
            <th id="participant-count">
                Nombre de participants: <?php echo isset($participant_count) ? $participant_count : '0'; ?>
            </th>
        </tr>
        </thead>
        <tbody id="participant-body">
        <?php if ($participant_count > 0): ?>
            <?php foreach ($participants as $p): ?>
                <tr>
                    <td><?php echo htmlspecialchars($p['nom'] . ' ' . $p['prenom']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td>Aucun participant pour le moment.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<div class="button-confirmer">
    <div class="button-container">
        <button id="confirmer">Confirmer</button>
    </div>
</div>
<footer id="footer" class="footer">
    <?php echo Footer2(); ?>
</footer>
</body>
<script src="../Components/NavbarAnim.js"></script>
<script src="../Components/Reservation.js"></script>
<script>
    document.getElementById("confirmer").addEventListener("click", function () {
        if (participants.length === 0) {
            alert("Aucun participant à enregistrer.");
            return;
        }

        fetch("ReservationActivite.php?id=<?php echo $idActivite; ?>", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ participants: participants })
        })

            .then(response => response.text())
            .then(data => {
                console.log("Réponse PHP :", data);
                if (data.includes("success")) {
                    alert("Réservation enregistrée !");
                    location.reload();
                } else {
                    alert("Erreur lors de l'enregistrement.");
                }
            })
            .catch(error => {
                console.error("Erreur :", error);
                alert("Échec de l'enregistrement.");
            });
    });
</script>
</html>


