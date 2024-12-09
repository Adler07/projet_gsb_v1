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
    SELECT * FROM hors_forfait 
    WHERE id_hors_forfait = :fiche_id
");

// Passer les deux paramètres lors de l'exécution
$stmt->execute([
    'fiche_id' => $fiche_id,  // ID du visiteur dans la session
]);

$fiche = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$fiche) {
    die("Aucune fiche trouvée.");
}

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation fiche hors frais</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="text-white">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark p-3">
    <div class="container-fluid">
    <img src="assets\images\Fichier 1.png" alt="Logo" width="150" height="auto" class="d-inline-block align-text-center">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="dashboardComptable.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="profilComptable.php?id=<?= $_SESSION['id'] ?>">Voir profil</a>
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
    <form method="post" enctype="multipart/form-data" action="validationStatutHorsForfait.php" class="text-white bg-dark p-4 rounded w-50 mx-auto">
    <input type="hidden" name="fiche_id" value="<?php echo htmlspecialchars($fiche_id); ?>">
    <form method="post" enctype="multipart/form-data" class="text-white">
                <input type="hidden" name="type_frais" value="hors_forfait">
        <div class="mb-3">
            <label for="date_hors_forfait" class="form-label">Date :</label>
            <input type="date" class="form-control" id="date_hors_forfait" name="date_hors_forfait" value="<?php echo htmlspecialchars($fiche['date_hors_forfait']); ?>" readonly>
        </div>
        <div class="mb-3">
            <label for="libelle" class="form-label">Raison du hors forfait :</label>
            <input type="text" class="form-control" id="libelle" name="libelle" value="<?php echo htmlspecialchars($fiche['libelle']); ?>" readonly>
        </div>
        <div class="mb-3">
            <label for="montant" class="form-label">Montant (€) :</label>
            <input type="number" class="form-control" id="montant" name="montant" step="0.01" value="<?php echo htmlspecialchars($fiche['montant']); ?>" readonly>
        </div>
        <?php if (!empty($fiche['justificatif'])): ?>
            <div class="mb-3">
                <label class="form-label">Justificatif existant :</label>
                <a href="path_to_your_directory/<?php echo $fiche['justificatif']; ?>" target="_blank">Voir le justificatif précédent</a>
            </div>
        <?php endif; ?>
        <div class="mb-3">
    <label for="validation" class="form-label">Validation :</label>
    <select id="validation" name="validation" class="form-select">
        <option value="Complet">Remboursement complet</option>
        <option value="Partiel">Remboursement partiel</option>
        <option value="Refuse">Remboursement refusé</option>
    </select>
</div>
<div id="remboursement" class="mb-3">
    <label for="montant_rembourse" class="form-label">Montant du remboursement :</label>
    <input type="number" name="montant_rembourse" id="montant_rembourse" step="0.1" class="form-control">
</div>
                <button type="submit" class="btn btn-success">Ajouter</button>
            </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>