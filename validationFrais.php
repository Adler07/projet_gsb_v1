<?php
session_start();

$host = 'localhost';
$dbname = 'gsb1';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$fiche_id = $_GET['fiche_id'];

$stmt = $pdo->prepare("
    SELECT ff.montant_repas, ff.montant_hebergement, ff.montant_deplacement, ff.total, ff.date_soumission, sf.statut 
    FROM fiche_frais ff
    JOIN statut_fiche sf ON ff.n°fiche_frais = sf.fiche_id
    WHERE ff.n°fiche_frais = :fiche_id
");
$stmt->execute(['fiche_id' => $fiche_id]);
$fiche = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$fiche) {
    die("Aucune fiche trouvée.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualisation fiche frais</title>
</head>
<body>
    <h1>Visualisation de la fiche de frais</h1>
    <form>
        <label>Montant repas:</label>
        <input type="text" value="<?php echo htmlspecialchars($fiche['montant_repas']); ?>" readonly><br>
        <label>Montant hébergement:</label>
        <input type="text" value="<?php echo htmlspecialchars($fiche['montant_hebergement']); ?>" readonly><br>
        <label>Montant déplacement:</label>
        <input type="text" value="<?php echo htmlspecialchars($fiche['montant_deplacement']); ?>" readonly><br>
        <label>Total:</label>
        <input type="text" value="<?php echo htmlspecialchars($fiche['total']); ?>" readonly><br>
        <label>Date:</label>
        <input type="date" value="<?php echo htmlspecialchars($fiche['date_soumission']); ?>" readonly><br>
    </form>
</body>
</html>