<?php 
session_start();
$id_visiteur = $_SESSION['id'];


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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['type_frais']) && $_POST['type_frais'] === 'forfait') {
        $montant_repas = $_POST['montant_repas'];
        $nombre_repas = $_POST['nombre_repas']; 
        $montant_hebergement = $_POST['montant_hebergement'];
        $nombre_hebergement = $_POST['nombre_hebergement']; 
        $montant_deplacement = $_POST['montant_deplacement'];
        $nombre_deplacement = $_POST['nombre_deplacement']; 
        $total = $_POST['total'];
        $date = $_POST['date'];
        $justificatif = $_FILES['justificatif'];
        $kilometres_voiture = isset($_POST['kilometres_voiture']) ? $_POST['kilometres_voiture'] : null;


        $justificatif_path = null; 
        if ($justificatif['error'] === UPLOAD_ERR_OK) {
            $target_dir = "assets\uploads"; 
            $justificatif_path = $target_dir . basename($justificatif['name']);
            
            if (!move_uploaded_file($_FILES['justificatif']['tmp_name'], $justificatif_path)) {
                die("Erreur lors de l'upload du fichier.");
            }
        }

        $sql = "INSERT INTO fiche_frais (montant_repas, nombre_repas, montant_hebergement, nombre_hebergement, montant_deplacement, nombre_deplacement, total, date_soumission, justificatif, kilometres_voiture, statut, id_visiteur) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$montant_repas, $nombre_repas, $montant_hebergement, $nombre_hebergement, $montant_deplacement, $nombre_deplacement, $total, $date, $justificatif_path, $kilometres_voiture, 'En attente', $id_visiteur]);
    }
    elseif (isset($_POST['type_frais']) && $_POST['type_frais'] === 'hors_forfait') {
        $date = $_POST['date_hors_forfait'];
        $libelle = $_POST['libelle'];
        $montant = $_POST['montant'];
        $justificatif = $_FILES['justificatif'];
    
        $justificatif_path = null; 
        if ($justificatif['error'] === UPLOAD_ERR_OK) {
            $target_dir = "assets\uploads"; 
            
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $justificatif_path = $target_dir . basename($justificatif['name']);
            
            if (!move_uploaded_file($justificatif['tmp_name'], $justificatif_path)) {
                die("Erreur lors de l'upload du fichier.");
            }
        }
    
        $sql = "INSERT INTO hors_forfait (date_hors_forfait, libelle, montant, justificatif, statut, id_visiteur) 
        VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$date, $libelle, $montant, $justificatif_path, 'En attente', $id_visiteur]);

    
        echo "Frais hors forfait ajoutés avec succès !";
    }

    header('Location: dashboardVisiteur.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter des frais</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-dark navbar-dark p-3">
    <div class="container-fluid">
    <img src="assets\images\Fichier 1.png" alt="Logo" width="150" height="auto" class="d-inline-block align-text-center">
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
    <h2 class="text-center mb-4 text-dark">Nouvelle fiche de frais</h2>
    <ul class="nav nav-tabs" id="fraisTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="forfait-tab" data-bs-toggle="tab" data-bs-target="#forfait" type="button" role="tab">Frais forfait</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="hors-forfait-tab" data-bs-toggle="tab" data-bs-target="#hors-forfait" type="button" role="tab">Frais hors forfait</button>
        </li>
    </ul>
    <div class="tab-content bg-dark p-4 rounded" id="fraisTabContent">
        <div class="tab-pane fade show active" id="forfait" role="tabpanel">
            <form method="post" enctype="multipart/form-data" class="text-white">
                <input type="hidden" name="type_frais" value="forfait">
                <div class="mb-3">
                    <label for="montant_repas" class="form-label">Total repas (€) :</label>
                    <input type="number" class="form-control" id="montant_repas" name="montant_repas" step="0.01" required>
                </div>
                <div class="mb-3">
                    <label for="nombre_repas" class="form-label">Nombre de repas :</label>
                    <input type="number" class="form-control" id="nombre_repas" name="nombre_repas" required>
                </div>
                <div class="mb-3">
                    <label for="montant_hebergement" class="form-label">Total hébergement (€) :</label>
                    <input type="number" class="form-control" id="montant_hebergement" name="montant_hebergement" step="0.01" required>
                </div>
                <div class="mb-3">
                    <label for="nombre_repas" class="form-label">Nombre de nuits :</label>
                    <input type="number" class="form-control" id="nombre_hebergement" name="nombre_hebergement" required>
                </div>
    <div class="mb-3">
        <label class="form-label">Avez-vous pris les transports en commun ou utilisé la voiture ?</label>
        <select class="form-control" id="moyen_transport" name="moyen_transport" required>
            <option value="">Choisissez...</option>
            <option value="transports">Transports en commun</option>
            <option value="voiture">Voiture</option>
        </select>
    </div>

    <div id="transports_fields" style="display:none;">
        <div class="mb-3">
            <label for="total_deplacement" class="form-label">Total déplacement (€) :</label>
            <input type="number" class="form-control" id="total_deplacement" name="total_deplacement" step="0.01">
        </div>
        <div class="mb-3">
            <label for="nombre_deplacement" class="form-label">Nombre de déplacements pris :</label>
            <input type="number" class="form-control" id="nombre_deplacement" name="nombre_deplacement">
        </div>
    </div>

    <div id="voiture_fields" style="display:none;">
        <div class="mb-3">
            <label for="kilometres_voiture" class="form-label">Nombre de kilomètres effectués en voiture :</label>
            <input type="number" class="form-control" id="kilometres_voiture" name="kilometres_voiture">
        </div>
    </div>
                <div class="mb-3">
                    <label for="total" class="form-label">Total (€) :</label>
                    <input type="number" class="form-control" id="total" name="total" readonly required>
                </div>
                <div class="mb-3">
                    <label for="date" class="form-label">Date :</label>
                    <input type="date" class="form-control" id="date" name="date" required>
                </div>
                <div class="mb-3">
                    <label for="justificatif" class="form-label">Justificatif :</label>
                    <input type="file" class="form-control" id="justificatif" name="justificatif">
                    <label for="justificatif" class="form-label">Veuillez mettre tous vos justificatifs dans un seul document au format .pdf</label>
                </div>
                <button type="submit" class="btn btn-success">Ajouter</button>
            </form>
        </div>

        <div class="tab-pane fade" id="hors-forfait" role="tabpanel">
            <form method="post" enctype="multipart/form-data" class="text-white">
                <input type="hidden" name="type_frais" value="hors_forfait">
                <div class="mb-3">
                    <label for="date_hors_forfait" class="form-label">Date :</label>
                    <input type="date" class="form-control" id="date_hors_forfait" name="date_hors_forfait" required>
                </div>
                <div class="mb-3">
                    <label for="libelle" class="form-label">Raison du hors forfait :</label>
                    <input type="text" class="form-control" id="libelle" name="libelle" required>
                </div>
                <div class="mb-3">
                    <label for="montant" class="form-label">Montant (€) :</label>
                    <input type="number" class="form-control" id="montant" name="montant" step="0.01" required>
                </div>
                <div class="mb-3">
                    <label for="justificatif" class="form-label">Justificatif :</label>
                    <input type="file" class="form-control" id="justificatif" name="justificatif">
                    <label for="justificatif" class="form-label">Veuillez mettre tous vos justificatifs dans un seul document au format .pdf</label>
                </div>
                <button type="submit" class="btn btn-success">Ajouter</button>
            </form>
        </div>
    </div>
</div>
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
