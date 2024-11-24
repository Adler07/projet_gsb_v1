<?php
session_start();

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
    $montant_repas = $_POST['montant_repas'];
    $montant_hebergement = $_POST['montant_hebergement'];
    $montant_deplacement = $_POST['montant_deplacement'];
    $total = $_POST['total'];
    $date = $_POST['date'];

    $sql = "INSERT INTO fiche_frais (montant_repas, montant_hebergement, montant_deplacement, total, date_soumission) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$montant_repas, $montant_hebergement, $montant_deplacement, $total, $date]);

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
        <a class="navbar-brand" href="#">
            <img src="assets/images/logo.png" alt="Logo" width="40" height="40" class="d-inline-block align-text-center">
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
    <h2 class="text-center mb-4 text-dark">Nouvelle fiche de frais</h2>
    <form method="post" action="ajoutFrais.php" class="bg-dark p-4 rounded w-50 mx-auto">
        <div class="mb-3">
            <label for="montant_repas" class="form-label text-white">Montant repas (€) :</label>
            <input type="number" class="form-control" id="montant_repas" step="0.01" name="montant_repas" required>
        </div>
        <div class="mb-3">
            <label for="montant_hebergement" class="form-label text-white">Montant hébergement (€) :</label>
            <input type="number" class="form-control" id="montant_hebergement" step="0.01" name="montant_hebergement" required>
        </div>
        <div class="mb-3">
            <label for="montant_deplacement" class="form-label text-white">Montant déplacement (€) :</label>
            <input type="number" class="form-control" id="montant_deplacement" step="0.01" name="montant_deplacement" required>
        </div>
        <div class="mb-3">
            <label for="total" class="form-label text-white">Total (€) :</label>
            <input type="number" class="form-control" id="total" step="0.01" name="total" readonly required>
        </div>
        <div class="mb-3">
            <label for="date" class="form-label text-white">Date :</label>
            <input type="date" class="form-control" id="date" name="date" required>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-success text-white">Ajouter</button>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function calculateTotal() {
        const repas = parseFloat(document.getElementById('montant_repas').value) || 0;
        const hebergement = parseFloat(document.getElementById('montant_hebergement').value) || 0;
        const deplacement = parseFloat(document.getElementById('montant_deplacement').value) || 0;

        const total = repas + hebergement + deplacement;
        document.getElementById('total').value = total.toFixed(2);
    }
    document.getElementById('montant_repas').addEventListener('input', calculateTotal);
    document.getElementById('montant_hebergement').addEventListener('input', calculateTotal);
    document.getElementById('montant_deplacement').addEventListener('input', calculateTotal);
</script>
</body>
</html>
