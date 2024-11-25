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
    <h2 class="text-center mb-4 text-dark">Consultation fiche de frais</h2>
    <form method="post" enctype="multipart/form-data" action="updateFrais.php" class="text-white bg-dark p-4 rounded w-50 mx-auto">
    <input type="hidden" name="fiche_id" value="<?php echo htmlspecialchars($fiche_id); ?>">
    <div class="mb-3">
        <label for="montant_repas" class="form-label">Total repas (€) :</label>
        <input type="number" class="form-control" id="montant_repas" name="montant_repas" step="0.01" value="<?php echo htmlspecialchars($fiche['montant_repas']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="nombre_repas" class="form-label">Nombre de repas :</label>
        <input type="number" class="form-control" id="nombre_repas" name="nombre_repas" value="<?php echo htmlspecialchars($fiche['nombre_repas']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="montant_hebergement" class="form-label">Total hébergement (€) :</label>
        <input type="number" class="form-control" id="montant_hebergement" name="montant_hebergement" step="0.01" value="<?php echo htmlspecialchars($fiche['montant_hebergement']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="nombre_hebergement" class="form-label">Nombre de nuits :</label>
        <input type="number" class="form-control" id="nombre_hebergement" name="nombre_hebergement" value="<?php echo htmlspecialchars($fiche['nombre_hebergement']); ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Avez-vous pris les transports en commun ou utilisé la voiture ?</label>
        <select class="form-control" id="moyen_transport" name="moyen_transport" required>
            <option value="">Choisissez...</option>
            <option value="transports" <?php echo ($fiche['kilometres_voiture'] === null) ? 'selected' : ''; ?>>Transports en commun</option>
            <option value="voiture" <?php echo ($fiche['kilometres_voiture'] !== null) ? 'selected' : ''; ?>>Voiture</option>
        </select>
    </div>
    <div id="transports_fields" style="display: <?php echo ($fiche['kilometres_voiture'] === null) ? 'block' : 'none'; ?>;">
        <div class="mb-3">
            <label for="total_deplacement" class="form-label">Total déplacement (€) :</label>
            <input type="number" class="form-control" id="total_deplacement" name="total_deplacement" value="<?php echo htmlspecialchars($fiche['montant_deplacement']); ?>" step="0.01">
        </div>
        <div class="mb-3">
            <label for="nombre_deplacement" class="form-label">Nombre de déplacements :</label>
            <input type="number" class="form-control" id="nombre_deplacement" name="nombre_deplacement" value="<?php echo htmlspecialchars($fiche['nombre_deplacement']); ?>">
        </div>
    </div>
    <div id="voiture_fields" style="display: <?php echo ($fiche['kilometres_voiture'] !== null) ? 'block' : 'none'; ?>;">
        <div class="mb-3">
            <label for="kilometres_voiture" class="form-label">Kilomètres effectués :</label>
            <input type="number" class="form-control" id="kilometres_voiture" name="kilometres_voiture" value="<?php echo htmlspecialchars($fiche['kilometres_voiture']); ?>">
        </div>
    </div>
    <div class="mb-3">
        <label for="total" class="form-label">Total (€) :</label>
        <input type="number" class="form-control" id="total" name="total" value="<?php echo htmlspecialchars($fiche['total']); ?>" readonly required>
    </div>
    <div class="mb-3">
        <label for="date" class="form-label">Date :</label>
        <input type="date" class="form-control" id="date" name="date" value="<?php echo htmlspecialchars($fiche['date_soumission']); ?>" required>
    </div>
    <?php if (!empty($fiche['justificatif'])): ?>
    <div class="mb-3">
        <label class="form-label">Justificatif existant :</label>
        <a href="path_to_your_directory/<?php echo $fiche['justificatif']; ?>" target="_blank">Voir le justificatif précédent</a>
    </div>
<?php endif; ?>
<div class="mb-3">
    <label for="justificatif" class="form-label">Justificatif :</label>
    <input type="file" class="form-control" id="justificatif" name="justificatif">
    <label for="justificatif" class="form-label">Veuillez mettre tous vos justificatifs dans un seul document au format .pdf</label>
</div>
                <button type="submit" class="btn btn-success">Ajouter</button>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('moyen_transport').addEventListener('change', function() {
    var moyen_transport = this.value;

    document.getElementById('transports_fields').style.display = 'none';
    document.getElementById('voiture_fields').style.display = 'none';

    if (moyen_transport === 'transports') {
        document.getElementById('transports_fields').style.display = 'block';
    } else if (moyen_transport === 'voiture') {
        document.getElementById('voiture_fields').style.display = 'block';
    }

   
    calculateTotal(); 
});


function calculateTotal() {

    var montant_repas = parseFloat(document.getElementById('montant_repas').value) || 0;
    var nombre_repas = parseInt(document.getElementById('nombre_repas').value) || 0;
    var montant_hebergement = parseFloat(document.getElementById('montant_hebergement').value) || 0;
    var nombre_hebergement = parseInt(document.getElementById('nombre_hebergement').value) || 0;
    var total_deplacement = parseFloat(document.getElementById('total_deplacement').value) || 0;
    var nombre_deplacement = parseInt(document.getElementById('nombre_deplacement').value) || 0;
    var kilometres_voiture = parseFloat(document.getElementById('kilometres_voiture').value) || 0;

    var totalRepas = montant_repas; 
    var totalHebergement = montant_hebergement; 
    var total = totalRepas + totalHebergement;  

  
    if (document.getElementById('moyen_transport').value === 'voiture') {
        var totalKilometres = kilometres_voiture * 0.5; 
        total += totalKilometres;
    } else if (document.getElementById('moyen_transport').value === 'transports') {
        var totalTransport = total_deplacement * nombre_deplacement; 
        total += totalTransport;
    }


    document.getElementById('total').value = total.toFixed(2);
}

document.getElementById('montant_repas').addEventListener('input', calculateTotal);
document.getElementById('nombre_repas').addEventListener('input', calculateTotal);
document.getElementById('montant_hebergement').addEventListener('input', calculateTotal);
document.getElementById('nombre_hebergement').addEventListener('input', calculateTotal);
document.getElementById('total_deplacement').addEventListener('input', calculateTotal);
document.getElementById('nombre_deplacement').addEventListener('input', calculateTotal);
document.getElementById('kilometres_voiture').addEventListener('input', calculateTotal);

</script>
</body>
</html>
