
<?php
session_start();
include('common/navbar.php');
include('./config.php');

if (isset($_POST['Connexion'])) {
    if (!empty($_POST['username']) && !empty($_POST['motdepasse'])) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$_POST['username']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($_POST['motdepasse'], $user['password'])) {
            $_SESSION["role"] = $user['role'];
            $_SESSION["id"] = $user['user_id'];
            header("Location: index.php");
            exit;
        }
        $error = "Identifiants incorrects.";
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion | SuperTCG</title>
   <link rel="stylesheet" href="css/conexionS.css">
   
</head>
<body>
    <div class="scanlines"></div>
    
    <div class="login-container">
        <h1 class="titre">Accès Système</h1>
        
        <?php if (isset($error)): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="post" class="login-form">
            <div class="input-group">
                <label for="username">Identifiant</label>
                <input type="text" id="username" name="username" placeholder="Entrez votre pseudo" required>
            </div>
            
            <div class="input-group">
                <label for="motdepasse">Mot de passe</label>
                <input type="password" id="motdepasse" name="motdepasse" placeholder="••••••••" required>
            </div>
            
            <input type="submit" name="Connexion" value="Connexion" class="submit-btn">
        </form>
        
        <div class="register-link">
            Nouvel utilisateur? <a href="inscription.php">Créer un compte</a>
        </div>
    </div>

</body>
</html>