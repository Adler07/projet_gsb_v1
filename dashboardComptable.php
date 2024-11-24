<?php
session_start();


$nom = $_SESSION['name'];
$role = $_SESSION['role'];

$host = 'localhost';
$dbname = 'gsb1';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT f.`n°fiche_frais`, f.total, f.date_soumission, s.statut 
                           FROM fiche_frais f 
                           JOIN statut_fiche s ON f.`n°fiche_frais` = s.fiche_id 
                           WHERE s.statut = 'En attente' 
                           ORDER BY f.date_soumission DESC;");
    $stmt->execute();
    $fiches = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-white text-white">
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
                    <a class="nav-link text-white" href="dashboardComptable.php">Accueil</a>
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
    <h2 class="text-center mb-4">Bienvenue, <?= htmlspecialchars($nom) ?> !</h2>
    <p class="text-center">Vous êtes connecté en tant que <strong><?= htmlspecialchars($role) ?></strong>.</p>
</div>
<div class="container mt-4">
    <h3 class="text-center mb-4">Liste des Fiches de Frais</h3>
    <table class="table table-bordered text-center">
        <thead class="table-dark">
            <tr>
                <th>Numéro de Fiche</th>
                <th>Total (€)</th>
                <th>Date de Soumission</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($fiches)) : ?>
                <?php foreach ($fiches as $fiche) : ?>
                    <tr>
                        <td><?= htmlspecialchars($fiche['n°fiche_frais']); ?></td>
                        <td><?= number_format($fiche['total'], 2, ',', ' '); ?> €</td>
                        <td><?= htmlspecialchars($fiche['date_soumission']); ?></td>
                        <td><?= htmlspecialchars($fiche['statut']); ?></td>
                        <td>
                            <a href="validationFrais.php?fiche_id=<?= $fiche['n°fiche_frais']; ?>" class="btn btn-primary">Consulter</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5">Aucune fiche de frais en attente.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
