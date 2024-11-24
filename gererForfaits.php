<?php
session_start();

$prenom = $_SESSION['prenom'];
$nom = $_SESSION['nom'];
$role = $_SESSION['nom_role'];
$id = $_SESSION['id'];

$db = 'mysql:host=localhost;dbname=gsb1;charset=utf8';
$username = 'root';
$password = '';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($db, $username, $password, $options);
} catch (PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}

$sql = "SELECT * FROM frais_forfait";
$stmt = $pdo->query($sql);
$forfaits = $stmt->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $repas = $_POST['repas'];
    $hebergement = $_POST['hebergement'];
    $transport = $_POST['transport'];
    $kilometre = $_POST['kilometre'];

    $updateRepas = $pdo->prepare("UPDATE frais_forfait SET forfait = :forfait WHERE nom_forfait = 'repas'");
    $updateRepas->execute(['forfait' => $repas]);

    $updateHebergement = $pdo->prepare("UPDATE frais_forfait SET forfait = :forfait WHERE nom_forfait = 'hebergement'");
    $updateHebergement->execute(['forfait' => $hebergement]);

    $updateTransport = $pdo->prepare("UPDATE frais_forfait SET forfait = :forfait WHERE nom_forfait = 'transport'");
    $updateTransport->execute(['forfait' => $transport]);

    $updateTransport = $pdo->prepare("UPDATE frais_forfait SET forfait = :forfait WHERE nom_forfait = 'kilometrique'");
    $updateTransport->execute(['forfait' => $kilometre]);

    $successMessage = "Les forfaits ont été mis à jour avec succès.";
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Forfaits</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-dark navbar-dark p-3">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="assets/images/logo.png" alt="Logo" width="40" height="40" class="d-inline-block align-text-center">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link text-white" href="dashboardAdmin.php">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="creerProfil.php">Créer utilisateur</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="gererForfaits.php">Gérer les forfaits</a>
                </li>
                <li class="nav-item">
                    <form action="logout.php" method="post" class="d-inline">
                        <button type="submit" class="btn btn-danger ms-3">Déconnexion</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success text-center mb-4">
            <?= htmlspecialchars($successMessage) ?>
        </div>
    <?php endif; ?>
    <h2 class="text-center mb-4 text-dark">Modifier les frais forfaitaires quotidiens</h2>
    <form method="post" class="bg-dark p-4 rounded w-50 mx-auto">
        <div class="mb-3">
            <label for="repas" class="form-label text-white">Forfait repas</label>
            <input type="text" class="form-control" id="repas" name="repas" value="<?= htmlspecialchars($forfaits[0]['forfait']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="hebergement" class="form-label text-white">Forfait hébergement :</label>
            <input type="text" class="form-control" id="hebergement" name="hebergement" value="<?= htmlspecialchars($forfaits[1]['forfait']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="transport" class="form-label text-white">Forfait transport :</label>
            <input type="text" class="form-control" id="transport" name="transport" value="<?= htmlspecialchars($forfaits[2]['forfait']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="transport" class="form-label text-white">Forfait kilométrique :</label>
            <input type="text" class="form-control" id="kilometre" name="kilometre" value="<?= htmlspecialchars($forfaits[3]['forfait']) ?>" required>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-success text-white">Changer forfaits</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>