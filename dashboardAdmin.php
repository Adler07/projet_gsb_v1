<?php
session_start();

$prenom = $_SESSION['prenom'];
$nom = $_SESSION['nom'];
$role = $_SESSION['nom_role'];
$id = $_SESSION['id'];

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

$utilisateurs = [];

$sql = "SELECT u.id, u.nom, u.prenom, r.nom_role, u.statut FROM utilisateur u
            INNER JOIN role r ON u.id_role = r.id_role";
$stmt = $pdo->query($sql);
if ($stmt) {
    $utilisateurs = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Utilisateurs</title>
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
                    <a class="nav-link text-white" href="dashboardAdmin.php">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="creerProfil.php">Créer utilisateur</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="gererForfaits.php">Gérer les forfaits</a>
                </li>
                <li class="nav-item">
                    <form action="logout.php" method="post" class="d-inline">
                        <button type="submit" class="btn btn-danger ms-3">Déconnexion</button>
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
<div class="container mt-5">
    <h2 class="text-center mb-4">Liste des Utilisateurs</h2>
    <table class="table table-bordered text-center">
        <thead class="table-dark">
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Rôle</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($utilisateurs)) : ?>
                <?php foreach ($utilisateurs as $utilisateur) : ?>
                    <tr>
                        <td><?= htmlspecialchars($utilisateur['nom']) ?></td>
                        <td><?= htmlspecialchars($utilisateur['prenom']) ?></td>
                        <td><?= htmlspecialchars($utilisateur['nom_role']) ?></td>
                        <td><?= htmlspecialchars($utilisateur['statut']) ?></td>
                        <td><a href="modifierProfil.php?id=<?= $utilisateur['id'] ?>" class="btn btn-primary">Modifier profil</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5">Aucun utilisateur trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="text-center mt-4">
        <a href="creerProfil.php" class="btn btn-success">Créer Profil</a>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>