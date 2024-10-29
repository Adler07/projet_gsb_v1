<?php
 session_start();
 $nom = $_SESSION['name'];
 $role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <nav>
        <div>
            <h1>GSB</h1>
            <p>"La recherche pour un monde meilleur"</p>
        </div>
    </nav>
    <?php
    $nom = $_SESSION['name'];
    $role = $_SESSION['role'];
    echo "Félicitations {$nom}! Vous êtes connecté en tant que {$role}";
    ?>
    <form action="logout.php">
    <button>Se déconnecter</button>
    </form>
    <table>
        <thead>
            <tr>
                <th>Numéro de Fiche</th>
                <th>Total (€)</th>
                <th>Date de Soumission</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
             $host = 'localhost';
             $dbname = 'gsb1';
             $username = 'root';
             $password = '';
         
             try {
                 $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
                 $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                 
                 $stmt = $pdo->prepare("SELECT f.`n°fiche_frais` , f.total, f.date_soumission, s.statut FROM fiche_frais f 
                 Join statut_fiche s ON f.`n°fiche_frais` = s.fiche_id WHERE statut = 'En attente' ORDER BY f.date_soumission DESC;");
                 $stmt->execute();
                 $fiches = $stmt->fetchAll(PDO::FETCH_ASSOC);
         
             } catch (PDOException $e) {
                 echo "Erreur : " . $e->getMessage();
             }

            foreach ($fiches as $fiche): ?>
                <tr>
                    <td><?php echo $fiche['n°fiche_frais']; ?></td>
                    <td><?php echo number_format($fiche['total'], 2); ?></td>
                    <td><?php echo $fiche['date_soumission']; ?></td>
                    <td><?php echo $fiche['statut']; ?></td>
                    <td><a href="validationFrais.php?fiche_id=<?php echo $fiche['n°fiche_frais']; ?>">Consulter</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
    if ($nom == NULL || $role == NULL){
        header("Location: connexion.html");
            exit;}
    ?>
</body>
</html>