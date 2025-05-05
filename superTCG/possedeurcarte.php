<?php

session_start();
include('./config.php');
include('common/navbar.php');

if (!isset($_GET['id'])) {
    echo "ID de la carte manquant.";
    exit;
}

$carte_id = $_GET['id'];

$stmt = $conn->prepare("SELECT users.user_id, users.username, users.role, collection.quantite FROM collection INNER JOIN users ON collection.user_id = users.user_id WHERE collection.carte_id = :carte_id");
$stmt->bindParam(':carte_id', $carte_id);
$stmt->execute();
$possesseurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/gererCS.css">
    <title>Possesseurs de la carte</title>
</head>
<body>
    <h1>Utilisateurs possédant cette carte</h1>

    <?php if (count($possesseurs) > 0): ?>
        <table border="1">
            <tr>
                <th>ID Utilisateur</th>
                <th>Nom d'utilisateur</th>
                <th>Rôle</th>
                <th>Quantité</th>
            </tr>
            <?php foreach ($possesseurs as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['user_id']) ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['role']) ?></td>
                    <td><?= htmlspecialchars($user['quantite']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Aucun utilisateur ne possède cette carte.</p>
    <?php endif; ?>
</body>
</html>
