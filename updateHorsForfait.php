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
    $date_hors_farfait = $_POST['date_hors_forfait'];
    $libelle = $_POST['libelle'];
    $montant = $_POST['montant'];
    $justificatif = $_FILES['justificatif'];
 
    
    if (empty($fiche_id) || empty($date_hors_farfait) || empty($libelle) || empty($montant) || empty($justificatif)) {
        die("Tous les champs sont obligatoires.");
    }

    try {
      
        $stmt = $pdo->prepare("
            UPDATE hors_forfait 
            SET date_hors_forfait = :date_hors_forfait,
                libelle = :libelle, 
                montant = :montant, 
                justificatif = :justificatif
                WHERE `id_hors_forfait` = :fiche_id
        ");


        $stmt->execute([
            'fiche_id' => $fiche_id,
            'date_hors_forfait' => $date_hors_farfait,
            'libelle' => $libelle,
            'montant' => $montant,
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