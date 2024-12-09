<?php
session_start();
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

$userId = (int)$_SESSION['id'];
$sql = "SELECT nom, prenom, email, telephone, ville, code_postal FROM utilisateur WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$userId]);
$utilisateur = $stmt->fetch();

if (!$utilisateur) {
    die('Utilisateur non trouvé.');
}
if (isset($_POST['update'])) {
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);
    $telephone = htmlspecialchars($_POST['telephone']);
    $ville = htmlspecialchars($_POST['ville']);
    $code_postal = htmlspecialchars($_POST['code_postal']);

    $updateSql = "UPDATE utilisateur SET nom = ?, prenom = ?, email = ?, telephone = ?, ville = ?, code_postal = ? WHERE id = ?";
    $stmt = $pdo->prepare($updateSql);
    
    $stmt->execute([$nom, $prenom, $email, $telephone, $ville, $code_postal, $userId]);

}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Utilisateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-dark navbar-dark p-3">
    <div class="container-fluid">
    <img src="assets\images\Fichier 1.png" alt="Logo" width="150" height="auto" class="d-inline-block align-text-center">
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
</nav>
<div class="container mt-5">
    <h2 class="text-center mb-4">Mon Profil</h2>
    <form method="POST" class="bg-dark p-4 rounded w-50 mx-auto text-white">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom :</label>
            <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($utilisateur['nom']) ?>">
        </div>
        <div class="mb-3">
            <label for="prenom" class="form-label">Prénom :</label>
            <input type="text" class="form-control" id="prenom" name="prenom" value="<?= htmlspecialchars($utilisateur['prenom']) ?>">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email :</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($utilisateur['email']) ?>">
        </div>
        <div class="mb-3">
            <label for="telephone" class="form-label">Téléphone :</label>
            <input type="text" class="form-control" id="telephone" name="telephone" value="<?= htmlspecialchars($utilisateur['telephone']) ?>">
        </div>
        <div class="mb-3">
            <label for="ville" class="form-label">Ville :</label>
            <input type="text" class="form-control" id="ville" name="ville" value="<?= htmlspecialchars($utilisateur['ville']) ?>">
        </div>
        <div class="mb-3">
            <label for="code_postal" class="form-label">Code postal :</label>
            <input type="text" class="form-control" id="code_postal" name="code_postal" value="<?= htmlspecialchars($utilisateur['code_postal']) ?>">
        </div>
        <div class="text-center d-flex justify-content-center gap-5">
            <button type="submit" name="update" class="btn btn-success text-white">Mettre à jour</button>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
