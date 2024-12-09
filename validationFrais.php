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
    SELECT montant_repas, nombre_repas, montant_hebergement, nombre_hebergement, montant_deplacement, nombre_deplacement, total, date_soumission, justificatif, kilometres_voiture, statut
    FROM fiche_frais
    WHERE n°fiche_frais = :fiche_id
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
    <h2 class="text-center mb-4 text-dark">Validation fiche de frais</h2>
    <form class="text-white bg-dark p-4 rounded w-50 mx-auto" action="validationStatut.php" method="post">
        <input type="hidden" name="fiche_id" value="<?php echo htmlspecialchars($fiche_id); ?>">
        <div class="mb-3">
            <label for="montant_repas" class="form-label">Total repas (€) :</label>
            <input type="number" class="form-control" id="montant_repas" value="<?php echo htmlspecialchars($fiche['montant_repas']); ?>" readonly>
        </div>
        <div class="mb-3">
            <label for="nombre_repas" class="form-label">Nombre de repas :</label>
            <input type="number" class="form-control" id="nombre_repas" value="<?php echo htmlspecialchars($fiche['nombre_repas']); ?>" readonly>
        </div>
        <div class="mb-3">
            <label for="montant_hebergement" class="form-label">Total hébergement (€) :</label>
            <input type="number" class="form-control" id="montant_hebergement" value="<?php echo htmlspecialchars($fiche['montant_hebergement']); ?>" readonly>
        </div>
        <div class="mb-3">
            <label for="nombre_hebergement" class="form-label">Nombre de nuits :</label>
            <input type="number" class="form-control" id="nombre_hebergement" value="<?php echo htmlspecialchars($fiche['nombre_hebergement']); ?>" readonly>
        </div>
        <?php if ($fiche['kilometres_voiture'] === null): ?>
            <div id="transports_fields">
                <div class="mb-3">
                    <label for="total_deplacement" class="form-label">Total déplacement (€) :</label>
                    <input type="number" class="form-control" id="total_deplacement" value="<?php echo htmlspecialchars($fiche['montant_deplacement']); ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="nombre_deplacement" class="form-label">Nombre de déplacements :</label>
                    <input type="number" class="form-control" id="nombre_deplacement" value="<?php echo htmlspecialchars($fiche['nombre_deplacement']); ?>" readonly>
                </div>
            </div>
        <?php else: ?>
            <div id="voiture_fields">
                <div class="mb-3">
                    <label for="kilometres_voiture" class="form-label">Kilomètres effectués :</label>
                    <input type="number" class="form-control" id="kilometres_voiture" value="<?php echo htmlspecialchars($fiche['kilometres_voiture']); ?>" readonly>
                </div>
            </div>
        <?php endif; ?>
        <div class="mb-3">
            <label for="total" class="form-label">Total (€) :</label>
            <input type="number" class="form-control" id="total" value="<?php echo htmlspecialchars($fiche['total']); ?>" readonly>
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Date :</label>
            <input type="date" class="form-control" id="date" value="<?php echo htmlspecialchars($fiche['date_soumission']); ?>" readonly>
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
<div class="text-center">
    <input type="submit" value="Enregistrer" name="enregistrer" class="btn btn-success">
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
