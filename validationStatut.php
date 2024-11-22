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
    $fiche_id = $_POST['fiche_id'] ?? null;
    $validation = $_POST['validation'];
    $raison = $_POST['raison'] ?? null;
    $montant_partiel = $_POST['montant_partiel'] ?? null;

    if (!$fiche_id) {
        die("ID de fiche non spécifié.");
    }
  
    $nouveau_statut = '';
    switch ($validation) {
        case 'Valider':
            $nouveau_statut = 'Remboursé';
            $raison = null; 
            break;
        case 'Partiel':
            $nouveau_statut = 'Remboursement Partiel';
            break;
        case 'Refuser':
            $nouveau_statut = 'Refusé';
            $montant_partiel = null; 
            break;
        default:
            die("Option de validation non reconnue.");
    }

 
    $sql = "
        UPDATE statut_fiche 
        SET statut = :statut, raison = :raison
        WHERE fiche_id = :fiche_id
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'statut' => $nouveau_statut,
        'raison' => $raison,
        'fiche_id' => $fiche_id,
    ]);

    if ($validation === 'Partiel' && $montant_partiel !== null) {
        $sqlPartiel = "
            UPDATE fiche_frais 
            SET total = :montant_partiel 
            WHERE `n°fiche_frais` = :fiche_id
        ";
        $stmtPartiel = $pdo->prepare($sqlPartiel);
        $stmtPartiel->execute([
            'montant_partiel' => $montant_partiel,
            'fiche_id' => $fiche_id,
        ]);
    }
    header('Location: dashboardComptable.php');
    exit;
} else {
    die("Requête invalide.");
}
?>