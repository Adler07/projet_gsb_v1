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
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualisation fiche frais</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="text-white">
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
        <h2 class="text-center mb-4 text-dark">Visualisation de la fiche de frais</h2>
        <form action="validationStatut.php" method="post" class="bg-dark p-4 rounded w-50 mx-auto">
            <input type="hidden" name="fiche_id" value="<?php echo htmlspecialchars($fiche_id); ?>">
            <div class="mb-3">
                <label for="montant_repas" class="form-label text-white">Montant repas :</label>
                <input type="text" class="form-control" id="montant_repas" value="<?php echo htmlspecialchars($fiche['montant_repas']); ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="montant_hebergement" class="form-label text-white">Montant hébergement :</label>
                <input type="text" class="form-control" id="montant_hebergement" value="<?php echo htmlspecialchars($fiche['montant_hebergement']); ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="montant_deplacement" class="form-label text-white">Montant déplacement :</label>
                <input type="text" class="form-control" id="montant_deplacement" value="<?php echo htmlspecialchars($fiche['montant_deplacement']); ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="total" class="form-label text-white">Total :</label>
                <input type="text" class="form-control" id="total" value="<?php echo htmlspecialchars($fiche['total']); ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="date_soumission" class="form-label text-white">Date :</label>
                <input type="date" class="form-control" id="date_soumission" value="<?php echo htmlspecialchars($fiche['date_soumission']); ?>" readonly>
            </div>
            <div class="mb-3">
                <input type="radio" name="validation" id="valider" value="Valider">
                <label for="valider">Remboursement complet</label><br>
                <input type="radio" name="validation" id="partiel" value="Partiel">
                <label for="partiel">Remboursement partiel</label><br>
                <input type="radio" name="validation" id="refuser" value="Refuser">
                <label for="refuser">Remboursement refusé</label><br>
            </div>
            <div class="mb-3">
                <label for="raison" class="form-label text-white">Raison (obligatoire si refusé ou partiel) :</label>
                <textarea name="raison" id="raison" class="form-control" maxlength="255"></textarea>
            </div>
            <div id="partiel-amount-section" class="mb-3" style="display: none;">
                <label for="montant_partiel" class="form-label text-white">Montant du remboursement partiel :</label>
                <input type="number" name="montant_partiel" id="montant_partiel" class="form-control">
            </div>
            <div class="text-center">
                <input type="submit" value="Enregistrer" name="enregistrer" class="btn btn-success">
            </div>
        </form>
    </div>

    <script>
        const commentaire = document.getElementById('raison');
        const montantPartiel = document.getElementById('montant_partiel');
        const sectionPartiel = document.getElementById('partiel-amount-section');

        function handleValidation() {
            const validerRadio = document.getElementById('valider');
            const partielRadio = document.getElementById('partiel');
            const refuserRadio = document.getElementById('refuser');

            if (validerRadio.checked) {
                commentaire.required = false;
                sectionPartiel.style.display = 'none';
                montantPartiel.required = false;
            } else if (partielRadio.checked) {
                commentaire.required = true;
                sectionPartiel.style.display = 'block';
                montantPartiel.required = true;
            } else if (refuserRadio.checked) {
                commentaire.required = true;
                sectionPartiel.style.display = 'none';
                montantPartiel.required = false;
            }
        }

        document.getElementById('valider').addEventListener('change', handleValidation);
        document.getElementById('partiel').addEventListener('change', handleValidation);
        document.getElementById('refuser').addEventListener('change', handleValidation);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
