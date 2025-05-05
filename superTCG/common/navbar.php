<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="common/style.css">
</head>
<body>
    <div class="navbar">
        <ul>
            <li><a href="index.php"><p>Accueil</p></a></li>
            <p><?echo $_SESSION["role"];?></p>
            <?php if (isset($_SESSION["role"])): ?>
                
                <?php if ($_SESSION["role"] === "user"): ?>
                    <li><a href="RechercheCarte.php"><p>Cartes disponibles</p></a></li>
                    <li><a href="macollection.php"><p>Ma collection</p></a></li>

                <?php elseif ($_SESSION["role"] === "admin"): ?>
                    <li><a href="gererutilisateur.php"><p>Gerer les utilisateurs</p></a></li>
                    <li><a href="gerercarte.php"><p>Gerer les cartes</p></a></li>
                <?php endif; ?>

                <li><a href="deconnexion.php"><p>Déconnexion</p></a></li>

            <?php else: ?>
                <li><a href="inscription.php"><p>Créer un compte</p></a></li>
                <li><a href="connexion.php"><p>Se connecter</p></a></li>
            <?php endif; ?>
        </ul>
    </div>
</body>
</html>
