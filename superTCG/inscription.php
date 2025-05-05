<?php
include('./config.php');
include('common/navbar.php');

if (isset($_POST['cree'])) {
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        if ($_POST['password'] == $_POST['password2']) {
            $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO `users`(`username`,`password`) VALUES (:username, :ppassword)");
            $stmt->bindParam(':username', $_POST['username']);
            $stmt->bindParam(':ppassword', $passwordHash);
            $stmt->execute();
            header("Location: connexion.php");
            exit;
        } else {
            echo "Les mots de passe ne correspondent pas";
        }
    } else {
        echo "Veuillez remplir tous les champs";
    }
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/inscriptionS.css">
</head>

<body class="container">
    <div>
        <h1>Créer un compte</h1>
        <form method="post" enctype="multipart/form-data">
            <label for="username">Pseudo</label>
            <input type="text" name="username" id="nom" required>

            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required>

            <label for="password2">Confirmer mot de passe</label>
            <input type="password" name="password2" id="password2" required>

            <input type="submit" name="cree" value="Créer">
        </form>
    </div>
</body>

</html>