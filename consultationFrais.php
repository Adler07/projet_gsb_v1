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

$fiche_id = isset($_GET['fiche_id']) ? $_GET['fiche_id'] : null;

if (!$fiche_id) {
    die("ID de fiche non spécifié.");
}

$stmt = $pdo->prepare("
    SELECT ff.montant_repas, ff.montant_hebergement, ff.montant_deplacement, ff.total, ff.date_soumission, sf.statut 
    FROM fiche_frais ff
    JOIN statut_fiche sf ON ff.`n°fiche_frais` = sf.fiche_id
    WHERE ff.`n°fiche_frais` = :fiche_id
");
$stmt->execute(['fiche_id' => $fiche_id]);
$fiche = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$fiche) {
    die("Aucune fiche trouvée.");
}

$isEditable = ($fiche['statut'] === 'en attente');
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
    <h2 class="text-center mb-4 text-dark">Visualisation de la fiche de frais</h2>
    <form method="post" action="updateFrais.php" class="bg-dark p-4 rounded w-50 mx-auto">
        <div class="mb-3">
            <label for="montant_repas" class="form-label text-white">Montant repas (en €) :</label>
            <input type="text" class="form-control" id="montant_repas" name="montant_repas" value="<?php echo htmlspecialchars($fiche['montant_repas']); ?>" <?php echo $isEditable ? '' : 'readonly'; ?> oninput="calculerTotal()">
        </div>
        <div class="mb-3">
            <label for="montant_hebergement" class="form-label text-white">Montant hébergement (en €) :</label>
            <input type="text" class="form-control" id="montant_hebergement" name="montant_hebergement" value="<?php echo htmlspecialchars($fiche['montant_hebergement']); ?>" <?php echo $isEditable ? '' : 'readonly'; ?> oninput="calculerTotal()">
        </div>
        <div class="mb-3">
            <label for="montant_deplacement" class="form-label text-white">Montant déplacement (en €) :</label>
            <input type="text" class="form-control" id="montant_deplacement" name="montant_deplacement" value="<?php echo htmlspecialchars($fiche['montant_deplacement']); ?>" <?php echo $isEditable ? '' : 'readonly'; ?> oninput="calculerTotal()">
        </div>
        <div class="mb-3">
            <label for="total" class="form-label">Total (en €) :</label>
            <input type="text" class="form-control" id="total" name="total" value="<?php echo htmlspecialchars($fiche['total']); ?>" readonly>
        </div>
        <div class="mb-3">
            <label for="date_soumission" class="form-label">Date :</label>
            <input type="date" class="form-control" name="date_soumission" value="<?php echo htmlspecialchars($fiche['date_soumission']); ?>" <?php echo $isEditable ? '' : 'readonly'; ?>>
        </div>
        <div class="mb-3">
            <label for="statut" class="form-label">Statut :</label>
            <input type="text" class="form-control" name="statut" value="<?php echo htmlspecialchars($fiche['statut']); ?>" readonly>
        </div>
        <?php if ($isEditable): ?>
            <input type="hidden" name="fiche_id" value="<?php echo $fiche_id; ?>">
            <div class="text-center">
            <button type="submit" class="btn btn-success text-white">Mettre à jour</button>
            </div>
           
        <?php endif; ?>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function calculerTotal() {
        const montantRepas = parseFloat(document.getElementById('montant_repas').value) || 0;
        const montantHebergement = parseFloat(document.getElementById('montant_hebergement').value) || 0;
        const montantDeplacement = parseFloat(document.getElementById('montant_deplacement').value) || 0;

        const total = montantRepas + montantHebergement + montantDeplacement;
        document.getElementById('total').value = total.toFixed(2);
    }
</script>
</body>
</html>
