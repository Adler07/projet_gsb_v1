<?php 
session_start(); 

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

if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    $email = $_POST["email"];
    $password = $_POST["mdp"];

    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if ($user['pass'] === $password) { 
            
            $_SESSION["name"] = $user["nom"]; 
            $_SESSION["surname"] = $user["prenom"]; 
            $_SESSION["role"] = $user["role_compte"]; 
            if ($user["role_compte"] == "visiteur médical") {
                header("Location: dashboardVisiteur.php");
                exit;
            } else if ($user["role_compte"] == "comptable") {
                header("Location: dashboardComptable.php");
                exit;
            } else if ($user["role_compte"] == "administrateur") {
                header("Location: dashboardAdmin.php");
                exit;
            }
        }
    }
    echo "Email ou mot de passe incorrect";
    header("Refresh: 2; URL=connexion.html");
} 