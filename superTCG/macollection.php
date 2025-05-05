<?php
session_start();
require_once 'config.php'; 
include('common/navbar.php');

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['id'];

$sql = "
    SELECT carte.nom, carte.description, carte.chemin_image, carte.rarete, collection.quantite
    FROM collection
    INNER JOIN carte ON collection.carte_id = carte.carte_id
    WHERE collection.user_id = :user_id
";

$stmt = $conn->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$cartes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ma Collection</title>
  <link rel="stylesheet" href="css/col.css">

</head>
<body>
    <h1>Ma Collection</h1>

    <?php if (count($cartes) > 0): ?>
        <?php foreach ($cartes as $carte): ?>
            <div class="carte">
                <img src="<?= htmlspecialchars($carte['chemin_image']) ?>" alt="<?= htmlspecialchars($carte['nom']) ?>">
                <h2><?= htmlspecialchars($carte['nom']) ?></h2>
                <p><?= htmlspecialchars($carte['description']) ?></p>
                <p class="rarete-<?= htmlspecialchars($carte['rarete']) ?>">
                    Rareté : <?= htmlspecialchars($carte['rarete']) ?>
                </p>
                <p>Quantité : <?= (int)$carte['quantite'] ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Vous n'avez aucune carte dans votre collection.</p>
    <?php endif; ?>
</body>
</html>
