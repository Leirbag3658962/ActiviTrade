<?php
session_start();
require_once "../../ModeleB/LienPDO.php";
$pdo = lienPDO();  

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $lastname = htmlspecialchars($_POST['lastname']);
    $firstname = htmlspecialchars($_POST['firstname']);
    $age = htmlspecialchars($_POST['age']);

    if (!empty($lastname) && !empty($firstname) && !empty($age)) {
        $stmt = $pdo->prepare("INSERT INTO participant (lastname, firstname, age) VALUES (:lastname, :firstname, :age)");
        $stmt->execute([
            ':lastname' => $lastname,
            ':firstname' => $firstname,
            ':age' => $age
        ]);
    }
}

$stmt = $pdo->query("SELECT * FROM participant ORDER BY created_at DESC");
$participants = $stmt->fetchAll(PDO::FETCH_ASSOC);
$participant_count = count($participants);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Style/Navbar.css">
    <link rel="stylesheet" href="../Style/Footer.css">
    <link rel="stylesheet" href="../Style/ReservationActivite.css">
    <title>ReservationActivite</title>
</head>

<body>
<header id="navbar" class="navbar">
</header>
<br><br>
<h1>Réservation: Club de lecture</h1>
<div class="container">
    <div class="boxform">
        <h2>Participant</h2><br>
        <form method="post">
            <label for="lastname">Nom</label><br>
            <input type="text" id="lastname" name="lastname" placeholder="Smith" required><br>
            <label for="firstname">Prénom</label><br>
            <input type="text" id="firstname" name="firstname" placeholder="David" required><br>
            <label for="age">Age</label><br>
            <input type="text" id="age" name="age" placeholder="" required><br><br>
            <button id="Button" type="submit">Ajouter un participant</button>
        </form>
    </div>
    <table class="info-table">
        <tr>
            <th>Nombre de participants: <?php echo $participant_count; ?></th>
        </tr>
        <?php if ($participant_count > 0): ?>
            <?php foreach ($participants as $p): ?>
                <tr>
                    <td><?php echo htmlspecialchars($p['lastname'] . ' ' . $p['firstname'] . ' (' . $p['age'] . ' ans)'); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td>Aucun participant pour le moment.</td>
            </tr>
        <?php endif; ?>
        <tr>
            <td></td>
        </tr>
    </table>
</div>
<div class="button-confirmer">
    <div class="button-container">
        <button id="confirmer">Comfirmer</button>
    </div>
</div>
<footer id="footer" class="footer"></footer>
</body>
<script src="../Components/Navbar.js"></script>
<script>
    document.getElementById("navbar").innerHTML = Navbar();
</script>
<script src="../Components/NavbarAnim.js"></script>
<script src="../Components/Footer.js"></script>
</html>
