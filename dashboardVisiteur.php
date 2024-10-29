<?php
 session_start()
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <navbar>
        <form action="logout.php">
            <button>Se déconnecter</button>
        </form>
    </navbar>
    <div class="flex justify-center content-center">
    <?php
    $nom = $_SESSION['name'];
    $role = $_SESSION['role'];
    echo "Bonjour {$nom}! Vous êtes connecté en tant que {$role}";
    ?>
    </div>
    <a href="saisieFrais.php"><button>Nouvelle demande</button></a>


    <?php
    $host = 'localhost';
    $dbname = 'gsb1';
    $username = 'root';
    $password = '';
    
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
    

    $stmt = $pdo->prepare(" SELECT f.n°fiche_frais, f.date_soumission, f.total, s.statut 
    FROM fiche_frais f
    LEFT JOIN statut_fiche s ON f.n°fiche_frais = s.fiche_id
    WHERE f.date_soumission >= DATE_SUB(NOW(), INTERVAL 1 YEAR) 
    ORDER BY f.n°fiche_frais DESC 
    LIMIT 8");
    $stmt->execute();
    $fiches = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    
    <table>
        <thead>
            <tr>
                <th>Numéro de Fiche</th>
                <th>Total</th>
                <th>Date de Soumission</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($fiches as $fiche): ?>
                <tr>
                    <td><?php echo $fiche['n°fiche_frais']; ?></td>
                    <td><?php echo $fiche['total']; ?></td>
                    <td><?php echo $fiche['date_soumission']; ?></td>
                    <td><?php echo $fiche['statut']; ?></td>
                    <td><a href="consultationFrais.php?fiche_id=<?php echo $fiche['n°fiche_frais']; ?>">Consulter</a></td>
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