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
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $ville = $_POST['ville'];
    $role = $_POST['role_compte'];
    $age = $_POST['age'];
    $statut = $_POST['statut'];

    $sql = "INSERT INTO utilisateur (nom, prenom, email, ville, role_compte, age, statut) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $prenom, $email, $ville, $role, $age, $statut]);

    header('Location: dashboardAdmin.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer Profil</title>
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
    <h2 class="text-center mb-4 text-dark">Créer un nouveau profil</h2>
    <form method="post" class="bg-dark p-4 rounded w-50 mx-auto">
        <div class="mb-3">
            <label for="nom" class="form-label text-white">Nom :</label>
            <input type="text" class="form-control" id="nom" name="nom" required>
        </div>

        <div class="mb-3">
            <label for="prenom" class="form-label text-white">Prénom :</label>
            <input type="text" class="form-control" id="prenom" name="prenom" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label text-white">Email :</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="mb-3">
            <label for="ville" class="form-label text-white">Ville :</label>
            <input type="text" class="form-control" id="ville" name="ville" required>
        </div>

        <div class="mb-3">
            <label for="role_compte" class="form-label text-white">Rôle :</label>
            <input type="text" class="form-control" id="role_compte" name="role_compte" required>
        </div>

        <div class="mb-3">
            <label for="code_postal" class="form-label text-white">Code postal :</label>
            <input type="number" class="form-control" id="code_postal" name="code_postal" required>
        </div>

        <div class="mb-3">
            <label for="statut" class="form-label text-white">Statut :</label>
            <input type="text" class="form-control" id="statut" name="statut" required>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-success text-white">Créer</button>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
