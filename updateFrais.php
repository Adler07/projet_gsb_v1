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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fiche_id = $_POST['fiche_id'];
    $montant_repas = $_POST['montant_repas'];
    $montant_hebergement = $_POST['montant_hebergement'];
    $montant_deplacement = $_POST['montant_deplacement'];
    $total = $_POST['total'];
    $date_soumission = $_POST['date_soumission'];

    
    if (empty($fiche_id) || empty($montant_repas) || empty($montant_hebergement) || empty($montant_deplacement) || empty($total) || empty($date_soumission)) {
        die("Tous les champs sont obligatoires.");
    }

    try {
      
        $stmt = $pdo->prepare("
            UPDATE fiche_frais 
            SET montant_repas = :montant_repas, 
                montant_hebergement = :montant_hebergement, 
                montant_deplacement = :montant_deplacement, 
                total = :total, 
                date_soumission = :date_soumission
            WHERE `n°fiche_frais` = :fiche_id
        ");


        $stmt->execute([
            'montant_repas' => $montant_repas,
            'montant_hebergement' => $montant_hebergement,
            'montant_deplacement' => $montant_deplacement,
            'total' => $total,
            'date_soumission' => $date_soumission,
            'fiche_id' => $fiche_id
        ]);

        header("Location: dashboardVisiteur.php");
        exit;

    } catch (PDOException $e) {
        die("Erreur lors de la mise à jour : " . $e->getMessage());
    }
} else {
    die("Requête invalide.");
}
?>