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
    $telephone = $_POST['telephone'];
    $role = $_POST['role_compte']; 
    $code_postal = $_POST['code_postal'];
    $statut = $_POST['statut'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($password !== $confirmPassword) {
        die('Les mots de passe ne correspondent pas.');
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $roleSql = "SELECT id_role FROM role WHERE nom_role = ?";
    $roleStmt = $pdo->prepare($roleSql);
    $roleStmt->execute([$role]);
    $roleData = $roleStmt->fetch();

    if ($roleData) {
        $idRole = $roleData['id_role']; 

        $sql = "INSERT INTO utilisateur (nom, prenom, email, ville, pass ,telephone, code_postal, statut, id_role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nom, $prenom, $email, $ville, $hashedPassword, $telephone , $code_postal, $statut, $idRole]);

        header('Location: dashboardAdmin.php');
        exit;
    } else {
        die('Rôle non trouvé.');
    }
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
            <label for="password" class="form-label text-white">Mot de passe :</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label text-white">Confirmer le mot de passe :</label>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
        </div>
        <div class="mb-3">
            <label for="ville" class="form-label text-white">Téléphone:</label>
            <input type="text" class="form-control" id="telephone" name="telephone" required>
        </div>
        <div class="mb-3">
            <label for="ville" class="form-label text-white">Ville :</label>
            <input type="text" class="form-control" id="ville" name="ville" required>
        </div>

        <div class="mb-3">
            <label for="role_compte" class="form-label text-white">Rôle :</label>
            <select class="form-control" id="role_compte" name="role_compte" required>
                <option value="Visiteur" selected>Visiteur Médical</option>
                <option value="Comptable">Comptable</option>
                <option value="Administrateur">Administrateur</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="code_postal" class="form-label text-white">Code postal :</label>
            <input type="text" class="form-control" id="code_postal" name="code_postal" required>
        </div>

        <div class="mb-3">
            <label for="statut" class="form-label text-white">Statut :</label>
            <select class="form-control" id="statut" name="statut" required>
                <option value="Actif" selected>Actif</option>
                <option value="Inactif">Inactif</option>
            </select>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-success text-white">Créer</button>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
