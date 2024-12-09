<?php
session_start();

$prenom = $_SESSION['prenom'];
$nom = $_SESSION['nom'];
$role = $_SESSION['nom_role'];
$id = $_SESSION['id'];

$host = 'localhost';
$dbname = 'gsb1';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête pour récupérer les fiches de frais en attente
    $stmt = $pdo->prepare("SELECT * FROM fiche_frais
                            WHERE statut = 'En attente' AND id_visiteur = :id
                            ORDER BY `n°fiche_frais` DESC");
    $stmt->execute([':id' => $id]);
    $fiches = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Requête pour récupérer les hors forfait
    $stmt_hors_forfait = $pdo->prepare("SELECT *
                                        FROM hors_forfait 
                                        WHERE id_visiteur = :id
                                        ORDER BY id_hors_forfait DESC;");
    $stmt_hors_forfait->execute([':id' => $id]);
    $hors_forfait = $stmt_hors_forfait->fetchAll(PDO::FETCH_ASSOC);

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
    <div class="bg-dark p-4 rounded text-white">
        <h2 class="text-center mb-4">Bienvenue, <strong><?= htmlspecialchars($prenom) . " " . htmlspecialchars($nom)?></strong></h2>
        <p class="text-center">Vous êtes connecté en tant que <strong><?= htmlspecialchars($role) ?></strong>.</p>
    </div>
</div>
<div class="container mt-4">
    <h3 class="text-center mb-4 text-dark">Liste des fiches de frais</h3>
    <table class="table table-bordered text-center">
        <thead class="table-dark">
            <tr>
                <th>Numéro de Fiche</th>
                <th>Date de Soumission</th>
                <th>Total (€)</th>
                <th>Montant remboursé</th>
                <th>Action</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($fiches)) : ?>
                <?php foreach ($fiches as $fiche) : ?>
                    <tr>
                        <td><?= htmlspecialchars($fiche['n°fiche_frais']); ?></td>
                        <td><?= htmlspecialchars($fiche['date_soumission']); ?></td>
                        <td><?= number_format($fiche['total'], 2, ',', ' '); ?> €</td>
                        <td><?= htmlspecialchars($fiche['montant_rembourse']); ?> €</td>
                        <td><?= htmlspecialchars($fiche['statut']); ?></td>
                        <td>
                            <a href="consultationFrais.php?fiche_id=<?= $fiche['n°fiche_frais']; ?>" class="btn btn-primary">Consulter</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="10">Aucune fiche de frais en attente.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Deuxième tableau pour les Hors Forfait -->
<div class="container mt-4">
    <h3 class="text-center mb-4 text-dark">Liste des fiches hors forfait</h3>
    <table class="table table-bordered text-center">
        <thead class="table-dark">
            <tr>
                <th>Fiche Hors Forfait</th>
                <th>Date du Hors Forfait</th>
                <th>Libellé</th>
                <th>Montant (€)</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($hors_forfait)) : ?>
                <?php foreach ($hors_forfait as $hf) : ?>
                    <tr>
                        <td><?= htmlspecialchars($hf['id_hors_forfait']); ?></td>
                        <td><?= htmlspecialchars($hf['date_hors_forfait']); ?></td>
                        <td><?= htmlspecialchars($hf['libelle']); ?></td>
                        <td><?= number_format($hf['montant'], 2, ',', ' '); ?> €</td>
                        <td><?= htmlspecialchars($hf['statut']); ?></td>
                        <td>
                            <a href="consultationHorsForfait.php?hf_id=<?= $hf['id_hors_forfait']; ?>" class="btn btn-primary">Consulter</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="6">Aucun hors forfait enregistré.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>