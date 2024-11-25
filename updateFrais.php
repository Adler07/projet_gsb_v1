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
    $nombre_repas = $_POST['nombre_repas'];
    $montant_hebergement = $_POST['montant_hebergement'];
    $nombre_hebergement = $_POST['nombre_hebergement'];
    $montant_deplacement = $_POST['total_deplacement'];
    $nombre_deplacement = $_POST['nombre_deplacement'];
    $total = $_POST['total'];
    $date_soumission = $_POST['date'];
    $justificatif = $_FILES['justificatif'];
    $kilometres_voiture = $_POST['kilometres_voiture'];

    
    if (empty($fiche_id) || empty($montant_repas) || empty($montant_hebergement) || empty($total) || empty($date_soumission)) {
        die("Tous les champs sont obligatoires.");
    }

    try {
      
        $stmt = $pdo->prepare("
            UPDATE fiche_frais 
            SET montant_repas = :montant_repas, 
                nombre_repas = :nombre_repas, 
                montant_hebergement = :montant_hebergement, 
                nombre_hebergement = :nombre_hebergement, 
                montant_deplacement = :montant_deplacement, 
                nombre_deplacement = :nombre_deplacement, 
                total = :total, 
                date_soumission = :date_soumission,
                justificatif = :justificatif,
                kilometres_voiture = :kilometres_voiture
            WHERE `n°fiche_frais` = :fiche_id
        ");


        $stmt->execute([
            'fiche_id' => $fiche_id,
            'montant_repas' => $montant_repas,
            'nombre_repas' => $nombre_repas,
            'montant_hebergement' => $montant_hebergement,
            'nombre_hebergement' => $nombre_hebergement,
            'montant_deplacement' => $montant_deplacement,
            'nombre_deplacement' => $nombre_deplacement,
            'total' => $total,
            'date_soumission' => $date_soumission,
            'kilometres_voiture' => $kilometres_voiture,
            'justificatif' => $justificatif
            
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