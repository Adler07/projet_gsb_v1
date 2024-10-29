<?php
 session_start()
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
    <nav class="bg-white p-4">
        <div class="flex justify-around items-center mr-48">
            <h1 class="font-extrabold text-6xl">GSB</h1>
            <p class="text-4xl italic">"La recherche pour un monde meilleur"</p>
        </div>
    </nav>
    <p class="text-red-500 bg-blue-400 underline shadow-lg ring-emerald-500">Test</p>
    <?php
    $nom = $_SESSION['name'];
    $role = $_SESSION['role'];
    echo "Félicitations {$nom}! Vous êtes connecté en tant que {$role}";
    ?>
    <form action="logout.php">
    <button>Se déconnecter</button>
    </form>
    <?php
    if ($nom == NULL || $role == NULL){
        header("Location: connexion.html");
            exit;}
    ?>
</body>
</html>