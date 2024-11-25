<?php
session_start();

$host = 'localhost';
$dbname = 'gsb1';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$fiche_id = isset($_GET['hf_id']) ? $_GET['hf_id'] : null;

if (!$fiche_id) {
    die("ID de fiche non spécifié.");
}

$stmt = $pdo->prepare("
    SELECT hf.id_hors_forfait, hf.date_hors_forfait, hf.libelle, hf.montant, hf.justificatif, hf.statut
    FROM hors_forfait hf
    INNER JOIN fiche_frais ff ON hf.id_visiteur = ff.id_visiteur
    WHERE ff.id_visiteur = :visiteur_id AND hf.id_visiteur = :fiche_id
");

// Passer les deux paramètres lors de l'exécution
$stmt->execute([
    'visiteur_id' => $_SESSION['id'],  // ID du visiteur dans la session
    'fiche_id' => $fiche_id            // ID de la fiche récupéré depuis l'URL
]);

$fiche = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$fiche) {
    die("Aucune fiche trouvée.");
}

$isEditable = ($fiche['statut'] === 'En attente');
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation fiche frais</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="text-white">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark p-3">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="assets/images/logo.png" alt="Logo" width="40" height="40" class="d-inline-block align-text-center">
            GSB
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link text-white" href="dashboardVisiteur.php">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="saisieFrais.php">Nouvelle fiche</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="profilVisiteur.php?id=<?= $_SESSION['id'] ?>">Voir profil</a>
                </li>
                <li class="nav-item">
                    <form action="logout.php" method="post" class="d-inline">
                        <button type="submit" class="btn btn-danger">Déconnexion</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-5">
    <h2 class="text-center mb-4 text-dark">Consultation fiche hors forfait</h2>
    <form method="post" enctype="multipart/form-data" action="updateHorsForfait.php" class="text-white bg-dark p-4 rounded w-50 mx-auto">
    <input type="hidden" name="fiche_id" value="<?php echo htmlspecialchars($fiche_id); ?>">
    <form method="post" enctype="multipart/form-data" class="text-white">
                <input type="hidden" name="type_frais" value="hors_forfait">
        <div class="mb-3">
            <label for="date_hors_forfait" class="form-label">Date :</label>
            <input type="date" class="form-control" id="date_hors_forfait" name="date_hors_forfait" value="<?php echo htmlspecialchars($fiche['date_hors_forfait']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="libelle" class="form-label">Raison du hors forfait :</label>
            <input type="text" class="form-control" id="libelle" name="libelle" value="<?php echo htmlspecialchars($fiche['libelle']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="montant" class="form-label">Montant (€) :</label>
            <input type="number" class="form-control" id="montant" name="montant" step="0.01" value="<?php echo htmlspecialchars($fiche['montant']); ?>" required>
        </div>
                <div class="mb-3">
                     <label for="justificatif" class="form-label">Justificatif :</label>
                            <input type="file" class="form-control" id="justificatif" name="justificatif">
                      <label for="justificatif" class="form-label">Veuillez mettre tous vos justificatifs dans un seul document au format .pdf</label>
                </div>
                <button type="submit" class="btn btn-success">Ajouter</button>
            </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>