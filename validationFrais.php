<?php
// Récupérer les valeurs du formulaire via la méthode POST
$montant_repas = isset($_POST['montant_repas']) ? htmlspecialchars($_POST['montant_repas']) : '';
$montant_hebergement = isset($_POST['montant_hebergement']) ? htmlspecialchars($_POST['montant_hebergement']) : '';
$montant_deplacement = isset($_POST['montant_deplacement']) ? htmlspecialchars($_POST['montant_deplacement']) : '';
$total = isset($_POST['total']) ? htmlspecialchars($_POST['total']) : '';
$date = isset($_POST['date']) ? htmlspecialchars($_POST['date']) : '';
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
        <input type="text" value="<?php echo $montant_repas; ?>" readonly><br>
        <label>Montant hébergement:</label>
        <input type="text" value="<?php echo $montant_hebergement; ?>" readonly><br>
        <label>Montant déplacement:</label>
        <input type="text" value="<?php echo $montant_deplacement; ?>" readonly><br>
        <label>Total:</label>
        <input type="text" value="<?php echo $total; ?>" readonly><br>
        <label>Date:</label>
        <input type="date" value="<?php echo $date; ?>" readonly><br>
    </form>
    <a href="formulaire.php">Retour au formulaire</a> <!-- Lien pour revenir au formulaire -->
</body>
</html>