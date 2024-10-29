<?php
 session_start()
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-300">
    <nav class="bg-white p-4 fixed top-0 left-0 right-0">
        <div class="flex justify-around items-center mr-48">
            <h1 class="font-extrabold text-6xl">GSB</h1>
            <p class="text-2xl">Profil</p>
            <p class="text-2xl">Tableau de bord</p>
            <p class="text-2xl">Gestion de frais</p>
        </div>
    </nav>
    <div class="flex flex-col justify-center content-center gap-2">
        <div></div>
        <div class="flex gap-2"></div>
    </div>
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
    <footer class="bg-white p-9">
        <div class="flex justify-around items-center mr-48">
        <p>© Galaxy Swiss Bourdin 2024</p>
        <p>Mentions Légales</p>
        </div>
    </footer>
</body>
</html>